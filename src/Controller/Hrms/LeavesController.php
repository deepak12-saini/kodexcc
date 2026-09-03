<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class LeavesController extends HrmsController
{
    public function index()
    {
        $this->checkHrSession();
        $this->set('pageTitle', 'Leave Requests');
        $role = (string)$this->Session->read('hr_role');
        $status = (string)($this->request->getQuery('status') ?: '');

        $query = $this->fetchTable('HrLeaveRequests')->find()
            ->contain(['HrEmployees', 'HrLeaveTypes']);

        if ($role === 'manager') {
            $query->where(['HrEmployees.manager_id' => (int)$this->Session->read('hr_employee_id')]);
        } elseif ($role === 'employee') {
            return $this->redirect(['prefix' => 'Hrms', 'controller' => 'My', 'action' => 'leaves']);
        } else {
            $this->requireHrRole(['admin', 'hr']);
        }
        if ($status !== '') {
            $query->where(['HrLeaveRequests.status' => $status]);
        }
        $this->hrPaginate($query, [
            'order' => ['HrLeaveRequests.id' => 'DESC'],
            'sortableFields' => ['HrLeaveRequests.id', 'HrLeaveRequests.status', 'HrLeaveRequests.start_date'],
        ]);
        $this->set(compact('status'));
    }

    public function view($id = null)
    {
        $this->checkHrSession();
        $item = $this->fetchTable('HrLeaveRequests')->get($id, contain: ['HrEmployees', 'HrLeaveTypes']);
        $role = (string)$this->Session->read('hr_role');
        $empId = (int)$this->Session->read('hr_employee_id');

        if ($role === 'manager' && (int)$item->hr_employee->manager_id !== $empId) {
            return $this->redirectDenied();
        }
        if ($role === 'employee') {
            return $this->redirectDenied();
        }

        if ($this->request->is(['post', 'put'])) {
            $decision = (string)$this->request->getData('decision');
            $remark = (string)$this->request->getData('remark');
            $table = $this->fetchTable('HrLeaveRequests');

            if ($role === 'manager') {
                $item->manager_status = $decision;
                $item->manager_id = $empId;
                $item->manager_remark = $remark;
                if ($decision === 'rejected') {
                    $item->status = 'rejected';
                } else {
                    $item->status = 'pending_hr';
                }
            } else {
                $item->hr_status = $decision;
                $item->hr_remark = $remark;
                $item->approved_by = (int)$this->Session->read('HrUser.id');
                $item->status = $decision === 'approved' ? 'approved' : 'rejected';
                if ($decision === 'approved') {
                    $this->applyLeaveUsage($item);
                }
            }
            $item->modified = date('Y-m-d H:i:s');
            $table->save($item);
            $this->auditLog(
                'leave_' . $decision,
                'leave',
                'Leave #' . $item->id . ' ' . $item->status . ' by ' . $role,
                (int)$item->id,
                (int)$item->employee_id,
                null,
                ['status' => $item->status, 'decision' => $decision]
            );
            $this->Flash->success('Leave updated.');
            return $this->redirect(['action' => 'index']);
        }

        $this->set('pageTitle', 'Leave Request #' . $id);
        $this->set(compact('item'));
    }

    public function calendar()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Leave Calendar');
        $month = (string)($this->request->getQuery('month') ?: date('Y-m'));
        $start = $month . '-01';
        $end = date('Y-m-t', strtotime($start));
        $items = $this->fetchTable('HrLeaveRequests')->find()
            ->contain(['HrEmployees', 'HrLeaveTypes'])
            ->where([
                'HrLeaveRequests.status' => 'approved',
                'HrLeaveRequests.start_date <=' => $end,
                'HrLeaveRequests.end_date >=' => $start,
            ])
            ->all();
        $this->set(compact('items', 'month'));
    }

    private function applyLeaveUsage($item): void
    {
        $year = (int)date('Y', strtotime((string)$item->start_date));
        $this->ensureLeaveBalances((int)$item->employee_id, $year);
        $balances = $this->fetchTable('HrLeaveBalances');
        $bal = $balances->find()->where([
            'employee_id' => $item->employee_id,
            'leave_type_id' => $item->leave_type_id,
            'year' => $year,
        ])->first();
        if ($bal) {
            $bal->used = (float)$bal->used + (float)$item->days;
            $bal->modified = date('Y-m-d H:i:s');
            $balances->save($bal);
        }
    }
}
