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
        $employeeId = $this->Session->read('hr_employee_id');
        $today = date('Y-m-d');
        $employees = $this->fetchTable('HrEmployees');
        $attendances = $this->fetchTable('HrAttendances');
        $leaves = $this->fetchTable('HrLeaveRequests');

        if (in_array($role, ['admin', 'hr'], true)) {
            $total = $employees->find()->where(['status' => 'active'])->count();
            $present = $attendances->find()->where(['attendance_date' => $today, 'status IN' => ['present', 'late', 'half_day']])->count();
            $absent = $attendances->find()->where(['attendance_date' => $today, 'status' => 'absent'])->count();
            $onLeave = $leaves->find()->where([
                'status' => 'approved',
                'start_date <=' => $today,
                'end_date >=' => $today,
            ])->count();
            $late = $attendances->find()->where(['attendance_date' => $today, 'status' => 'late'])->count();
            $pendingLeave = $leaves->find()->where(['status' => 'pending'])->count();
            $pendingCorrections = $attendances->find()->where(['correction_status' => 'pending'])->count();

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

            $monthStart = date('Y-m-01');
            $chartLabels = [];
            $chartPresent = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = date('Y-m-d', strtotime("-{$i} days"));
                $chartLabels[] = date('d M', strtotime($d));
                $chartPresent[] = $attendances->find()->where([
                    'attendance_date' => $d,
                    'status IN' => ['present', 'late', 'half_day'],
                ])->count();
            }

            $monthlySummary = [
                'present' => $attendances->find()->where(['attendance_date >=' => $monthStart, 'status IN' => ['present', 'late']])->count(),
                'absent' => $attendances->find()->where(['attendance_date >=' => $monthStart, 'status' => 'absent'])->count(),
                'half_day' => $attendances->find()->where(['attendance_date >=' => $monthStart, 'status' => 'half_day'])->count(),
                'late' => $attendances->find()->where(['attendance_date >=' => $monthStart, 'status' => 'late'])->count(),
            ];

            $this->set(compact(
                'total', 'present', 'absent', 'onLeave', 'late', 'pendingLeave', 'pendingCorrections',
                'birthdays', 'newJoiners', 'deptCounts', 'chartLabels', 'chartPresent', 'monthlySummary'
            ));
            $this->render('admin');
            return;
        }

        // Employee / Manager self dashboard
        if ($employeeId) {
            $todayAtt = $attendances->find()->where([
                'employee_id' => $employeeId,
                'attendance_date' => $today,
            ])->first();
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
            $this->set(compact('todayAtt', 'balances', 'myLeaves'));
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
