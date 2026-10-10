<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class DepartmentsController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Departments');
        $this->set('title_for_layout', 'Departments');
        $this->hrPaginate(
            $this->fetchTable('HrDepartments')->find(),
            ['limit' => 30, 'order' => ['HrDepartments.id' => 'DESC'], 'sortableFields' => ['HrDepartments.name']]
        );
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Add Department');
        $table = $this->fetchTable('HrDepartments');
        $entity = $table->newEmptyEntity();
        if ($this->request->is('post')) {
            $entity = $table->patchEntity($entity, $this->request->getData() + [
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'status' => 1,
            ]);
            if ($table->save($entity)) {
                $this->Flash->success('Department saved.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Could not save.');
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Edit Department');
        $table = $this->fetchTable('HrDepartments');
        $entity = $table->get($id);
        if ($this->request->is(['post', 'put', 'patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData() + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $this->Flash->success('Department updated.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Could not update.');
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->requireHrRole(['admin', 'hr']);
        $id = (int)$id;
        $usedByPeople = $this->fetchTable('HrEmployees')->find()
            ->where(['department_id' => $id, 'status !=' => 'deleted'])
            ->count();
        $usedByRoles = $this->fetchTable('HrDesignations')->find()
            ->where(['department_id' => $id])
            ->count();
        if ($usedByPeople > 0 || $usedByRoles > 0) {
            $this->Flash->error('This department is still used by a designation or an employee.');

            return $this->redirect(['action' => 'index']);
        }
        $table = $this->fetchTable('HrDepartments');
        $entity = $table->get($id);
        if ($table->delete($entity)) {
            $this->Flash->success('Department deleted.');
        } else {
            $this->Flash->error('Delete failed.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
