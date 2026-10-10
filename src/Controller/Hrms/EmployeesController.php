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
            ->contain(['HrDepartments', 'HrDesignations', 'HrShifts'])
            ->where(['HrEmployees.status !=' => 'deleted']);

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
            'order' => ['HrEmployees.id' => 'DESC'],
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
        $loginUser = null;
        $this->set(compact('entity', 'loginUser'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Edit Employee');
        $table = $this->fetchTable('HrEmployees');
        $entity = $table->get($id);
        $this->loadFormLists();
        $loginUser = $this->fetchTable('HrUsers')->find()->where(['employee_id' => (int)$id])->first();
        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->request->getData();
            $username = trim((string)($data['username'] ?? ''));
            $password = (string)($data['password'] ?? '');
            $loginRole = (string)($data['login_role'] ?? '');
            unset($data['username'], $data['password'], $data['login_role']);
            $entity = $table->patchEntity($entity, $data + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $loginError = $this->saveEmployeeLogin((int)$entity->id, $username, $password, $loginRole, $loginUser);
                if ($loginError !== null) {
                    $this->Flash->error($loginError);
                }
                $this->auditLog('employee_update', 'employee', 'Updated employee ' . $entity->employee_code, (int)$entity->id, (int)$entity->id);
                $this->Flash->success('Employee updated.');
                return $this->redirect(['action' => 'view', $id]);
            }
            $this->Flash->error('Could not update.');
        }
        $this->set(compact('entity', 'loginUser'));
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
        // Attendance history is disabled.
        $attendances = [];
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

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->requireHrRole(['admin', 'hr']);
        $table = $this->fetchTable('HrEmployees');
        $entity = $table->get($id);
        $entity->status = 'deleted';
        $entity->modified = date('Y-m-d H:i:s');
        if ($table->save($entity)) {
            $user = $this->fetchTable('HrUsers')->find()->where(['employee_id' => (int)$entity->id])->first();
            if ($user) {
                $user->is_active = 0;
                $user->modified = date('Y-m-d H:i:s');
                $this->fetchTable('HrUsers')->save($user);
            }
            $this->Flash->success('Employee removed.');
        } else {
            $this->Flash->error('Could not remove this employee.');
        }

        return $this->redirect(['action' => 'index']);
    }

    private function saveEmployeeLogin(int $employeeId, string $username, string $password, string $loginRole, $existing): ?string
    {
        $allowed = ['employee', 'manager', 'hr', 'admin', 'it'];
        if (!in_array($loginRole, $allowed, true)) {
            $loginRole = (string)($existing->role ?? 'employee');
        }
        $users = $this->fetchTable('HrUsers');
        if ($existing === null && $username === '') {
            return null;
        }
        if ($username !== '') {
            $taken = $users->find()->where(['username' => $username]);
            if ($existing !== null) {
                $taken->where(['id !=' => $existing->id]);
            }
            if ($taken->count() > 0) {
                return 'That username is already used.';
            }
        }
        if ($existing === null) {
            if ($password === '') {
                return null;
            }
            $user = $users->newEntity([
                'employee_id' => $employeeId,
                'username' => $username,
                'password' => $this->hashPassword($password),
                'role' => $loginRole,
                'is_active' => 1,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            $users->save($user);

            return null;
        }
        if ($username !== '') {
            $existing->username = $username;
        }
        if ($password !== '') {
            $existing->password = $this->hashPassword($password);
        }
        $existing->role = $loginRole;
        $existing->is_active = 1;
        $existing->modified = date('Y-m-d H:i:s');
        $users->save($existing);

        return null;
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
