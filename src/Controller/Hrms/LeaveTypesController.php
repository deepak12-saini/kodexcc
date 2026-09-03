<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class LeaveTypesController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Leave Types');
        $this->hrPaginate(
            $this->fetchTable('HrLeaveTypes')->find(),
            ['limit' => 30, 'order' => ['HrLeaveTypes.name' => 'ASC'], 'sortableFields' => ['HrLeaveTypes.name', 'HrLeaveTypes.code']]
        );
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Add Leave Type');
        $table = $this->fetchTable('HrLeaveTypes');
        $entity = $table->newEmptyEntity();
        if ($this->request->is('post')) {
            $entity = $table->patchEntity($entity, $this->request->getData() + [
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'status' => 1,
            ]);
            if ($table->save($entity)) {
                $this->Flash->success('Saved.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $table = $this->fetchTable('HrLeaveTypes');
        $entity = $table->get($id);
        $this->set('pageTitle', 'Edit Leave Type');
        if ($this->request->is(['post', 'put', 'patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData() + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $this->Flash->success('Updated.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('entity'));
        $this->render('form');
    }
}
