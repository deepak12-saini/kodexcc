<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Admin materials: /admin/materials/...
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
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Material ');
        $this->checkAdminSession();

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
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Material Order Request');
        $this->checkAdminSession();

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
        $this->checkAdminSession();
        $adminId = $this->Session->read('User.id');
        $update = [
            'MaterialOrder' => [
                'id' => $orderid,
                'admin_id' => $adminId,
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

    public function add(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add New Material Type');
        $this->checkAdminSession();

        if ($this->getRequest()->is('post')) {
            $data = $this->requestData();
            if (!isset($data['Material']) || !is_array($data['Material'])) {
                $data['Material'] = [];
            }
            $data['Material']['created'] = date('Y-m-d H:i:s');
            $this->setRequestData($data);
            if ($this->Material->save($data)) {
                $this->Session->setFlash('Material Added successfully', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);
                return;
            }
        }
    }

    public function edit($id = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Material');
        $this->checkAdminSession();

        $data = $this->requestData();
        if ($data !== [] && isset($data['Material'])) {
            $data['Material']['id'] = $id;
            $this->setRequestData($data);
            if ($this->Material->save($data)) {
                $this->Session->setFlash('Material updated successfully', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);
                return;
            }
        }

        $materialArr = $this->Material->find('first', ['conditions' => ['Material.id' => $id]]);
        $this->set('MaterialArr', $materialArr);
        if (!empty($materialArr)) {
            $this->setRequestData($materialArr);
        }
    }

    public function autologin($id = null): void
    {
        $this->autoRender = false;
        $admin_arr = $this->User->find('first', ['conditions' => ['User.id' => $id]]);
        if (!empty($admin_arr)) {
            $insert = [
                'LoginHistory' => [
                    'admin_id' => $admin_arr['User']['id'],
                    'role' => 'Admin',
                    'logintime' => date('Y-m-d H:i:s'),
                ],
            ];
            $this->LoginHistory->save($insert);

            $this->Session->write('User', $admin_arr['User']);
            $this->Session->write('is_admin', 1);
            $this->redirect(['action' => 'order']);
        } else {
            $this->Session->setFlash('Sorry you cannot access this portal', 'default', ['class' => 'alert alert-danger']);
            $this->redirect('/admin');
        }
    }
}
