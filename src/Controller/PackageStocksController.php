<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Staff package stock CRUD (legacy CakePHP 2 actions migrated to CakePHP 5).
 */
class PackageStocksController extends AppController
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

    public function add(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Add Package Stock');
        $this->checkSatffSession();
        $userid = $this->Session->read('Customer.id');
        $name = trim((string)$this->Session->read('Customer.name') . ' ' . (string)$this->Session->read('Customer.lname'));

        if (!empty($this->requestData())) {
            $data = $this->requestData();
            $data['PackageStock']['name'] = $name;
            $data['PackageStock']['user_id'] = $userid;
            $data['PackageStock']['created'] = date('Y-m-d H:i:s');
            $this->setRequestData($data);
            if ($this->PackageStock->save($data)) {
                $this->Session->setFlash('Added successfully.', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);
            }
        }
    }

    public function delete(?string $id = null): void
    {
        $this->autoRender = false;
        $this->checkSatffSession();
        if ($id !== null && $id !== '') {
            $this->PackageStock->id = $id;
            if ($this->PackageStock->delete()) {
                $this->Session->setFlash('Deleted successfully.', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);

                return;
            }
        }
        $this->Session->setFlash('Record could not be deleted.', 'default', ['class' => 'alert alert-danger']);
        $this->redirect(['action' => 'index']);
    }

    public function edit(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Package Stock');
        $this->checkSatffSession();
        $name = trim((string)$this->Session->read('Customer.name') . ' ' . (string)$this->Session->read('Customer.lname'));

        $PackageStock = $this->PackageStock->find('first', ['conditions' => ['PackageStock.id' => $id]]);
        if ($PackageStock === []) {
            $this->Session->setFlash('Package stock not found.', 'default', ['class' => 'alert alert-danger']);
            $this->redirect(['action' => 'index']);

            return;
        }

        if (!empty($this->requestData())) {
            $data = $this->requestData();
            $updatetype = (int)($data['updatetype'] ?? 0);
            $updateStock = (float)($data['PackageStock']['update_stock'] ?? 0);
            $currentInstock = (float)($PackageStock['PackageStock']['instock'] ?? 0);
            if ($updatetype === 1) {
                $data['PackageStock']['instock'] = $currentInstock + $updateStock;
            } else {
                $data['PackageStock']['instock'] = $currentInstock - $updateStock;
            }
            $data['PackageStock']['id'] = $id;
            $data['PackageStock']['name'] = $name;
            $data['PackageStock']['created'] = date('Y-m-d H:i:s');
            unset($data['PackageStock']['update_stock'], $data['updatetype']);
            $this->setRequestData($data);
            if ($this->PackageStock->save($data)) {
                $this->Session->setFlash('Added successfully.', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);

                return;
            }
        }

        $this->set('PackageStock', $PackageStock);
        $this->setRequestData($PackageStock);
    }

    public function index(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Package Stock List');
        $this->checkSatffSession();

        $PackageStock = $this->PackageStock->find('all');
        $this->set('PackageStock', $PackageStock);
    }
}
