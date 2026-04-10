<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;

/**
 * Staff production app: /productions/...
 */
class ProductionsController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Batch Register List');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $query = $this->fetchTable('BatchRegister')->find()
            ->contain(['NappUser'])
            ->where(['BatchRegister.user_id' => $userId]);

        $this->paginate = [
            'limit' => 10,
            'order' => ['BatchRegister.id' => 'DESC'],
            'sortableFields' => [
                'BatchRegister.id',
                'NappUser.name',
                'BatchRegister.batch_no',
                'BatchRegister.date',
                'BatchRegister.product',
                'BatchRegister.apearance',
                'BatchRegister.viscosity',
                'BatchRegister.t_degree_c',
                'BatchRegister.s_g',
                'BatchRegister.check_test',
                'BatchRegister.test_by',
                'BatchRegister.comments',
                'BatchRegister.created',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('batchRegisterStaffPaginated', $page);

        $BatchRegisterArr = [];
        foreach ($page as $e) {
            if (!$e instanceof EntityInterface) {
                continue;
            }
            $a = $e->toArray();
            $nu = $a['napp_user'] ?? [];
            unset($a['napp_user']);
            $BatchRegisterArr[] = ['BatchRegister' => $a, 'NappUser' => $nu];
        }
        $this->set('BatchRegisterArr', $BatchRegisterArr);
    }

    public function batchRegisterAdd(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Batch Register List');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $post = $this->requestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $br = $post['BatchRegister'] ?? [];
            if (is_array($br)) {
                $br['user_id'] = $userId;
                $br['created'] = date('Y-m-d H:i:s');
                if ($this->BatchRegister->save(['BatchRegister' => $br])) {
                    $this->Session->setFlash('Batch register added successfully.', 'default', ['class' => 'alert alert-success']);
                    $this->redirect(['action' => 'index']);

                    return;
                }
            }
        }
    }

    public function batchRegisterEdit(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Batch Register List');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $post = $this->requestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $br = $post['BatchRegister'] ?? [];
            if (is_array($br)) {
                $br['user_id'] = $userId;
                $br['id'] = $id;
                if ($this->BatchRegister->save(['BatchRegister' => $br])) {
                    $this->Session->setFlash('Batch register added successfully.', 'default', ['class' => 'alert alert-success']);
                    $this->redirect(['action' => 'index']);

                    return;
                }
            }
        }

        $BatchRegisterArr = $this->BatchRegister->find('first', [
            'conditions' => ['BatchRegister.id' => $id, 'BatchRegister.user_id' => $userId],
        ]);
        if ($BatchRegisterArr === []) {
            throw new NotFoundException(__('Batch register not found.'));
        }
        $this->set('BatchRegisterArr', $BatchRegisterArr);
    }

    public function batchCountSheet(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Batch Count Sheet List');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $query = $this->fetchTable('BatchCountSheet')->find()
            ->contain(['NappUser'])
            ->where(['BatchCountSheet.user_id' => $userId]);

        $this->paginate = [
            'limit' => 10,
            'order' => ['BatchCountSheet.id' => 'DESC'],
            'sortableFields' => [
                'BatchCountSheet.id',
                'NappUser.name',
                'BatchCountSheet.employee_name',
                'BatchCountSheet.batch_number',
                'BatchCountSheet.product_name',
                'BatchCountSheet.quantity',
                'BatchCountSheet.no_of_pails',
                'BatchCountSheet.date',
                'BatchCountSheet.date_completed',
                'BatchCountSheet.signature',
                'BatchCountSheet.created',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('batchCountSheetStaffPaginated', $page);

        $BatchRegisterArr = [];
        foreach ($page as $e) {
            if (!$e instanceof EntityInterface) {
                continue;
            }
            $a = $e->toArray();
            $nu = $a['napp_user'] ?? [];
            unset($a['napp_user']);
            $BatchRegisterArr[] = ['BatchCountSheet' => $a, 'NappUser' => $nu];
        }
        $this->set('BatchRegisterArr', $BatchRegisterArr);
    }

    public function batchCountSheetAdd(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Batch Count Sheet List');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $post = $this->requestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $row = $post['BatchCountSheet'] ?? [];
            if (is_array($row)) {
                $row['user_id'] = $userId;
                $row['created'] = date('Y-m-d H:i:s');
                if ($this->BatchCountSheet->save(['BatchCountSheet' => $row])) {
                    $this->Session->setFlash('Batch added successfully.', 'default', ['class' => 'alert alert-success']);
                    $this->redirect(['action' => 'batchCountSheet']);

                    return;
                }
            }
        }
    }

    public function batchCountSheetEdit(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Batch Count Sheet List');
        $this->checkSatffSession();

        $userId = $this->Session->read('Customer.id');
        $post = $this->requestData();
        if ($this->getRequest()->is('post') && $post !== []) {
            $row = $post['BatchCountSheet'] ?? [];
            if (is_array($row)) {
                $row['id'] = $id;
                $row['user_id'] = $userId;
                if ($this->BatchCountSheet->save(['BatchCountSheet' => $row])) {
                    $this->Session->setFlash('Batch added successfully.', 'default', ['class' => 'alert alert-success']);
                    $this->redirect(['action' => 'batchCountSheet']);

                    return;
                }
            }
        }

        $BatchCountSheetArr = $this->BatchCountSheet->find('first', [
            'conditions' => ['BatchCountSheet.id' => $id, 'BatchCountSheet.user_id' => $userId],
        ]);
        if ($BatchCountSheetArr === []) {
            throw new NotFoundException(__('Batch count sheet not found.'));
        }
        $this->set('BatchCountSheetArr', $BatchCountSheetArr);
    }
}
