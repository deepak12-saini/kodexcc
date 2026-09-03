<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class AttendancesController extends HrmsController
{
    public function index()
    {
        $this->checkHrSession();
        $this->set('pageTitle', 'Attendance');
        $role = (string)$this->Session->read('hr_role');
        $date = (string)($this->request->getQuery('date') ?: date('Y-m-d'));

        $query = $this->fetchTable('HrAttendances')->find()
            ->contain(['HrEmployees' => ['HrDepartments']])
            ->where(['HrAttendances.attendance_date' => $date]);

        if ($role === 'manager') {
            $query->where(['HrEmployees.manager_id' => (int)$this->Session->read('hr_employee_id')]);
        } elseif ($role === 'employee') {
            return $this->redirect(['prefix' => 'Hrms', 'controller' => 'My', 'action' => 'attendance']);
        } else {
            $this->requireHrRole(['admin', 'hr']);
        }

        $this->hrPaginate($query, [
            'limit' => 30,
            'order' => ['HrEmployees.full_name' => 'ASC'],
            'sortableFields' => ['HrEmployees.full_name', 'HrAttendances.status', 'HrAttendances.clock_in'],
        ]);
        $this->set(compact('date'));
    }

    public function mark()
    {
        $this->requireHrRole(['admin', 'hr']);
        if ($this->request->is('post')) {
            $empId = (int)$this->request->getData('employee_id');
            $date = (string)$this->request->getData('attendance_date');
            $status = (string)$this->request->getData('status');
            $table = $this->fetchTable('HrAttendances');
            $row = $table->find()->where(['employee_id' => $empId, 'attendance_date' => $date])->first();
            if (!$row) {
                $row = $table->newEntity([
                    'employee_id' => $empId,
                    'attendance_date' => $date,
                    'created' => date('Y-m-d H:i:s'),
                ]);
            }
            $row = $table->patchEntity($row, [
                'status' => $status,
                'is_half_day' => $status === 'half_day' ? 1 : 0,
                'notes' => $this->request->getData('notes'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            $table->save($row);
            $this->Flash->success('Attendance marked.');
            return $this->redirect(['action' => 'index', '?' => ['date' => $date]]);
        }
        $employees = $this->fetchTable('HrEmployees')->find('list', [
            'keyField' => 'id',
            'valueField' => 'full_name',
        ])->where(['status' => 'active'])->toArray();
        $this->set('pageTitle', 'Mark Attendance');
        $this->set(compact('employees'));
    }

    public function approveCorrection($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $table = $this->fetchTable('HrAttendances');
        $row = $table->get($id);
        $decision = (string)$this->request->getQuery('decision');
        $row->correction_status = $decision === 'approve' ? 'approved' : 'rejected';
        if ($decision === 'approve' && $row->correction_note) {
            // keep status as set; admin can edit via mark
        }
        $row->modified = date('Y-m-d H:i:s');
        if ($decision === 'approve') {
            if (!empty($row->proposed_clock_in)) {
                $row->clock_in = $row->proposed_clock_in;
            }
            if (!empty($row->proposed_clock_out)) {
                $row->clock_out = $row->proposed_clock_out;
            }
        }
        $table->save($row);
        $this->auditLog(
            $decision === 'approve' ? 'attendance_correction_approved' : 'attendance_correction_rejected',
            'attendance',
            'Attendance correction #' . $row->id . ' ' . $row->correction_status,
            (int)$row->id,
            (int)$row->employee_id
        );
        $this->Flash->success('Correction ' . $row->correction_status . '.');
        return $this->redirect(['action' => 'index', '?' => ['date' => is_object($row->attendance_date) ? $row->attendance_date->format('Y-m-d') : $row->attendance_date]]);
    }

    public function reports()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Attendance Reports');
        $from = (string)($this->request->getQuery('from') ?: date('Y-m-01'));
        $to = (string)($this->request->getQuery('to') ?: date('Y-m-d'));
        $employeeId = $this->request->getQuery('employee_id');
        $departmentId = $this->request->getQuery('department_id');

        $query = $this->fetchTable('HrAttendances')->find()
            ->contain(['HrEmployees' => ['HrDepartments']])
            ->where([
                'HrAttendances.attendance_date >=' => $from,
                'HrAttendances.attendance_date <=' => $to,
            ]);

        if ($employeeId) {
            $query->where(['HrAttendances.employee_id' => (int)$employeeId]);
        }
        if ($departmentId) {
            $query->where(['HrEmployees.department_id' => (int)$departmentId]);
        }

        if ($this->request->getQuery('export') === 'csv') {
            $exportQuery = clone $query;
            $exportItems = $exportQuery
                ->orderBy(['HrAttendances.attendance_date' => 'DESC', 'HrEmployees.full_name' => 'ASC'])
                ->all();
            return $this->exportCsv($exportItems, $from, $to);
        }

        $this->hrPaginate($query, [
            'limit' => 30,
            'order' => ['HrAttendances.attendance_date' => 'DESC', 'HrEmployees.full_name' => 'ASC'],
            'sortableFields' => [
                'HrAttendances.attendance_date',
                'HrEmployees.full_name',
                'HrAttendances.status',
            ],
        ]);

        $employees = $this->fetchTable('HrEmployees')->find('list', [
            'keyField' => 'id',
            'valueField' => 'full_name',
        ])->where(['status' => 'active'])->toArray();
        $departments = $this->fetchTable('HrDepartments')->find('list')->where(['status' => 1])->toArray();
        $this->set(compact('from', 'to', 'employeeId', 'departmentId', 'employees', 'departments'));
    }

    private function exportCsv($items, string $from, string $to)
    {
        $this->response = $this->response->withType('csv')
            ->withDownload('attendance_' . $from . '_' . $to . '.csv');
        $out = fopen('php://temp', 'r+');
        fputcsv($out, ['Date', 'Employee', 'Code', 'Department', 'In', 'Out', 'Status', 'Late Min', 'OT Min']);
        foreach ($items as $a) {
            fputcsv($out, [
                is_object($a->attendance_date) ? $a->attendance_date->format('Y-m-d') : $a->attendance_date,
                $a->hr_employee->full_name ?? '',
                $a->hr_employee->employee_code ?? '',
                $a->hr_employee->hr_department->name ?? '',
                $a->clock_in ? (is_object($a->clock_in) ? $a->clock_in->format('H:i') : $a->clock_in) : '',
                $a->clock_out ? (is_object($a->clock_out) ? $a->clock_out->format('H:i') : $a->clock_out) : '',
                $a->status,
                $a->late_minutes,
                $a->overtime_minutes,
            ]);
        }
        rewind($out);
        $csv = stream_get_contents($out);
        fclose($out);
        return $this->response->withStringBody($csv);
    }
}
