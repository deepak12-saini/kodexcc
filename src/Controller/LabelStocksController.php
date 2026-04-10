<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Staff label stock CRUD (legacy CakePHP 2 actions migrated to CakePHP 5).
 */
class LabelStocksController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Add New Label');
        $this->checkSatffSession();
        $userid = $this->Session->read('Customer.id');
        $name = trim((string)$this->Session->read('Customer.name') . ' ' . (string)$this->Session->read('Customer.lname'));

        if (!empty($this->requestData())) {
            $data = $this->requestData();
            $data['LabelStock']['name'] = $name;
            $data['LabelStock']['user_id'] = $userid;
            $data['LabelStock']['created'] = date('Y-m-d H:i:s');
            $this->setRequestData($data);
            if ($this->LabelStock->save($data)) {
                $this->Session->setFlash('Label added successfully.', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);
            }
        }
    }

    public function delete(?string $id = null): void
    {
        $this->autoRender = false;
        $this->checkSatffSession();
        if ($id !== null && $id !== '') {
            $this->LabelStock->id = $id;
            if ($this->LabelStock->delete()) {
                $this->Session->setFlash('Deleted successfully.', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);

                return;
            }
        }
        $this->Session->setFlash('Label could not be deleted.', 'default', ['class' => 'alert alert-danger']);
        $this->redirect(['action' => 'index']);
    }

    public function edit(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Label Stock');
        $this->checkSatffSession();
        $name = trim((string)$this->Session->read('Customer.name') . ' ' . (string)$this->Session->read('Customer.lname'));

        $LabelStock = $this->LabelStock->find('first', ['conditions' => ['LabelStock.id' => $id]]);
        if ($LabelStock === []) {
            $this->Session->setFlash('Label not found.', 'default', ['class' => 'alert alert-danger']);
            $this->redirect(['action' => 'index']);

            return;
        }

        if (!empty($this->requestData())) {
            $data = $this->requestData();
            $updatetype = (int)($data['updatetype'] ?? 0);
            $updateStock = (float)($data['LabelStock']['update_stock'] ?? 0);
            $currentInstock = (float)($LabelStock['LabelStock']['instock'] ?? 0);
            if ($updatetype === 1) {
                $data['LabelStock']['instock'] = $currentInstock + $updateStock;
            } else {
                $data['LabelStock']['instock'] = $currentInstock - $updateStock;
            }
            $data['LabelStock']['id'] = $id;
            $data['LabelStock']['name'] = $name;
            $data['LabelStock']['created'] = date('Y-m-d H:i:s');
            unset($data['LabelStock']['update_stock'], $data['updatetype']);
            $this->setRequestData($data);
            if ($this->LabelStock->save($data)) {
                $this->Session->setFlash('Label added successfully.', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);

                return;
            }
        }

        $this->set('LabelStock', $LabelStock);
        $this->setRequestData($LabelStock);
    }

    public function index(): void
    {
        $this->viewBuilder()->setLayout('staff_inner_layout');
        $this->set('title_for_layout', SITENAME . ' Label Stock List');
        $this->checkSatffSession();

        $LabelStock = $this->LabelStock->find('all');
        $this->set('LabelStock', $LabelStock);
    }
}
