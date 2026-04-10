<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Staff / public material catalog and orders: /materials/...
 */
class MaterialsController extends AppController
{
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

    public function index(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Material ');
        $this->checkSatffSession();

        $table = $this->fetchTable('Material');
        $query = $table->find()->orderDesc($table->aliasField('id'));
        $this->paginate = [
            'limit' => 25,
            'order' => ['Material.id' => 'DESC'],
            'sortableFields' => [
                'Material.id',
                'Material.material_type',
                'Material.weight',
                'Material.quantity',
                'Material.description',
                'Material.created',
            ],
        ];
        $paged = $this->paginate($query);
        $materials = [];
        foreach ($paged as $entity) {
            $materials[] = ['Material' => $entity->toArray()];
        }
        $this->set('materials', $materials);
        $this->set('materialsPaginated', $paged);
    }

    public function order(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Material Order Request');
        $this->checkSatffSession();

        $search = '';
        $orderTable = $this->fetchTable('MaterialOrder');
        $query = $orderTable->find()->orderDesc($orderTable->aliasField('id'));
        if ($this->getRequest()->is('post')) {
            $search = trim((string)($this->requestData()['search'] ?? ''));
            if ($search !== '') {
                $query->where(['MaterialOrder.order_id' => $search]);
            }
        }

        $this->paginate = [
            'limit' => 25,
            'order' => ['MaterialOrder.id' => 'DESC'],
            'sortableFields' => [
                'MaterialOrder.order_id',
                'MaterialOrder.material_type',
                'MaterialOrder.weight',
                'MaterialOrder.quantity',
                'MaterialOrder.name',
                'MaterialOrder.status',
                'MaterialOrder.lastmodification',
                'MaterialOrder.created',
            ],
        ];
        $paged = $this->paginate($query);
        $rows = [];
        foreach ($paged as $entity) {
            $rows[] = ['MaterialOrder' => $entity->toArray()];
        }
        $this->set('MaterialOrder', $rows);
        $this->set('materialOrderPaginated', $paged);
        $this->set('search', $search);
    }

    public function status($status = null, $orderid = null): void
    {
        $this->autoRender = false;
        $this->checkSatffSession();
        $userId = $this->Session->read('Customer.id');
        $update = [
            'MaterialOrder' => [
                'id' => $orderid,
                'user_id' => $userId,
                'status' => $status,
                'lastmodification' => date('Y-m-d H:i:s'),
            ],
        ];
        if ($this->MaterialOrder->save($update)) {
            if ((int)$status === 1) {
                $this->Session->setFlash('Accepted successfully.', 'default', ['class' => 'alert alert-success']);
            } else {
                $this->Session->setFlash('Completed successfully.', 'default', ['class' => 'alert alert-success']);
            }
            $this->redirect(['action' => 'order']);
        }
    }

