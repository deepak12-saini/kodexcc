<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

use Cake\Routing\Router;

class MyController extends HrmsController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->checkHrSession();
        if (!(int)$this->Session->read('hr_employee_id')) {
            // Admin without employee profile can still change password etc., but not ESS
            if (!in_array((string)$this->Session->read('hr_role'), ['admin'], true)) {
                $this->Flash->error('No employee profile linked to this login.');
                $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
                return;
            }
        }
    }

    public function attendance()
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', 'My Attendance');
        $today = date('Y-m-d');
        $table = $this->fetchTable('HrAttendances');
        $todayRow = $table->find()->where(['employee_id' => $empId, 'attendance_date' => $today])->first();

        if ($this->request->is('post')) {
            $action = (string)$this->request->getData('action');
            $employee = $this->fetchTable('HrEmployees')->get($empId, contain: ['HrShifts']);
            $now = date('Y-m-d H:i:s');

            if ($action === 'clock_in') {
                if ($todayRow && $todayRow->clock_in) {
                    $this->Flash->error('Already clocked in today.');
                } else {
                    if (!$todayRow) {
                        $todayRow = $table->newEntity([
                            'employee_id' => $empId,
                            'attendance_date' => $today,
                            'created' => $now,
                        ]);
                    }
                    $status = 'present';
                    $late = 0;
                    if (!empty($employee->hr_shift)) {
                        $start = $today . ' ' . (is_object($employee->hr_shift->start_time) ? $employee->hr_shift->start_time->format('H:i:s') : $employee->hr_shift->start_time);
                        $grace = (int)$employee->hr_shift->grace_minutes;
                        $deadline = strtotime($start) + ($grace * 60);
                        if (time() > $deadline) {
                            $status = 'late';
                            $late = (int)ceil((time() - strtotime($start)) / 60);
                        }
                    }
                    $todayRow = $table->patchEntity($todayRow, [
                        'clock_in' => $now,
                        'status' => $status,
                        'late_minutes' => $late,
                        'modified' => $now,
                    ]);
                    $table->save($todayRow);
                    $this->Flash->success('Clocked in (' . $status . ').');
                }
            } elseif ($action === 'clock_out') {
                if (!$todayRow || !$todayRow->clock_in) {
                    $this->Flash->error('Clock in first.');
                } elseif ($todayRow->clock_out) {
                    $this->Flash->error('Already clocked out.');
                } else {
                    $early = 0;
                    $ot = 0;
                    if (!empty($employee->hr_shift)) {
                        $end = $today . ' ' . (is_object($employee->hr_shift->end_time) ? $employee->hr_shift->end_time->format('H:i:s') : $employee->hr_shift->end_time);
                        if (time() < strtotime($end) - 30 * 60) {
                            $early = (int)ceil((strtotime($end) - time()) / 60);
                            if ($todayRow->status !== 'half_day') {
                                $todayRow->status = 'early_leave';
                            }
                        } elseif (time() > strtotime($end) + 30 * 60) {
                            $ot = (int)ceil((time() - strtotime($end)) / 60);
                        }
                    }
                    $todayRow = $table->patchEntity($todayRow, [
                        'clock_out' => $now,
                        'early_leave_minutes' => $early,
                        'overtime_minutes' => $ot,
                        'modified' => $now,
                    ]);
                    $table->save($todayRow);
                    $this->Flash->success('Clocked out.');
                }
            } elseif ($action === 'correction') {
                $corrDate = (string)($this->request->getData('correction_date') ?: $today);
                $note = trim((string)$this->request->getData('correction_note'));
                $propIn = (string)$this->request->getData('proposed_clock_in');
                $propOut = (string)$this->request->getData('proposed_clock_out');
                if ($note === '') {
                    $this->Flash->error('Please describe the correction.');
                } else {
                    $row = $table->find()->where(['employee_id' => $empId, 'attendance_date' => $corrDate])->first();
                    if (!$row) {
                        $row = $table->newEntity([
                            'employee_id' => $empId,
                            'attendance_date' => $corrDate,
                            'status' => 'present',
                            'created' => $now,
                        ]);
                    }
                    $patch = [
                        'correction_note' => $note,
                        'correction_status' => 'pending',
                        'modified' => $now,
                    ];
                    if ($propIn !== '') {
                        $patch['proposed_clock_in'] = $corrDate . ' ' . (strlen($propIn) === 5 ? $propIn . ':00' : $propIn);
                    }
                    if ($propOut !== '') {
                        $patch['proposed_clock_out'] = $corrDate . ' ' . (strlen($propOut) === 5 ? $propOut . ':00' : $propOut);
                    }
                    $row = $table->patchEntity($row, $patch);
                    $table->save($row);
                    $this->auditLog(
                        'attendance_correction_submit',
                        'attendance',
                        'Correction requested for ' . $corrDate,
                        (int)$row->id,
                        $empId
                    );
                    $this->Flash->success('Correction submitted for approval.');
                }
            }
            return $this->redirect(['action' => 'attendance']);
        }

        $employee = $this->fetchTable('HrEmployees')->get($empId, contain: ['HrShifts']);
        $this->paginate = [
            'limit' => 20,
            'maxLimit' => 100,
            'order' => ['HrAttendances.attendance_date' => 'DESC'],
            'sortableFields' => ['HrAttendances.attendance_date', 'HrAttendances.status'],
        ];
        $history = $this->paginate($table->find()->where(['employee_id' => $empId]));
        $this->set(compact('todayRow', 'history', 'employee'));
    }

    public function leaves()
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', 'My Leaves');
        $this->ensureLeaveBalances($empId);
        $types = $this->fetchTable('HrLeaveTypes')->find()->where(['status' => 1])->all();
        $balances = $this->fetchTable('HrLeaveBalances')->find()
            ->contain(['HrLeaveTypes'])
            ->where(['employee_id' => $empId, 'year' => (int)date('Y')])
            ->all();

        if ($this->request->is('post')) {
            $start = (string)$this->request->getData('start_date');
            $end = (string)$this->request->getData('end_date');
            $duration = (string)($this->request->getData('duration_type') ?: 'full_day');
            $halfSession = (string)$this->request->getData('half_day_session');
            $startTime = (string)$this->request->getData('start_time');
            $endTime = (string)$this->request->getData('end_time');

            if ($start === '' || $end === '') {
                $this->Flash->error('Please select start and end dates.');
                return $this->redirect(['action' => 'leaves']);
            }
            if (strtotime($end) < strtotime($start)) {
                $this->Flash->error('End date cannot be before start date.');
                return $this->redirect(['action' => 'leaves']);
            }

            $daySpan = max(1, (int)((strtotime($end) - strtotime($start)) / 86400) + 1);
            $days = (float)$daySpan;
            $startTimeVal = null;
            $endTimeVal = null;
            $halfVal = null;

            if ($duration === 'half_day') {
                $days = 0.5;
                $end = $start; // half day is same-day
                $halfVal = in_array($halfSession, ['first_half', 'second_half'], true)
                    ? $halfSession
                    : 'first_half';
                // Default times aligned to General shift halves
                if ($halfVal === 'first_half') {
                    $startTimeVal = '09:00:00';
                    $endTimeVal = '13:00:00';
                } else {
                    $startTimeVal = '14:00:00';
                    $endTimeVal = '18:00:00';
                }
            } elseif ($duration === 'hourly') {
                $end = $start;
                if ($startTime === '' || $endTime === '') {
                    $this->Flash->error('Please enter start and end time for hourly leave.');
                    return $this->redirect(['action' => 'leaves']);
                }
                $startTimeVal = strlen($startTime) === 5 ? $startTime . ':00' : $startTime;
                $endTimeVal = strlen($endTime) === 5 ? $endTime . ':00' : $endTime;
                $mins = max(0, (strtotime($start . ' ' . $startTimeVal) - strtotime($start . ' ' . $endTimeVal)) * -1) / 60;
                if ($mins <= 0) {
                    $this->Flash->error('End time must be after start time.');
                    return $this->redirect(['action' => 'leaves']);
                }
                // Store as fraction of an 8-hour day (min 0.5 hour = 0.1 day rounded to 1 decimal)
                $days = max(0.1, round($mins / (8 * 60), 1));
            } else {
                $duration = 'full_day';
            }

            $entity = $this->fetchTable('HrLeaveRequests')->newEntity([
                'employee_id' => $empId,
                'leave_type_id' => (int)$this->request->getData('leave_type_id'),
                'start_date' => $start,
                'end_date' => $end,
                'days' => $days,
                'duration_type' => $duration,
                'half_day_session' => $halfVal,
                'start_time' => $startTimeVal,
                'end_time' => $endTimeVal,
                'reason' => (string)$this->request->getData('reason'),
                'status' => 'pending',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($this->fetchTable('HrLeaveRequests')->save($entity)) {
                $this->Flash->success('Leave applied.');
                return $this->redirect(['action' => 'leaves']);
            }
            $this->Flash->error('Could not apply leave.');
        }
        $this->hrPaginate(
            $this->fetchTable('HrLeaveRequests')->find()
                ->contain(['HrLeaveTypes'])
                ->where(['employee_id' => $empId]),
            [
                'order' => ['HrLeaveRequests.id' => 'DESC'],
                'sortableFields' => ['HrLeaveRequests.id', 'HrLeaveRequests.status', 'HrLeaveRequests.start_date'],
            ]
        );
        $this->set(compact('types', 'balances'));
    }

    public function documents()
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', 'My Documents');
        $this->hrPaginate(
            $this->fetchTable('HrDocuments')->find()->where(['employee_id' => $empId]),
            ['order' => ['HrDocuments.id' => 'DESC'], 'sortableFields' => ['HrDocuments.id', 'HrDocuments.created']]
        );
    }

    public function assets()
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', 'My Assets');
        $this->hrPaginate(
            $this->fetchTable('HrAssetAssignments')->find()
                ->contain(['HrAssets'])
                ->where(['employee_id' => $empId]),
            ['order' => ['HrAssetAssignments.id' => 'DESC'], 'sortableFields' => ['HrAssetAssignments.id', 'HrAssetAssignments.issue_date']]
        );
    }

    public function profile()
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', 'My Profile');
        $employee = $this->fetchTable('HrEmployees')->get($empId, contain: [
            'HrDepartments', 'HrDesignations', 'HrShifts', 'Managers',
        ]);
        if ($this->request->is(['post', 'put'])) {
            // limited self-edit
            $employee = $this->fetchTable('HrEmployees')->patchEntity($employee, [
                'mobile' => $this->request->getData('mobile'),
                'address' => $this->request->getData('address'),
                'emergency_contact_name' => $this->request->getData('emergency_contact_name'),
                'emergency_contact_phone' => $this->request->getData('emergency_contact_phone'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($this->fetchTable('HrEmployees')->save($employee)) {
                $this->auditLog('profile_update', 'employee', 'Self-updated profile', $empId, $empId);
                $this->Flash->success('Profile updated.');
                return $this->redirect(['action' => 'profile']);
            }
        }
        $this->set(compact('employee'));
    }

    public function requests()
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', 'My Requests');

        $rows = [];
        foreach ($this->fetchTable('HrRequests')->find()
            ->contain(['HrRequestTypes'])
            ->where(['employee_id' => $empId])
            ->orderBy(['HrRequests.id' => 'DESC'])
            ->limit(100)
            ->all() as $r) {
            $rows[] = [
                'sort' => is_object($r->created) ? $r->created->format('Y-m-d H:i:s') : (string)$r->created,
                'ref' => $r->request_no,
                'type' => $r->hr_request_type->name ?? 'Request',
                'title' => $r->title,
                'status' => $r->status,
                'url' => Router::url(['prefix' => 'Hrms', 'controller' => 'My', 'action' => 'viewRequest', $r->id]),
            ];
        }
        foreach ($this->fetchTable('HrLeaveRequests')->find()
            ->contain(['HrLeaveTypes'])
            ->where(['employee_id' => $empId])
            ->orderBy(['HrLeaveRequests.id' => 'DESC'])
            ->limit(100)
            ->all() as $l) {
            $rows[] = [
                'sort' => is_object($l->created) ? $l->created->format('Y-m-d H:i:s') : (string)$l->created,
                'ref' => 'LEAVE-' . $l->id,
                'type' => 'Leave',
                'title' => ($l->hr_leave_type->name ?? 'Leave') . ' (' . $l->start_date . ')',
                'status' => $l->status,
                'url' => Router::url(['prefix' => 'Hrms', 'controller' => 'My', 'action' => 'leaves']),
            ];
        }
        foreach ($this->fetchTable('HrAttendances')->find()
            ->where([
                'employee_id' => $empId,
                'correction_status IS NOT' => null,
                'correction_status !=' => '',
            ])
            ->orderBy(['HrAttendances.id' => 'DESC'])
            ->limit(100)
            ->all() as $a) {
            $date = is_object($a->attendance_date) ? $a->attendance_date->format('Y-m-d') : (string)$a->attendance_date;
            $rows[] = [
                'sort' => is_object($a->modified) ? $a->modified->format('Y-m-d H:i:s') : ((string)$a->modified ?: $date),
                'ref' => 'ATT-CORR-' . $a->id,
                'type' => 'Attendance Correction',
                'title' => $date . ($a->correction_note ? ' — ' . mb_substr((string)$a->correction_note, 0, 60) : ''),
                'status' => $a->correction_status,
                'url' => Router::url(['prefix' => 'Hrms', 'controller' => 'My', 'action' => 'attendance']),
            ];
        }

        usort($rows, static fn ($a, $b) => strcmp($b['sort'], $a['sort']));
        $this->set('rows', array_slice($rows, 0, 50));
    }

    public function addRequest()
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', 'Submit Request');
        $types = $this->fetchTable('HrRequestTypes')->find()->where(['status' => 1])->orderBy(['name' => 'ASC'])->all();

        if ($this->request->is('post')) {
            $typeId = (int)$this->request->getData('request_type_id');
            $type = $this->fetchTable('HrRequestTypes')->get($typeId);
            $entity = $this->fetchTable('HrRequests')->newEntity([
                'request_no' => $this->nextRequestNo(),
                'employee_id' => $empId,
                'request_type_id' => $typeId,
                'title' => (string)$this->request->getData('title'),
                'description' => (string)$this->request->getData('description'),
                'priority' => (string)($this->request->getData('priority') ?: 'normal'),
                'status' => 'pending',
                'asset_category' => !empty($type->needs_asset) ? (string)$this->request->getData('asset_category') : null,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($this->fetchTable('HrRequests')->save($entity)) {
                $this->auditLog('request_submit', 'request', $entity->request_no . ' submitted', (int)$entity->id, $empId);
                $this->Flash->success('Request submitted.');
                return $this->redirect(['action' => 'requests']);
            }
            $this->Flash->error('Could not submit request.');
        }
        $this->set(compact('types'));
    }

    public function viewRequest($id = null)
    {
        $empId = (int)$this->Session->read('hr_employee_id');
        if (!$empId) {
            return $this->redirectDenied();
        }
        $item = $this->fetchTable('HrRequests')->get($id, contain: ['HrRequestTypes', 'HrAssets']);
        if ((int)$item->employee_id !== $empId) {
            return $this->redirectDenied();
        }
        $this->set('pageTitle', $item->request_no);
        $this->set(compact('item'));
    }
}
