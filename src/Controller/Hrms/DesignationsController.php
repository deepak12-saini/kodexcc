<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class DesignationsController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Designations');
        $this->hrPaginate(
            $this->fetchTable('HrDesignations')->find()->contain(['HrDepartments']),
            ['limit' => 30, 'order' => ['HrDesignations.name' => 'ASC'], 'sortableFields' => ['HrDesignations.name']]
        );
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Add Designation');
        $table = $this->fetchTable('HrDesignations');
        $entity = $table->newEmptyEntity();
        $departments = $this->fetchTable('HrDepartments')->find('list')->where(['status' => 1])->toArray();
        if ($this->request->is('post')) {
            $entity = $table->patchEntity($entity, $this->request->getData() + [
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'status' => 1,
            ]);
            if ($table->save($entity)) {
                $this->Flash->success('Designation saved.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Could not save.');
        }
        $this->set(compact('entity', 'departments'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Edit Designation');
        $table = $this->fetchTable('HrDesignations');
        $entity = $table->get($id);
        $departments = $this->fetchTable('HrDepartments')->find('list')->where(['status' => 1])->toArray();
        if ($this->request->is(['post', 'put', 'patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData() + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $this->Flash->success('Updated.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Could not update.');
        }
        $this->set(compact('entity', 'departments'));
        $this->render('form');
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->requireHrRole(['admin', 'hr']);
        $table = $this->fetchTable('HrDesignations');
        if ($table->delete($table->get($id))) {
            $this->Flash->success('Deleted.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
