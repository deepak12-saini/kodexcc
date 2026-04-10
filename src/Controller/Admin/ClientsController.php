<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Admin CRM clients: /admin/clients/...
 */
class ClientsController extends AppController
{
    /**
     * FormHelper may submit `client[...]`; legacy save expects `Client`.
     *
     * @return array<string, mixed>
     */
    private function clientRequestData(): array
    {
        $d = $this->requestData();
        if (isset($d['client']) && !isset($d['Client'])) {
            $d['Client'] = $d['client'];
        }

        return $d;
    }

    public function __get(string $name): mixed
    {
        if (preg_match('/^[A-Z][A-Za-z0-9_]*$/', $name) === 1) {
            $adapter = $this->legacyModel($name);
            if ($adapter !== null) {
                return $adapter;
            }
        }

        return parent::__get($name);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
    }

    public function commentDownload(?string $id = null): Response
    {
        $this->checkAdminSession();
        if ($id === null || $id === '') {
            return $this->response->withStatus(404);
        }
        $decoded = base64_decode(base64_decode($id, true) ?: '', true);
        if ($decoded === false || $decoded === '') {
            return $this->response->withStatus(404);
        }
        $basename = basename(str_replace(['..', '\\'], '', $decoded));
        $path = WWW_ROOT . 'clientdoc' . DIRECTORY_SEPARATOR . $basename;
        if (!is_file($path)) {
            return $this->response->withStatus(404);
        }

        return $this->response->withFile($path, [
            'download' => true,
            'name' => $basename,
        ]);
    }

    public function addComment(?string $clientId = null): void
    {
        $this->viewBuilder()->setLayout('admin_new_layout');
        $this->set('title_for_layout', SITENAME . ' Employee Comments');
        $this->checkAdminSession();

        $userId = $this->Session->read('User.id');
        $post = $this->requestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $commentText = $post['comment'] ?? $post['data']['comment'] ?? '';
            $insert = [
                'ClientComment' => [
                    'comment' => $commentText,
                    'client_id' => $clientId,
                    'admin_id' => $userId,
                    'type' => 1,
                    'created' => date('Y-m-d H:i:s'),
                ],
            ];
            $uploaded = $this->getRequest()->getUploadedFile('documents');
            if ($uploaded !== null && $uploaded->getError() === UPLOAD_ERR_OK) {
                $name = $uploaded->getClientFilename();
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
                $uploaded->moveTo(WWW_ROOT . 'clientdoc' . DIRECTORY_SEPARATOR . $filename);
                $insert['ClientComment']['documents'] = $filename;
            }
            if ($this->ClientComment->save($insert)) {
                $this->Session->setFlash('Comment posted successfully', 'default', ['class' => 'alert alert-success']);
            }
            $this->redirect(['action' => 'comment', $clientId]);
            return;
        }

