<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class HolidaysController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Holidays');
        $this->hrPaginate(
            $this->fetchTable('HrHolidays')->find(),
            [
                'limit' => 30,
                'order' => ['HrHolidays.holiday_date' => 'ASC'],
                'sortableFields' => ['HrHolidays.holiday_date', 'HrHolidays.name'],
            ]
        );
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Add Holiday');
        $table = $this->fetchTable('HrHolidays');
        $entity = $table->newEmptyEntity();
        if ($this->request->is('post')) {
            $entity = $table->patchEntity($entity, $this->request->getData() + [
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($table->save($entity)) {
                $this->auditLog('holiday_create', 'holiday', 'Added holiday ' . $entity->name, (int)$entity->id);
                $this->Flash->success('Holiday saved.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $table = $this->fetchTable('HrHolidays');
        $entity = $table->get($id);
        $this->set('pageTitle', 'Edit Holiday');
        if ($this->request->is(['post', 'put', 'patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData() + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $this->auditLog('holiday_update', 'holiday', 'Updated holiday ' . $entity->name, (int)$entity->id);
                $this->Flash->success('Updated.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function calendar()
    {
        $this->checkHrSession();
        $this->set('pageTitle', 'Company Calendar');
        $month = (string)($this->request->getQuery('month') ?: date('Y-m'));
        $start = $month . '-01';
        $end = date('Y-m-t', strtotime($start));

        $holidays = $this->fetchTable('HrHolidays')->find()
            ->where([
                'status' => 1,
                'holiday_date >=' => $start,
                'holiday_date <=' => $end,
            ])
            ->all();

        $leaves = $this->fetchTable('HrLeaveRequests')->find()
            ->contain(['HrEmployees', 'HrLeaveTypes'])
            ->where([
                'HrLeaveRequests.status' => 'approved',
                'HrLeaveRequests.start_date <=' => $end,
                'HrLeaveRequests.end_date >=' => $start,
            ])
            ->all();

        $this->set(compact('holidays', 'leaves', 'month'));
    }
}
