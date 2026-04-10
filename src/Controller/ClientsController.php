<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Staff CRM clients: /clients/...
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
        $this->checkSatffSession();
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

    public function index(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' customer list');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $this->set('user_id', $userId);

        if ($id === 'clear') {
            $this->Session->delete('client');
            $this->redirect(['action' => 'index']);
            return;
        }

        $staffClientArr = $this->StaffClient->find('list', [
            'conditions' => ['StaffClient.staff_id' => $userId],
            'fields' => ['client_id', 'client_id'],
        ]);
        $staffIds = array_values($staffClientArr);

        $sessionClient = $this->Session->read('client');
        $categoryId = '';
        $search = '';
        $post = $this->requestData();
        $nested = $post['data'] ?? [];

        if ($post !== []) {
            $categoryId = (string)($nested['category_id'] ?? $post['category_id'] ?? '');
            $search = trim((string)($post['search'] ?? ''));
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
            $this->applyStaffIndexPaginate($categoryId, $search, $staffIds);
        } elseif (!empty($sessionClient)) {
            $categoryId = (string)($this->Session->read('client.category_id') ?? '');
            $search = trim((string)($this->Session->read('client.search') ?? ''));
            $this->applyStaffIndexPaginate($categoryId, $search, $staffIds);
        } else {
            if ($staffIds === []) {
                $this->paginate = [
                    'limit' => 300,
                    'conditions' => ['Client.id' => 0],
                    'sortableFields' => $this->clientSortableFields(),
                ];
            } else {
                $this->paginate = [
                    'limit' => 300,
                    'conditions' => ['Client.id IN' => $staffIds],
                    'order' => ['Client.fname' => 'ASC'],
                    'sortableFields' => $this->clientSortableFields(),
                ];
            }
        }

        $clientsTable = $this->fetchTable('Client');
        $query = $clientsTable->find()
            ->contain(['ClientType', 'StaffClients' => ['NappUser']]);

        if (isset($this->paginate['conditions'])) {
            $query->where($this->paginate['conditions']);
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
        $this->set('client_id', '');
    }

    /**
     * @param list<int|string> $staffIds
     */
    private function applyStaffIndexPaginate(string $categoryId, string $search, array $staffIds): void
    {
        $hasCat = $categoryId !== '';
        $hasSearch = $search !== '';
        if ($staffIds === []) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => ['Client.id' => 0],
                'sortableFields' => $this->clientSortableFields(),
            ];

            return;
        }

        if ($hasCat && $hasSearch) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->mergeStaffWhere(
                    ['Client.id IN' => $staffIds, 'Client.category_id' => $categoryId],
                    $this->clientSearchOr($search),
                ),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasCat) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => [
                    'Client.id IN' => $staffIds,
                    'Client.category_id' => $categoryId,
                ],
                'sortableFields' => $this->clientSortableFields(),
            ];
        } elseif ($hasSearch) {
            $this->paginate = [
                'limit' => 300,
                'conditions' => $this->mergeStaffWhere(
                    ['Client.id IN' => $staffIds],
                    $this->clientSearchOr($search),
                ),
                'sortableFields' => $this->clientSortableFields(),
            ];
        } else {
            $this->paginate = [
                'limit' => 300,
                'conditions' => ['Client.id IN' => $staffIds],
                'order' => ['Client.fname' => 'ASC'],
                'sortableFields' => $this->clientSortableFields(),
            ];
        }
    }

    /**
     * @param array<string, mixed> $base
     * @param array<string, mixed> $orBlock
     * @return array<string, mixed>
     */
    private function mergeStaffWhere(array $base, array $orBlock): array
    {
        return array_merge($base, $orBlock);
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

    public function add(): ?Response
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Add New customer');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $clientTable = $this->fetchTable('Client');
        $client = $clientTable->newEmptyEntity();

        if ($this->getRequest()->is('post')) {
            $data = $this->clientRequestData();
            if (!isset($data['Client'])) {
                $data['Client'] = [];
            }
            $data['Client']['user_id'] = $userId;
            $this->setRequestData($data);
            if ($this->Client->save($data)) {
                $clientId = $this->Client->id;
                $this->StaffClient->save([
                    'StaffClient' => [
                        'staff_id' => $userId,
                        'client_id' => $clientId,
                    ],
                ]);
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
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Add New customer');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
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

        $clientArr = $this->Client->find('first', [
            'conditions' => ['Client.user_id' => $userId, 'Client.id' => $id],
        ]);
        if ($clientArr === []) {
            $this->Session->setFlash('Sorry, you have no permission.', 'default', ['class' => 'alert alert-danger']);

            return $this->redirect(['action' => 'index']);
        }
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
        $this->set('user_id', $userId);

        return null;
    }

    public function addComment(?string $clientId = null): void
    {
        $this->viewBuilder()->setLayout('admin_new_layout');
        $this->set('title_for_layout', SITENAME . ' Employee Comments');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $post = $this->requestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $commentText = $post['comment'] ?? $post['data']['comment'] ?? '';
            $insert = [
                'ClientComment' => [
                    'comment' => $commentText,
                    'client_id' => $clientId,
                    'emp_id' => $userId,
                    'type' => 0,
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
        $this->checkSatffSession();

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

    public function addclientdialers(): void
    {
        $this->autoRender = false;
        $this->checkSatffSession();

        $list = $this->getRequest()->getData('client_list');
        if (!is_array($list) || $list === []) {
            return;
        }

        $dialerAdapter = $this->legacyModel('DialerNumber');
        if ($dialerAdapter === null) {
            return;
        }

        $ids = array_values(array_filter(array_map('intval', $list), fn ($v) => $v > 0));
        if ($ids === []) {
            return;
        }

        $clients = $this->Client->find('all', [
            'conditions' => ['Client.id IN' => $ids],
            'fields' => ['Client.id', 'Client.mobile'],
        ]);
        if (!is_array($clients)) {
            return;
        }

        foreach ($clients as $client) {
            $cid = $client['Client']['id'] ?? null;
            $mobile = (string)($client['Client']['mobile'] ?? '');
            if ($cid === null || $mobile === '') {
                continue;
            }

            $dialRow = $dialerAdapter->find('first', [
                'conditions' => ['DialerNumber.client_id' => $cid],
                'order' => ['DialerNumber.id' => 'DESC'],
            ]);

            $shouldQueue = $dialRow === []
                || (int)($dialRow['DialerNumber']['status'] ?? 0) === 1;

            if (!$shouldQueue) {
                continue;
            }

            $normalized = str_replace(['+', ' '], '', $mobile);
            $first = substr($normalized, 0, 1);
            if ($first === '0') {
                $normalized = '61' . ltrim($normalized, '0');
            }

            $dialerAdapter->save([
                'DialerNumber' => [
                    'client_id' => $cid,
                    'phone' => $normalized,
                    'comments' => '',
                    'status' => 0,
                    'created' => date('Y-m-d H:i:s'),
                ],
            ]);
        }
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