        $this->set('client_id', $clientId);
    }

    public function comment(?string $clientId = null): void
    {
        $this->viewBuilder()->setLayout('admin_new_layout');
        $this->set('title_for_layout', SITENAME . ' Employee Comments');
        $this->checkAdminSession();

        $comments = $this->fetchTable('ClientComment')->find()
            ->where(['ClientComment.client_id' => $clientId])
            ->contain(['Client', 'User', 'NappUser'])
            ->orderByDesc('ClientComment.id')
            ->all();

        $rows = [];
        foreach ($comments as $entity) {
            $rows[] = $this->normalizeClientCommentRow($entity);
        }
        $this->set('clientArr', $rows);
        $this->set('client_id', $clientId);
    }

    public function assign(): ?Response
    {
        $this->autoRender = false;
        if (!$this->getRequest()->is('post')) {
            return $this->redirect(['action' => 'index']);
        }
        $post = $this->requestData();
        $data = $post['data'] ?? [];
        $staffId = $data['client_id'] ?? $post['client_id'] ?? null;
        $selectIds = $post['selectids'] ?? [];
        if ($staffId !== null && $staffId !== '' && is_array($selectIds) && $selectIds !== []) {
            foreach ($selectIds as $clientId) {
                $existing = $this->StaffClient->find('first', [
                    'conditions' => [
                        'StaffClient.client_id' => $clientId,
                        'StaffClient.staff_id' => $staffId,
                    ],
                ]);
                if ($existing === []) {
                    $this->StaffClient->save([
                        'StaffClient' => [
                            'staff_id' => $staffId,
                            'client_id' => $clientId,
                        ],
                    ]);
                }
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    public function index(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' customer list');
        $this->checkAdminSession();

        $staffClientArr = [];
        $clientId = $this->Session->read('client.id');
        if ($id === 'clear') {
            $this->Session->delete('client');
            $this->redirect(['action' => 'index']);
            return;
        }
        if ($id !== null && $id !== '' && $id !== 'clear') {
            $this->Session->write('client.id', $id);
            $clientId = $id;
            $staffClientArr = $this->StaffClient->find('list', [
                'conditions' => ['StaffClient.staff_id' => $id],
                'fields' => ['client_id', 'client_id'],
            ]);
        } elseif (!empty($clientId)) {
            $staffClientArr = $this->StaffClient->find('list', [
                'conditions' => ['StaffClient.staff_id' => $clientId],
                'fields' => ['client_id', 'client_id'],
            ]);
        }

        $this->set('clientId', $clientId);
        $this->set('StaffClientArr', $staffClientArr);

        $sessionClient = $this->Session->read('client');
        $categoryId = '';
        $search = '';
        $filterStaffId = '';
        $searchClientIds = null;

        $post = $this->requestData();
        $nested = $post['data'] ?? [];

        if ($post !== []) {
            $categoryId = (string)($nested['category_id'] ?? $post['category_id'] ?? '');
            $search = trim((string)($post['search'] ?? ''));
            $filterStaffId = (string)($nested['client_id'] ?? $post['client_id'] ?? '');
            if ($filterStaffId !== '') {
                $searchClientIds = $this->StaffClient->find('list', [
                    'conditions' => ['StaffClient.staff_id' => $filterStaffId],
                    'fields' => ['client_id', 'client_id'],
                ]);
                $this->Session->write('client.client_id', $filterStaffId);
            } else {
                $this->Session->delete('client.client_id');
            }
            if ($categoryId !== '') {
                $this->Session->write('client.category_id', $categoryId);
            } else {
                $this->Session->delete('client.category_id');
            }
            if ($search !== '') {
                $this->Session->write('client.search', $search);
            } else {
                $this->Session->delete('client.search');
            }
            $this->applyAdminIndexConditionsFromPost($categoryId, $search, $searchClientIds);
        } elseif (!empty($sessionClient)) {
            $filterStaffId = (string)($this->Session->read('client.client_id') ?? '');
            if ($filterStaffId !== '') {
                $searchClientIds = $this->StaffClient->find('list', [
                    'conditions' => ['StaffClient.staff_id' => $filterStaffId],
                    'fields' => ['client_id', 'client_id'],
                ]);
            } else {
                $this->Session->delete('client.client_id');
            }
            $categoryId = (string)($this->Session->read('client.category_id') ?? '');
            $search = trim((string)($this->Session->read('client.search') ?? ''));
            $this->applyAdminIndexConditionsFromSession($categoryId, $search, $searchClientIds);
        } else {
            $this->paginate = [
                'limit' => 300,
                'order' => ['Client.fname' => 'ASC'],
                'sortableFields' => $this->clientSortableFields(),
            ];
        }

        $clientsTable = $this->fetchTable('Client');
        $query = $clientsTable->find()
            ->contain(['ClientType', 'StaffClients' => ['NappUser']]);

        $paginateOpts = $this->paginate;
        if (isset($paginateOpts['conditions'])) {
            $query->where($paginateOpts['conditions']);
            unset($this->paginate['conditions']);
        }

        $paged = $this->paginate($query);
        $clientArr = [];
        foreach ($paged as $entity) {
            $clientArr[] = $this->normalizeLegacyClientRow($entity);
        }
        $this->set('clientArr', $clientArr);
        $this->set('clientArrPaginated', $paged);

        $this->set('ClientTypeArr', $this->wrapClientTypeRows(
            $this->fetchTable('ClientType')->find()->all()->toList()
        ));

        $this->set('category_id', $categoryId);
        $this->set('search', $search);

        $userPer = $this->fetchTable('UserPermission')->find('list', [
            'conditions' => ['UserPermission.meta_val' => 'sr'],
            'valueField' => 'user_id',
        ])->toArray();
        $ids = array_values($userPer);
        $cuser = [];
        if ($ids !== []) {
            $cuser = $this->fetchTable('NappUser')->find()
                ->where(['NappUser.is_staff_id' => 1, 'NappUser.id IN' => $ids])
                ->select(['id', 'name', 'lname'])
                ->all()
                ->toList();
        }
        $this->set('cuser', $this->wrapNappUserRows($cuser));
        $this->set('client_id', $filterStaffId);
    }

    /**
     * @param array<int|string, mixed>|null $searchClientIds
     */
    private function applyAdminIndexConditionsFromPost(
        string $categoryId,
        string $search,
        ?array $searchClientIds,
    ): void {
        $hasCat = $categoryId !== '';
        $hasSearch = $search !== '';
        $hasSc = $searchClientIds !== null && $searchClientIds !== [];

        if ($hasCat && $hasSearch && $hasSc) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->mergeAdminWhere(
                    ['Client.id IN' => array_values($searchClientIds), 'Client.category_id' => $categoryId],
                    $this->clientSearchOr($search),
                ),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasCat && $hasSc) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => [
                    'Client.id IN' => array_values($searchClientIds),
                    'Client.category_id' => $categoryId,
                ],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasCat && $hasSearch) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->mergeAdminWhere(
                    ['Client.category_id' => $categoryId],
                    $this->clientSearchOr($search),
                ),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasSc) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => ['Client.id IN' => array_values($searchClientIds)],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasCat) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => ['Client.category_id' => $categoryId],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasSearch) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->clientSearchOr($search),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif (empty($searchClientIds)) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => ['Client.id' => 0],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } else {
            $this->paginate = [
                'limit' => 300,
                'order' => ['Client.fname' => 'ASC'],
                'sortableFields' => $this->clientSortableFields(),
            ];
        }
    }

    /**
     * @param array<int|string, mixed>|null $searchClientIds
     */
    private function applyAdminIndexConditionsFromSession(
        string $categoryId,
        string $search,
        ?array $searchClientIds,
    ): void {
        $hasCat = $categoryId !== '';
        $hasSearch = $search !== '';
        $hasSc = $searchClientIds !== null && $searchClientIds !== [];

        if ($hasCat && $hasSearch && $hasSc) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->mergeAdminWhere(
                    ['Client.id IN' => array_values($searchClientIds), 'Client.category_id' => $categoryId],
                    $this->clientSearchOr($search),
                ),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasCat && $hasSc) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => [
                    'Client.id IN' => array_values($searchClientIds),
                    'Client.category_id' => $categoryId,
                ],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasCat && $hasSearch) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->mergeAdminWhere(
                    ['Client.category_id' => $categoryId],
                    $this->clientSearchOr($search),
                ),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasSc) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => ['Client.id IN' => array_values($searchClientIds)],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasCat) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => ['Client.category_id' => $categoryId],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasSearch) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->clientSearchOr($search),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } else {
            $this->paginate = [
                'limit' => 300,
                'order' => ['Client.fname' => 'ASC'],
                'sortableFields' => $this->clientSortableFields(),
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function clientSearchOr(string $search): array
    {
        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $search) . '%';

        return [
            'OR' => [
                'Client.mobile LIKE' => $like,
                'Client.phone LIKE' => $like,
                'Client.fname LIKE' => $like,
                'Client.name LIKE' => $like,
                'Client.email LIKE' => $like,
                'Client.landline LIKE' => $like,
            ],
        ];
    }

    /**
     * @param array<string, mixed> $base
     * @param array<string, mixed> $orBlock
     * @return array<string, mixed>
     */
    private function mergeAdminWhere(array $base, array $orBlock): array
    {
        return array_merge($base, $orBlock);
    }

    /**
     * @return list<string>
     */
    private function clientSortableFields(): array
    {
        return [
            'Client.id',
            'Client.fname',
            'Client.name',
            'Client.email',
            'Client.mobile',
            'Client.landline',
            'Client.company',
            'Client.address1',
            'Client.category_id',
            'Client.status',
        ];
    }

    public function import(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Import Customer');
        $this->checkAdminSession();

        $post = $this->clientRequestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $uploaded = $this->getRequest()->getUploadedFile('file');
            if ($uploaded !== null && $uploaded->getError() === UPLOAD_ERR_OK) {
                $name = $uploaded->getClientFilename();
                $path = WWW_ROOT . 'files' . DIRECTORY_SEPARATOR . $name;
                $uploaded->moveTo($path);
                $categoryId = $post['Client']['category_id'] ?? $post['data']['Client']['category_id'] ?? null;
                $i = 0;
                if (($openfile = fopen($path, 'r')) !== false) {
                    while (($getdata = fgetcsv($openfile, 1000, ',')) !== false) {
                        if ($i > 0) {
                            $existing = $this->Client->find('first', [
                                'conditions' => [
                                    'Client.mobile' => $getdata[2] ?? '',
                                    'Client.email' => $getdata[5] ?? '',
                                ],
                            ]);
                            if ($existing === []) {
                                $clients = [
                                    'Client' => [
                                        'category_id' => $categoryId,
                                        'company' => $getdata[0] ?? '',
                                        'fname' => $getdata[1] ?? '',
                                        'mobile' => $getdata[2] ?? '',
                                        'landline' => $getdata[3] ?? '',
                                        'address1' => $getdata[4] ?? '',
                                        'email' => $getdata[5] ?? '',
                                        'website' => $getdata[6] ?? '',
                                        'status' => 1,
                                        'created' => date('Y-m-d H:i:s'),
                                    ],
                                ];
                                $this->Client->save($clients);
                            }
                        }
                        $i++;
                    }
                    fclose($openfile);
                    $this->Session->setFlash('The customer has been saved', 'default', ['class' => 'alert alert-success']);
                    $this->redirect(['action' => 'index']);
                    return;
                }
            }
        }

        $this->set('ClientTypeArr', $this->wrapClientTypeRows(
            $this->fetchTable('ClientType')->find()->all()->toList()
        ));
    }

    public function add(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add New customer');
        $this->checkAdminSession();

        $clientTable = $this->fetchTable('Client');
        $client = $clientTable->newEmptyEntity();

        if ($this->getRequest()->is('post')) {
            if ($this->Client->save($this->clientRequestData())) {
                $this->Session->setFlash('The customer has been saved', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash('The customer could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
            $post = $this->clientRequestData();
            $client = $clientTable->patchEntity($client, $post['Client'] ?? $post['data']['Client'] ?? []);
        }

        $this->set('client', $client);
        $this->set('ClientTypeArr', $this->wrapClientTypeRows(
            $this->fetchTable('ClientType')->find()->all()->toList()
        ));

        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add New customer');
        $this->checkAdminSession();

        $clientTable = $this->fetchTable('Client');

        if ($this->getRequest()->is('post')) {
            $data = $this->clientRequestData();
            if (!isset($data['Client'])) {
                $data['Client'] = [];
            }
            $data['Client']['id'] = $id;
            $this->setRequestData($data);
            if ($this->Client->save($data)) {
                $this->Session->setFlash('The customer has been updated', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash('The customer could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        }

        $clientArr = $this->Client->find('first', ['conditions' => ['Client.id' => $id]]);
        $client = $clientTable->patchEntity($clientTable->newEmptyEntity(), $clientArr['Client'] ?? []);
        if ($this->getRequest()->is('post')) {
            $post = $this->clientRequestData();
            $client = $clientTable->patchEntity($client, $post['Client'] ?? $post['data']['Client'] ?? []);
        }
        $this->set('client', $client);
        $this->set('clientArr', $clientArr);
        $this->set('ClientTypeArr', $this->wrapClientTypeRows(
            $this->fetchTable('ClientType')->find()->all()->toList()
        ));

        return null;
    }

    public function delete(?string $id = null): Response
    {
        $this->checkAdminSession();
        $this->Client->id = (int)($id ?? 0);
        if (!$this->Client->exists()) {
            throw new NotFoundException(__('Invalid client'));
        }
        if ($this->Client->delete()) {
            $this->Session->setFlash('The client has been deleted.', 'default', ['class' => 'alert alert-success']);
        } else {
            $this->Session->setFlash('The client could not be deleted. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function clienttypeAdd(): ?Response
    {
        $this->checkAdminSession();
        $this->viewBuilder()->disableAutoLayout();

        $ctTable = $this->fetchTable('ClientType');
        $clientType = $ctTable->newEmptyEntity();

        if ($this->getRequest()->is('post') && $this->ClientType->save($this->requestData())) {
            return $this->redirect(['action' => 'clienttype']);
        }
        if ($this->getRequest()->is('post')) {
            $post = $this->requestData();
            $clientType = $ctTable->patchEntity($clientType, $post['ClientType'] ?? []);
        }
        $this->set('clientType', $clientType);

        return null;
    }

    public function clienttypeEdit(?string $id = null): ?Response
    {
        $this->checkAdminSession();
        $this->viewBuilder()->disableAutoLayout();

        $ctTable = $this->fetchTable('ClientType');

        if ($this->getRequest()->is('post')) {
            $data = $this->requestData();
            if (!isset($data['ClientType'])) {
                $data['ClientType'] = [];
            }
            $data['ClientType']['id'] = $id;
            $this->setRequestData($data);
            if ($this->ClientType->save($data)) {
                return $this->redirect(['action' => 'clienttype']);
            }
        }

        $ClientTypeArr = $this->ClientType->find('first', ['conditions' => ['ClientType.id' => $id]]);
        $this->set('ClientTypeArr', $ClientTypeArr);
        $clientType = $ctTable->patchEntity($ctTable->newEmptyEntity(), $ClientTypeArr['ClientType'] ?? []);
        if ($this->getRequest()->is('post')) {
            $post = $this->requestData();
            $clientType = $ctTable->patchEntity($clientType, $post['ClientType'] ?? []);
        }
        $this->set('clientType', $clientType);

        return null;
    }

    public function clienttype(): void
    {
        $this->checkAdminSession();
        $this->viewBuilder()->disableAutoLayout();

        $ct = $this->fetchTable('ClientType');
        $query = $ct->find()->orderAsc($ct->aliasField('id'));
        $this->paginate = [
            'limit' => 50,
            'sortableFields' => ['ClientType.id', 'ClientType.name'],
        ];
        $paged = $this->paginate($query);
        $rows = [];
        foreach ($paged as $entity) {
            $rows[] = ['ClientType' => $entity->toArray()];
        }
        $this->set('ClientTypeArr', $rows);
        $this->set('clientTypePaginated', $paged);
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeLegacyClientRow(EntityInterface $client): array
    {
        $full = $client->toArray();
        unset($full['client_type'], $full['staff_clients']);
        $full = $this->mergeClientLegacyDisplayFields($full);
        $row = ['Client' => $full];
        $ct = $client->get('client_type');
        $row['ClientType'] = $ct instanceof EntityInterface ? $ct->toArray() : ['name' => ''];

        $row['StaffClient'] = [];
        foreach ($client->get('staff_clients') ?? [] as $sc) {
            if (!$sc instanceof EntityInterface) {
                continue;
            }
            $sca = $sc->toArray();
            $nu = $sca['napp_user'] ?? [];
            unset($sca['napp_user']);
            $row['StaffClient'][] = ['StaffClient' => $sca, 'NappUser' => $nu];
        }

        return $row;
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeClientCommentRow(EntityInterface $entity): array
    {
        $a = $entity->toArray();
        $user = $a['user'] ?? [];
        $napp = $a['napp_user'] ?? [];
        unset($a['user'], $a['napp_user'], $a['client']);
        $out = ['ClientComment' => $a, 'User' => $user, 'NappUser' => $napp];
        if ($entity->get('client') instanceof EntityInterface) {
            $out['Client'] = $entity->get('client')->toArray();
        } else {
            $out['Client'] = [];
        }

        return $out;
    }

    /**
     * @param list<EntityInterface|array<string, mixed>> $rows
     * @return list<array<string, mixed>>
     */
    private function wrapClientTypeRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            $out[] = ['ClientType' => $row instanceof EntityInterface ? $row->toArray() : $row];
        }

        return $out;
    }

    /**
     * @param list<EntityInterface|array<string, mixed>> $rows
     * @return list<array<string, mixed>>
     */
    private function wrapNappUserRows(array $rows): array
    {
        $out = [];
        foreach ($rows as $row) {
            $out[] = ['NappUser' => $row instanceof EntityInterface ? $row->toArray() : $row];
        }

        return $out;
    }
}
