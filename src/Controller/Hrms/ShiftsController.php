<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class ShiftsController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Shifts');
        $this->hrPaginate(
            $this->fetchTable('HrShifts')->find(),
            ['limit' => 30, 'order' => ['HrShifts.start_time' => 'ASC'], 'sortableFields' => ['HrShifts.name', 'HrShifts.start_time']]
        );
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Add Shift');
        $table = $this->fetchTable('HrShifts');
        $entity = $table->newEmptyEntity();
        if ($this->request->is('post')) {
            $entity = $table->patchEntity($entity, $this->request->getData() + [
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
                'status' => 1,
            ]);
            if ($table->save($entity)) {
                $this->Flash->success('Shift saved.');
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
        $this->set('pageTitle', 'Edit Shift');
        $table = $this->fetchTable('HrShifts');
        $entity = $table->get($id);
        if ($this->request->is(['post', 'put', 'patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData() + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $this->Flash->success('Updated.');
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
        $this->fetchTable('HrShifts')->delete($this->fetchTable('HrShifts')->get($id));
        $this->Flash->success('Deleted.');
        return $this->redirect(['action' => 'index']);
    }
}
