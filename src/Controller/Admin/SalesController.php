<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use DateTimeImmutable;

/**
 * Admin sales: /admin/sales/...
 */
class SalesController extends AppController
{
    public function __get(string $name): mixed
    {
        if (preg_match('/^[A-Z][A-Za-z0-9_]*$/', $name) === 1) {
            $adapter = $this->legacyModel($name);
            if ($adapter !== null) {
                return $adapter;
            }
        }

        return parent::__get($name);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
    }

    public function index(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Sale Customer List');
        $this->checkAdminSession();

        $userper = $this->UserPermission->find('list', [
            'conditions' => ['UserPermission.meta_val' => 'sr'],
            'fields' => ['user_id'],
        ]);
        $ids = array_values($userper);
        $nappUser = $this->fetchTable('NappUser');
        $query = $nappUser->find()->contain(['Department']);
        if ($ids === []) {
            $query->where('1 = 0');
        } else {
            $query->where(['NappUser.id IN' => $ids, 'NappUser.is_staff_id' => 1]);
        }

        $this->paginate = [
            'limit' => 25,
            'order' => ['NappUser.id' => 'DESC'],
            'sortableFields' => [
                'NappUser.id',
                'NappUser.name',
                'NappUser.email',
                'NappUser.mobile_number',
                'NappUser.is_approved',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('salesTeamPaginated', $page);

        $staff = [];
        foreach ($page as $row) {
            if (!$row instanceof EntityInterface) {
                continue;
            }
            $nu = $row->toArray();
            $dept = [];
            if ($row->has('department') && $row->get('department') !== null) {
                $dept = $row->get('department')->toArray();
            }
            unset($nu['department']);
            $staff[] = [
                'NappUser' => $nu,
                'Department' => $this->legacyDepartmentRow($dept),
            ];
        }
        $this->set('staff', $staff);
    }

    public function attendanceReport(?string $staffId = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Attendance Page');
        $this->checkAdminSession();

        $post = $this->requestData();
        if (!empty($post['start']) && !empty($post['end'])) {
            $start = date('Y-m-d', strtotime((string)$post['start']));
            $end = date('Y-m-d', strtotime((string)$post['end']));
        } else {
            $start = date('Y-m-d', strtotime('-30 days'));
            $end = date('Y-m-d');
        }

        $napuser = $this->NappUser->find('first', ['conditions' => ['NappUser.id' => $staffId]]);
        $inTime = (string)($napuser['NappUser']['in_time'] ?? '9:00');
        $exp = explode(':', $inTime);
        $exph = (int)($exp[0] ?? 9);
        $setdtime = 0.0;
        if (($exp[1] ?? '') === '00') {
            $setdtime = $exph + 0.01;
        } elseif (($exp[1] ?? '') === '30') {
            $setdtime = $exph + 0.29;
        }

        $sid = (int)($staffId ?? 0);
        $attendanceRows = $this->Attendance->query(
            'SELECT * FROM attendances WHERE staff_id = ' . $sid
            . " AND (DATE(created) >= '" . $start . "' AND DATE(created) <= '" . $end . "')"
        );

        $attendance = [];
        foreach ($attendanceRows as $r) {
            $attendance[] = ['attendances' => $r];
        }

        $attend = [];
        foreach ($attendance as $attendances) {
            $created = $attendances['attendances']['created'] ?? null;
            if ($created === null) {
                continue;
            }
            $dst = date('Y-m-d', strtotime((string)$created));
            $h = (int)date('H', strtotime((string)$created));
            $i = date('i', strtotime((string)$created));
            $dtime = $this->minuteToFractionalHour($h, $i);
            $attendnew = ['date' => $dst, 'time' => $dtime];
            $attend[$dst] = $attendnew;
        }

        $now = strtotime($end);
        $yourDate = strtotime($start);
        $day = (int)round(($now - $yourDate) / (60 * 60 * 24));

        $absent = 0;
        $late = 0;
        $present = 0;
        $datearr = [];
        for ($j = 0; $j <= $day; $j++) {
            $dt = date('Y-m-d', strtotime($start . ' +' . $j . ' days'));
            $final = [];
            if (isset($attend[$dt]['date']) && ($attend[$dt]['date'] === $dt)) {
                $final['name'] = date('d M Y', strtotime($start . ' +' . $j . ' days'));
                $final['y'] = $attend[$dt]['time'];
                if ($setdtime <= $attend[$dt]['time']) {
                    $late++;
                } else {
                    $present++;
                }
            } else {
                $final['name'] = date('d M Y', strtotime($start . ' +' . $j . ' days'));
                $final['y'] = 0.0;
                $absent++;
            }
            $datearr[] = $final;
        }

        $this->set(compact('start', 'end', 'attendance', 'datearr', 'absent', 'late', 'present', 'napuser'));
    }

    public function attendance(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Staff Attendance');
        $this->checkAdminSession();

        $post = $this->requestData();
        if (!empty($post['date'])) {
            $date = (string)$post['date'];
        } else {
            $date = date('Y-m-d');
        }

        $attendanceTable = $this->fetchTable('Attendance');
        $startTs = $date . ' 00:00:00';
        $endTs = $date . ' 23:59:59';
        $query = $attendanceTable->find()
            ->contain(['NappUser'])
            ->where([
                'Attendance.created >=' => $startTs,
                'Attendance.created <=' => $endTs,
            ]);

        $this->paginate = [
            'limit' => 25,
            'order' => ['Attendance.id' => 'DESC'],
            'sortableFields' => [
                'Attendance.id',
                'NappUser.name',
                'NappUser.email',
                'Attendance.address',
                'Attendance.created',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('salesAttendancePaginated', $page);

        $attendanceArr = [];
        foreach ($page as $e) {
            if (!$e instanceof EntityInterface) {
                continue;
            }
            $a = $e->toArray();
            $nu = $a['napp_user'] ?? [];
            unset($a['napp_user']);
            $attendanceArr[] = ['Attendance' => $a, 'NappUser' => $nu];
        }
        $this->set('AttendanceArr', $attendanceArr);
        $this->set('date', $date);
    }

    public function saledasboard(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Sale Dashboard');
        $this->checkAdminSession();

        $userper = $this->UserPermission->find('list', [
            'conditions' => ['UserPermission.meta_val' => 'sr'],
            'fields' => ['user_id'],
        ]);
        $logTable = $this->fetchTable('Log');
        $conn = $logTable->getConnection();
        $logUserIds = [];
        try {
            $stmt = $conn->execute('SELECT DISTINCT user_id FROM logs WHERE user_id IS NOT NULL');
            $logUserIds = array_map('intval', array_column($stmt->fetchAll('assoc'), 'user_id'));
        } catch (\Throwable) {
        }
        $userids = array_unique(array_merge(array_values($userper), $logUserIds));

        $napuser = [];
        if ($userids !== []) {
            $napuser = $this->NappUser->find('all', ['conditions' => ['NappUser.id' => $userids]]);
        }
        $this->set('napuser', $napuser);

        $post = $this->requestData();
        $startdate = '';
        $enddate = '';
        $slaetype = '';
        $saleRepTable = $this->fetchTable('SaleRep');
        $logT = $this->fetchTable('Log');

        if ($post !== []) {
            $sdate = (string)($post['startdate'] ?? '');
            $edate = (string)($post['enddate'] ?? '');
            $startdate = date('Y-m-d', strtotime($sdate));
            $enddate = date('Y-m-d', strtotime($edate));
        } else {
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $enddate = date('Y-m-d');
        }

        $startTs = $startdate . ' 00:00:00';
        $endTs = $enddate . ' 23:59:59';

        $saleRepEntities = $saleRepTable->find()
            ->contain(['SaleQuestion', 'NappUser'])
            ->where([
                'SaleRep.created >=' => $startTs,
                'SaleRep.created <=' => $endTs,
            ])
            ->all();

        $SaleRepArr = $this->wrapSaleRepDashboardRows($saleRepEntities);

        $logsEntities = $logT->find()
            ->where([
                'Log.created >=' => $startTs,
                'Log.created <=' => $endTs,
            ])
            ->all();
        $LogsArr = [];
        foreach ($logsEntities as $le) {
            if ($le instanceof EntityInterface) {
                $LogsArr[] = ['Log' => $le->toArray()];
            }
        }

        $startdates = date('Y-m-d', strtotime('-8 days'));
        $enddates = date('Y-m-d');
        $then = new DateTimeImmutable($enddates);
        $nowD = new DateTimeImmutable($startdates);
        $sinceThen = $then->diff($nowD);
        $d = (int)$sinceThen->d;
        $finaldata = [];

        foreach ($napuser as $napusers) {
            $data = ['name' => $napusers['NappUser']['name'] ?? ''];
            $nnew = [];
            for ($i = 0; $i < $d; $i++) {
                $startdatenew = date('Y-m-d', strtotime($startdates . ' +' . $i . ' days'));
                $dayStart = $startdatenew . ' 00:00:00';
                $dayEnd = $startdatenew . ' 23:59:59';
                $totalsalesrep = $saleRepTable->find()
                    ->where([
                        'SaleRep.staff_id' => $napusers['NappUser']['id'],
                        'SaleRep.created >=' => $dayStart,
                        'SaleRep.created <=' => $dayEnd,
                    ])
                    ->count();
                $totallogs = $logT->find()
                    ->where([
                        'Log.user_id' => $napusers['NappUser']['id'],
                        'Log.created >=' => $dayStart,
                        'Log.created <=' => $dayEnd,
                    ])
                    ->count();
                $nnew[] = $totalsalesrep + $totallogs;
            }
            $data['data'] = $nnew;
            $finaldata[] = $data;
        }

        $finaldate = [];
        for ($k = 0; $k < $d; $k++) {
            $finaldate[] = date('d-M', strtotime($startdates . ' +' . $k . ' days'));
        }

        $slatestype = $this->SaleQuestion->find('all');
        $this->set(compact('slatestype', 'SaleRepArr', 'startdate', 'enddate', 'slaetype', 'finaldata', 'd', 'finaldate', 'LogsArr'));
    }

    public function report(?string $staffId = null, ?string $extra = null): void
    {
        if ($extra === 'clearall' && $staffId !== null && $staffId !== '') {
            $this->redirect(['action' => 'report', $staffId]);

            return;
        }

        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Report Page');
        $this->checkAdminSession();

        $napuser = $this->NappUser->find('first', ['conditions' => ['NappUser.id' => $staffId]]);
        $saleRepTable = $this->fetchTable('SaleRep');
        $logsTable = $this->fetchTable('Log');

        $post = $this->requestData();
        $startdate = '';
        $enddate = '';
        $slaetype = '';

        if ($this->getRequest()->is('post') && $post !== []) {
            $slaetype = (string)($post['slaetype'] ?? '');
            $startdate = (string)($post['startdate'] ?? '');
            $enddate = (string)($post['enddate'] ?? '');
            $startTs = $startdate !== '' ? $startdate . ' 00:00:00' : '';
            $endTs = $enddate !== '' ? $enddate . ' 23:59:59' : '';

            $query = $saleRepTable->find()->contain(['SaleQuestion'])->where(['SaleRep.staff_id' => $staffId]);
            if ($startTs !== '' && $endTs !== '') {
                $query->where([
                    'SaleRep.created >=' => $startTs,
                    'SaleRep.created <=' => $endTs,
                ]);
            }
            if ($slaetype !== '') {
                $query->where(['SaleRep.sales_question_id' => $slaetype]);
            }
            $query = $query->orderBy(['SaleRep.id' => 'DESC']);

            $this->paginate = [
                'limit' => 100,
                'order' => ['SaleRep.id' => 'DESC'],
                'sortableFields' => [
                    'SaleRep.id',
                    'SaleRep.name',
                    'SaleRep.email',
                    'SaleRep.phone',
                    'SaleRep.company',
                    'SaleRep.spoken_to',
                    'SaleRep.sample_given_request',
                    'SaleRep.comment',
                    'SaleRep.address',
                    'SaleRep.created',
                ],
            ];
            $page = $this->paginate($query);
            $this->set('saleRepReportPaginated', $page);

            $totallogs = [];
            if ($startTs !== '' && $endTs !== '') {
                $totallogs = $this->wrapLogClientRows(
                    $logsTable->find()->contain(['Client'])->where([
                        'Log.user_id' => $staffId,
                        'Log.created >=' => $startTs,
                        'Log.created <=' => $endTs,
                    ])->all()
                );
            }
        } else {
            $startdate = date('Y-m-d', strtotime('-7 days'));
            $enddate = date('Y-m-d');
            $startTs = $startdate . ' 00:00:00';
            $endTs = $enddate . ' 23:59:59';

            $query = $saleRepTable->find()->contain(['SaleQuestion'])->where([
                'SaleRep.staff_id' => $staffId,
                'SaleRep.created >=' => $startTs,
                'SaleRep.created <=' => $endTs,
            ])->orderBy(['SaleRep.id' => 'DESC']);

            $this->paginate = [
                'limit' => 100,
                'order' => ['SaleRep.id' => 'DESC'],
                'sortableFields' => [
                    'SaleRep.id',
                    'SaleRep.name',
                    'SaleRep.email',
                    'SaleRep.phone',
                    'SaleRep.company',
                    'SaleRep.spoken_to',
                    'SaleRep.sample_given_request',
                    'SaleRep.comment',
                    'SaleRep.address',
                    'SaleRep.created',
                ],
            ];
            $page = $this->paginate($query);
            $this->set('saleRepReportPaginated', $page);

            $totallogs = $this->wrapLogClientRows(
                $logsTable->find()->contain(['Client'])->where([
                    'Log.user_id' => $staffId,
                    'Log.created >=' => $startTs,
                    'Log.created <=' => $endTs,
                ])->all()
            );
        }

        $SaleRepArr = $this->wrapSaleRepReportRows($page);

        $this->set('napuser', $napuser);
        $this->set('totallogs', $totallogs);
        $this->set('SaleRepArr', $SaleRepArr);
        $this->set('startdate', $startdate);
        $this->set('enddate', $enddate);
        $this->set('slaetype', $slaetype);

        $slatestype = $this->SaleQuestion->find('all');
        $this->set('slatestype', $slatestype);
    }

    public function settarget(?string $staffId = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Report Page');
        $this->checkAdminSession();

        $post = $this->requestData();
        if ($post !== []) {
            $nu = $post['data']['NappUser'] ?? $post['NappUser'] ?? [];
            if (is_array($nu)) {
                $nu['id'] = $staffId;
                if ($this->NappUser->save(['NappUser' => $nu])) {
                    $this->Session->setFlash('Sale target set successfully', 'default', ['class' => 'alert alert-success']);
                    $this->redirect(['action' => 'index']);

                    return;
                }
            }
        }

        $napuser = $this->NappUser->find('first', ['conditions' => ['NappUser.id' => $staffId]]);
        $this->set('napuser', $napuser);
    }

    public function salereminder(?string $staffId = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Report Page');
        $this->checkAdminSession();

        $found = $this->SaleReminder->find('first');
        $defaults = [
            'first_reminder' => '',
            'second_reminder' => '',
            'third_reminder' => '',
            'f_time' => '08',
            's_time' => '12',
            't_time' => '17',
        ];
        $inner = array_merge($defaults, $found['SaleReminder'] ?? []);
        $SaleReminder = ['SaleReminder' => $inner];
        $this->set('SaleReminder', $SaleReminder);

        $post = $this->requestData();
        if ($post !== []) {
            $sr = $SaleReminder['SaleReminder'];
            $row = [
                'first_reminder' => $post['first_reminder'] ?? '',
                'f_time' => $post['f_time'] ?? '',
                'second_reminder' => $post['second_reminder'] ?? '',
                's_time' => $post['s_time'] ?? '',
                'third_reminder' => $post['third_reminder'] ?? '',
                't_time' => $post['t_time'] ?? '',
            ];
            $pk = $sr['id'] ?? null;
            if ($pk !== null && $pk !== '') {
                $row['id'] = $pk;
            }
            $update = ['SaleReminder' => $row];
            if ($this->SaleReminder->save($update)) {
                $this->Session->setFlash('sale reminder setuo successfully', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'salereminder']);

                return;
            }
        }
    }

    public function salesreport(?string $uniqueId = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Attendance Page');
        $this->checkAdminSession();

        $userTable = $this->fetchTable('User');
        $userArr = $userTable->find()->where(['User.id' => 2])->first();
        if ($userArr !== null) {
            $this->Session->write('User', $userArr->toArray());
            $this->Session->write('is_admin', 1);
        }

        $SaleReportArr = $this->SaleReport->find('first', ['conditions' => ['SaleReport.unique_id' => $uniqueId]]);
        if ($SaleReportArr === []) {
            $this->redirect('/admin/users/dashboard');

            return;
        }

        $weekto = $SaleReportArr['SaleReport']['weekto'] ?? '';
        $weekfrom = $SaleReportArr['SaleReport']['weekfrom'] ?? '';
        $monthfrom = $SaleReportArr['SaleReport']['monthfrom'] ?? '';
        $monthto = $SaleReportArr['SaleReport']['monthto'] ?? '';
        $currentdate = $SaleReportArr['SaleReport']['currentdate'] ?? date('Y-m-d');

        $userPerm = $this->fetchTable('UserPermission');
        $userArrs = $userPerm->find()
            ->contain(['NappUser'])
            ->where(['UserPermission.permssion_id' => 8])
            ->all();

        $reportsummary = [];
        foreach ($userArrs as $up) {
            $userArr = ['UserPermission' => $up->toArray()];
            $napp = $userArr['UserPermission']['napp_user'] ?? [];
            if (!is_array($napp) || (int)($napp['rating'] ?? 0) <= 0) {
                continue;
            }
            $staff_id = (int)($napp['id'] ?? 0);
            if ($staff_id === 0) {
                continue;
            }

            $saleRepAdapter = $this->SaleRep;
            $currentday = $saleRepAdapter->query(
                'SELECT COUNT(IF( sales_question_id = 1,id,null)) AS totalmeeting , COUNT(IF( sales_question_id = 2,id,null)) AS totalcalled , COUNT(IF( sales_question_id = 3,id,null)) AS totaltechsuportcall FROM `sale_reps` where staff_id = '
                . $staff_id . " and (DATE(created) >= '" . $currentdate . "' and DATE(created) <= '" . $currentdate . "')"
            );
            $currentweek = $saleRepAdapter->query(
                'SELECT COUNT(IF( sales_question_id = 1,id,null)) AS totalmeeting , COUNT(IF( sales_question_id = 2,id,null)) AS totalcalled , COUNT(IF( sales_question_id = 3,id,null)) AS totaltechsuportcall FROM `sale_reps` where staff_id = '
                . $staff_id . " and (DATE(created) >= '" . $weekfrom . "' and DATE(created) <= '" . $weekto . "')"
            );
            $currentmonth = $saleRepAdapter->query(
                'SELECT COUNT(IF( sales_question_id = 1,id,null)) AS totalmeeting , COUNT(IF( sales_question_id = 2,id,null)) AS totalcalled , COUNT(IF( sales_question_id = 3,id,null)) AS totaltechsuportcall FROM `sale_reps` where staff_id = '
                . $staff_id . " and (DATE(created) >= '" . $monthfrom . "' and DATE(created) <= '" . $monthto . "')"
            );

            $achive = [];
            $achive['currentday']['totalmeeting'] = $currentday[0]['totalmeeting'] ?? $currentday[0][0]['totalmeeting'] ?? 0;
            $achive['currentday']['totalcalled'] = $currentday[0]['totalcalled'] ?? $currentday[0][0]['totalcalled'] ?? 0;
            $achive['currentday']['totaltechsuportcall'] = $currentday[0]['totaltechsuportcall'] ?? $currentday[0][0]['totaltechsuportcall'] ?? 0;

            $achive['currentweek']['totalmeeting'] = $currentweek[0]['totalmeeting'] ?? $currentweek[0][0]['totalmeeting'] ?? 0;
            $achive['currentweek']['totalcalled'] = $currentweek[0]['totalcalled'] ?? $currentweek[0][0]['totalcalled'] ?? 0;
            $achive['currentweek']['totaltechsuportcall'] = $currentweek[0]['totaltechsuportcall'] ?? $currentweek[0][0]['totaltechsuportcall'] ?? 0;

            $achive['currentmonth']['totalmeeting'] = $currentmonth[0]['totalmeeting'] ?? $currentmonth[0][0]['totalmeeting'] ?? 0;
            $achive['currentmonth']['totalcalled'] = $currentmonth[0]['totalcalled'] ?? $currentmonth[0][0]['totalcalled'] ?? 0;
            $achive['currentmonth']['totaltechsuportcall'] = $currentmonth[0]['totaltechsuportcall'] ?? $currentmonth[0][0]['totaltechsuportcall'] ?? 0;

            $achive['name'] = ($napp['name'] ?? '') . ' ' . ($napp['lname'] ?? '');
            $achive['ff_day'] = !empty($napp['ff_day']) ? $napp['ff_day'] : 0;
            $achive['ff_meeting'] = !empty($napp['ff_meeting']) ? $napp['ff_meeting'] : 0;
            $achive['ff_month'] = !empty($napp['ff_month']) ? $napp['ff_month'] : 0;
            $achive['cc_day'] = !empty($napp['cc_day']) ? $napp['cc_day'] : 0;
            $achive['cc_meeting'] = !empty($napp['cc_meeting']) ? $napp['cc_meeting'] : 0;
            $achive['cc_month'] = !empty($napp['cc_month']) ? $napp['cc_month'] : 0;

            $reportsummary[] = $achive;
        }

        $this->set('reportsummary', $reportsummary);
        $this->set('currentdate', $currentdate);
    }

    public function cronjob(): void
    {
        $this->autoRender = false;
        // Legacy had `die()` here — cron disabled.
    }

    /**
     * @param iterable<EntityInterface> $rows
     * @return list<array<string, mixed>>
     */
    private function wrapSaleRepDashboardRows(iterable $rows): array
    {
        $out = [];
        foreach ($rows as $e) {
            if (!$e instanceof EntityInterface) {
                continue;
            }
            $a = $e->toArray();
            $sq = $a['sale_question'] ?? [];
            $nu = $a['napp_user'] ?? [];
            unset($a['sale_question'], $a['napp_user']);
            $out[] = ['SaleRep' => $a, 'SaleQuestion' => $sq, 'NappUser' => $nu];
        }

        return $out;
    }

    /**
     * @param iterable<EntityInterface> $page
     * @return list<array<string, mixed>>
     */
    private function wrapSaleRepReportRows(iterable $page): array
    {
        $out = [];
        foreach ($page as $e) {
            if (!$e instanceof EntityInterface) {
                continue;
            }
            $a = $e->toArray();
            $sq = $a['sale_question'] ?? [];
            unset($a['sale_question']);
            $out[] = ['SaleRep' => $a, 'SaleQuestion' => $sq];
        }

        return $out;
    }

    /**
     * @param iterable<EntityInterface> $rows
     * @return list<array<string, mixed>>
     */
    private function wrapLogClientRows(iterable $rows): array
    {
        $out = [];
        foreach ($rows as $e) {
            if (!$e instanceof EntityInterface) {
                continue;
            }
            $a = $e->toArray();
            $client = $a['client'] ?? [];
            unset($a['client']);
            $client = $this->mergeClientLegacyDisplayFields(is_array($client) ? $client : []);
            $out[] = ['Log' => $a, 'Client' => $client];
        }

        return $out;
    }

    /**
     * @param array<string, mixed> $dept
     * @return array<string, mixed>
     */
    private function legacyDepartmentRow(array $dept): array
    {
        $title = $dept['department_title'] ?? $dept['title'] ?? $dept['name'] ?? '';

        return ['department_title' => $title] + $dept;
    }

    private function minuteToFractionalHour(int $h, string $min): float
    {
        $i = $min;
        $map = [
            '00' => 0.0, '01' => 0.01, '02' => 0.02, '03' => 0.03, '04' => 0.04, '05' => 0.05,
            '06' => 0.06, '07' => 0.07, '08' => 0.08, '09' => 0.09, '10' => 0.11, '11' => 0.11,
            '12' => 0.12, '13' => 0.13, '14' => 0.14, '15' => 0.15, '16' => 0.16, '17' => 0.17,
            '18' => 0.18, '19' => 0.19, '20' => 0.21, '21' => 0.21, '22' => 0.22, '23' => 0.23,
            '24' => 0.24, '25' => 0.25, '26' => 0.26, '27' => 0.27, '28' => 0.28, '29' => 0.29,
            '30' => 0.29, '31' => 0.31, '32' => 0.32, '33' => 0.33, '34' => 0.34, '35' => 0.35,
            '36' => 0.36, '37' => 0.37, '38' => 0.38, '39' => 0.39, '40' => 0.39, '41' => 0.41,
            '42' => 0.42, '43' => 0.43, '44' => 0.44, '45' => 0.45, '46' => 0.46, '47' => 0.47,
            '48' => 0.48, '49' => 0.49, '50' => 0.49, '51' => 0.51, '52' => 0.52, '53' => 0.53,
            '54' => 0.54, '55' => 0.55, '56' => 0.56, '57' => 0.57, '58' => 0.58, '59' => 0.59,
        ];
        $frac = $map[$i] ?? 0.0;

        return $h + $frac;
    }
}
