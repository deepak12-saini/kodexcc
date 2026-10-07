<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

use Cake\I18n\Date;

class DashboardController extends HrmsController
{
    public function index()
    {
        $this->checkHrSession();
        $this->set('title_for_layout', 'HRMS Dashboard');
        $this->set('pageTitle', 'Dashboard');

        $role = (string)$this->Session->read('hr_role');
        if ($role === 'it') {
            return $this->redirect(['prefix' => 'Hrms/It', 'controller' => 'Dashboard', 'action' => 'index']);
        }
        $employeeId = $this->Session->read('hr_employee_id');
        $today = date('Y-m-d');
        $employees = $this->fetchTable('HrEmployees');
        // Attendance counts are disabled.
        $leaves = $this->fetchTable('HrLeaveRequests');

        if (in_array($role, ['admin', 'hr'], true)) {
            $total = $employees->find()->where(['status' => 'active'])->count();
            $onLeave = $leaves->find()->where([
                'status' => 'approved',
                'start_date <=' => $today,
                'end_date >=' => $today,
            ])->count();
            $pendingLeave = $leaves->find()->where(['status' => 'pending'])->count();

            $birthdays = $employees->find()
                ->where(['status' => 'active', 'date_of_birth IS NOT' => null])
                ->orderBy(['MONTH(date_of_birth)' => 'ASC', 'DAY(date_of_birth)' => 'ASC'])
                ->limit(8)
                ->all();

            $newJoiners = $employees->find()
                ->where(['status' => 'active', 'joining_date >=' => date('Y-m-d', strtotime('-30 days'))])
                ->orderBy(['joining_date' => 'DESC'])
                ->limit(8)
                ->all();

            $deptCounts = $this->fetchTable('HrDepartments')->find()
                ->select(['HrDepartments.name', 'cnt' => $employees->find()->func()->count('*')])
                ->leftJoinWith('HrEmployees', function ($q) {
                    return $q->where(['HrEmployees.status' => 'active']);
                })
                ->groupBy(['HrDepartments.id', 'HrDepartments.name'])
                ->enableHydration(false)
                ->all()
                ->toList();

            $this->set(compact(
                'total', 'onLeave', 'pendingLeave',
                'birthdays', 'newJoiners', 'deptCounts'
            ));
            $this->render('admin');
            return;
        }

        // Employee / Manager self dashboard
        if ($employeeId) {
            $this->ensureLeaveBalances((int)$employeeId);
            $balances = $this->fetchTable('HrLeaveBalances')->find()
                ->contain(['HrLeaveTypes'])
                ->where(['employee_id' => $employeeId, 'year' => (int)date('Y')])
                ->all();
            $myLeaves = $leaves->find()
                ->contain(['HrLeaveTypes'])
                ->where(['employee_id' => $employeeId])
            ->orderBy(['HrLeaveRequests.id' => 'DESC'])
            ->limit(5)
            ->all();
            $this->set(compact('balances', 'myLeaves'));
        }

        if ($role === 'manager' && $employeeId) {
            $teamPending = $leaves->find()
                ->contain(['HrEmployees', 'HrLeaveTypes'])
                ->where(['HrLeaveRequests.status' => 'pending', 'HrEmployees.manager_id' => $employeeId])
                ->all();
            $this->set(compact('teamPending'));
        }

        $this->render('employee');
    }
}
