<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class EmployeesController extends HrmsController
{
    public function index()
    {
        $this->checkHrSession();
        $role = (string)$this->Session->read('hr_role');
        $this->set('pageTitle', 'Employees');
        $this->set('title_for_layout', 'Employees');

        // order already on query
        $query = $this->fetchTable('HrEmployees')->find()
            ->contain(['HrDepartments', 'HrDesignations', 'HrShifts']);

        if ($role === 'manager') {
            $mgrId = (int)$this->Session->read('hr_employee_id');
            $query->where(['HrEmployees.manager_id' => $mgrId]);
        } elseif ($role === 'employee') {
            return $this->redirect(['prefix' => 'Hrms', 'controller' => 'My', 'action' => 'profile']);
        } else {
            $this->requireHrRole(['admin', 'hr']);
        }

        $q = trim((string)$this->request->getQuery('q'));
        if ($q !== '') {
            $query->where([
                'OR' => [
                    'HrEmployees.full_name LIKE' => '%' . $q . '%',
                    'HrEmployees.employee_code LIKE' => '%' . $q . '%',
                    'HrEmployees.email LIKE' => '%' . $q . '%',
                ],
            ]);
        }

        $this->hrPaginate($query, [
            'order' => ['HrEmployees.full_name' => 'ASC'],
            'sortableFields' => [
                'HrEmployees.full_name',
                'HrEmployees.employee_code',
                'HrEmployees.status',
            ],
        ]);
        $this->set(compact('q'));
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Register Employee');
        $table = $this->fetchTable('HrEmployees');
        $entity = $table->newEmptyEntity();
        $this->loadFormLists();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['created'] = date('Y-m-d H:i:s');
            $data['modified'] = date('Y-m-d H:i:s');
            $data['status'] = $data['status'] ?? 'active';
            if (empty($data['employee_code'])) {
                $data['employee_code'] = 'KDX-EMP-' . str_pad((string)(time() % 100000), 5, '0', STR_PAD_LEFT);
            }
            $entity = $table->patchEntity($entity, $data);
            if ($table->save($entity)) {
                $this->ensureLeaveBalances((int)$entity->id);
                $username = trim((string)($this->request->getData('username') ?: strtolower(preg_replace('/\s+/', '.', $entity->full_name))));
                $password = (string)($this->request->getData('password') ?: 'welcome123');
                $role = (string)($this->request->getData('login_role') ?: 'employee');
                if ($username !== '') {
                    $users = $this->fetchTable('HrUsers');
                    $user = $users->newEntity([
                        'employee_id' => $entity->id,
                        'username' => $username,
                        'password' => $this->hashPassword($password),
                        'role' => $role,
                        'is_active' => 1,
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    ]);
                    $users->save($user);
                }
                $this->auditLog('employee_create', 'employee', 'Created employee ' . $entity->employee_code, (int)$entity->id, (int)$entity->id);
                $this->Flash->success('Employee registered.');
                return $this->redirect(['action' => 'view', $entity->id]);
            }
            $this->Flash->error('Could not save employee.');
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Edit Employee');
        $table = $this->fetchTable('HrEmployees');
        $entity = $table->get($id);
        $this->loadFormLists();
        if ($this->request->is(['post', 'put', 'patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData() + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $this->auditLog('employee_update', 'employee', 'Updated employee ' . $entity->employee_code, (int)$entity->id, (int)$entity->id);
                $this->Flash->success('Employee updated.');
                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error('Could not update.');
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function view($id = null)
    {
        $this->checkHrSession();
        $role = (string)$this->Session->read('hr_role');
        if ($role === 'employee') {
            return $this->redirectDenied();
        }
        if (!in_array($role, ['admin', 'hr', 'manager'], true)) {
            return $this->redirectDenied();
        }

        $tab = (string)($this->request->getQuery('tab') ?: 'personal');
        $employee = $this->fetchTable('HrEmployees')->get($id, contain: [
            'HrDepartments', 'HrDesignations', 'HrShifts', 'Managers', 'HrUsers',
        ]);

        if ($role === 'manager' && (int)$employee->manager_id !== (int)$this->Session->read('hr_employee_id')) {
            return $this->redirectDenied();
        }

        $this->set('pageTitle', $employee->full_name);
        $attendances = $this->fetchTable('HrAttendances')->find()
            ->where(['employee_id' => $id])
            ->orderBy(['attendance_date' => 'DESC'])
            ->limit(30)
            ->all();
        $leaveRequests = $this->fetchTable('HrLeaveRequests')->find()
            ->contain(['HrLeaveTypes'])
            ->where(['employee_id' => $id])
            ->orderBy(['HrLeaveRequests.id' => 'DESC'])
            ->limit(20)
            ->all();
        $this->ensureLeaveBalances((int)$id);
        $balances = $this->fetchTable('HrLeaveBalances')->find()
            ->contain(['HrLeaveTypes'])
            ->where(['employee_id' => $id, 'year' => (int)date('Y')])
            ->all();
        $documents = $this->fetchTable('HrDocuments')->find()
            ->where(['employee_id' => $id])
            ->orderBy(['HrDocuments.id' => 'DESC'])
            ->all();
        $assets = $this->fetchTable('HrAssetAssignments')->find()
            ->contain(['HrAssets'])
            ->where(['employee_id' => $id])
            ->orderBy(['HrAssetAssignments.id' => 'DESC'])
            ->all();

        $this->set(compact('employee', 'tab', 'attendances', 'leaveRequests', 'balances', 'documents', 'assets'));
    }

    private function loadFormLists(): void
    {
        $this->set('departments', $this->fetchTable('HrDepartments')->find('list')->where(['status' => 1])->toArray());
        $this->set('designations', $this->fetchTable('HrDesignations')->find('list')->where(['status' => 1])->toArray());
        $this->set('shifts', $this->fetchTable('HrShifts')->find('list')->where(['status' => 1])->toArray());
        $this->set('managers', $this->fetchTable('HrEmployees')->find('list', [
            'keyField' => 'id',
            'valueField' => 'full_name',
        ])->where(['status' => 'active'])->toArray());
    }
}