    public function readqrcode($qrcode = null): void
    {
        $this->viewBuilder()->setLayout('admin_login');
        $this->set('title_for_layout', SITENAME . ' Place order');

        $wrapped = $this->Material->find('first', ['conditions' => ['Material.id' => $qrcode]]);
        if (empty($wrapped)) {
            $this->redirect('/');
            return;
        }

        $post = $this->requestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $mo = $this->MaterialOrder->find('first', ['order' => ['MaterialOrder.id' => 'DESC'], 'limit' => 1]);
            if (!empty($mo['MaterialOrder']['id'])) {
                $nextId = (int)$mo['MaterialOrder']['id'] + 1;
            } else {
                $nextId = 1;
            }
            $strlen = strlen((string)$nextId);
            if ($strlen === 1) {
                $orderid = 'POR00' . $nextId;
            } elseif ($strlen === 2) {
                $orderid = 'POR0' . $nextId;
            } else {
                $orderid = 'POR' . $nextId;
            }

            $insert = [
                'MaterialOrder' => [
                    'order_id' => $orderid,
                    'material_id' => $post['id'] ?? $wrapped['Material']['id'] ?? null,
                    'material_type' => $post['material_type'] ?? '',
                    'weight' => $post['weight'] ?? '',
                    'quantity' => $post['quantity'] ?? '',
                    'name' => $post['name'] ?? '',
                    'created' => date('Y-m-d H:i:s'),
                    'lastmodification' => date('Y-m-d H:i:s'),
                ],
            ];
            $this->MaterialOrder->save($insert);

            $tomail = 'kal@durotechindustries.com.au';
            $toname = 'Kal';
            $orderidVar = $orderid;
            $subject = SITENAME . ':- New Material Order Request (#' . $orderidVar . ')';
            $template_name = 'martialorder';
            $adminurl = SITEURL . 'admin/materials/autologin/2/';
            $variables = [
                'toname' => $toname,
                'orderid' => $orderidVar,
                'material_type' => $post['material_type'] ?? '',
                'weight' => $post['weight'] ?? '',
                'quantity' => $post['quantity'] ?? '',
                'requestedname' => $post['name'] ?? '',
                'url' => $adminurl,
            ];
            try {
                $this->sendemail($tomail, $subject, $template_name, $variables);
            } catch (\Exception $e) {
            }

            $permTable = $this->fetchTable('UserPermission');
            $permRows = $permTable->find()
                ->select(['user_id'])
                ->where(['UserPermission.meta_val' => 'material'])
                ->enableHydration(false)
                ->all();
            $ids = [];
            foreach ($permRows as $pr) {
                $uid = $pr['user_id'] ?? null;
                if ($uid !== null && $uid !== '') {
                    $ids[] = (int)$uid;
                }
            }
            $ids = array_values(array_unique($ids));
            if ($ids !== []) {
                $napRows = $this->fetchTable('NappUser')->find()
                    ->where(['NappUser.id IN' => $ids])
                    ->enableHydration(false)
                    ->all();
                foreach ($napRows as $nr) {
                    $napusers = ['NappUser' => $nr];
                    $tomailS = $napusers['NappUser']['email'] ?? '';
                    $tonameS = trim(($napusers['NappUser']['name'] ?? '') . ' ' . ($napusers['NappUser']['lname'] ?? ''));
                    $subjectS = SITENAME . ':- New Material Order Request (#' . $orderidVar . ')';
                    $userIdEnc = base64_encode(base64_encode((string)($napusers['NappUser']['id'] ?? '')));
                    $adminurlS = SITEURL . 'materials/autologin/' . $userIdEnc;
                    $variablesS = [
                        'toname' => $tonameS,
                        'orderid' => $orderidVar,
                        'material_type' => $post['material_type'] ?? '',
                        'weight' => $post['weight'] ?? '',
                        'quantity' => $post['quantity'] ?? '',
                        'requestedname' => $post['name'] ?? '',
                        'url' => $adminurlS,
                    ];
                    try {
                        // $this->sendemail($tomailS, $subjectS, $template_name, $variablesS);
                    } catch (\Exception $e) {
                    }
                }
            }
            $this->redirect(['action' => 'success']);
            return;
        }

        $this->set('MaterialArr', $wrapped);
    }

    public function success(): void
    {
        $this->viewBuilder()->setLayout('admin_login');
        $this->set('title_for_layout', SITENAME . ' Success Page');
    }

    public function autologin($id = null): void
    {
        $this->autoRender = false;
        $userId = base64_decode(base64_decode((string)$id));
        $user_arr = $this->NappUser->find('first', ['conditions' => ['NappUser.id' => $userId]]);

        if (!empty($user_arr)) {
            if ((int)($user_arr['NappUser']['is_staff_id'] ?? 0) === 1) {
                $insert = [
                    'LoginHistory' => [
                        'user_id' => $user_arr['NappUser']['id'],
                        'role' => 'Staff',
                        'logintime' => date('Y-m-d H:i:s'),
                    ],
                ];
                $this->LoginHistory->save($insert);

                $this->Session->write('Customer', $user_arr['NappUser']);
                $this->Session->write('is_staff', 1);
                $this->redirect(['action' => 'order']);
            } else {
                $this->Session->setFlash('Sorry you cannot access this portal', 'default', ['class' => 'alert alert-danger']);
                $this->redirect('/login');
            }
        } else {
            $this->Session->setFlash('Sorry you cannot access this portal', 'default', ['class' => 'alert alert-danger']);
            $this->redirect('/login');
        }
    }
}
