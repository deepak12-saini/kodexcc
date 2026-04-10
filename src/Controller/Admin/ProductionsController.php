<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

/**
 * Admin production batch lists: /admin/productions/...
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
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Batch Register List');
        $this->checkAdminSession();

        $query = $this->fetchTable('BatchRegister')->find()
            ->contain(['NappUser']);

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
        $this->set('batchRegisterPaginated', $page);

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

    public function batchCountSheet(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Batch Count Sheet List');
        $this->checkAdminSession();

        $query = $this->fetchTable('BatchCountSheet')->find()
            ->contain(['NappUser']);

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
        $this->set('batchCountSheetPaginated', $page);

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
}
