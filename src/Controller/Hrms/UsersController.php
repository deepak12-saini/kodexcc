<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

use Cake\Event\EventInterface;

class UsersController extends HrmsController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        // Login page uses bare layout
        if ($this->request->getParam('action') === 'login') {
            $this->viewBuilder()->setLayout('hrms_login');
        }
    }

    public function login()
    {
        if ($this->Session->read('is_hr_user')) {
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            $username = trim((string)$this->request->getData('username'));
            $password = (string)$this->request->getData('password');
            $user = $this->fetchTable('HrUsers')->find()
                ->where([
                    'username' => $username,
                    'password' => $this->hashPassword($password),
                    'is_active' => 1,
                ])
                ->contain(['HrEmployees'])
                ->first();

            if ($user) {
                $user->last_login = date('Y-m-d H:i:s');
                $this->fetchTable('HrUsers')->save($user);

                $this->Session->write('is_hr_user', 1);
                $this->Session->write('hr_role', $user->role);
                $this->Session->write('hr_employee_id', $user->employee_id);
                $this->Session->write('HrUser', [
                    'id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'employee_id' => $user->employee_id,
                    'name' => $user->hr_employee->full_name ?? $user->username,
                ]);
                $this->auditLog('login', 'user', 'User logged in: ' . $user->username, (int)$user->id, $user->employee_id ? (int)$user->employee_id : null);
                $this->Flash->success('Welcome to KodexCC HRMS.');
                return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
            }
            $this->Flash->error('Invalid username or password.');
        }
    }

    public function logout()
    {
        $uid = (int)$this->Session->read('HrUser.id');
        $uname = (string)($this->Session->read('HrUser.username') ?? '');
        $empId = $this->Session->read('hr_employee_id');
        $this->auditLog('logout', 'user', 'User logged out: ' . $uname, $uid ?: null, $empId ? (int)$empId : null);
        $this->Session->delete('is_hr_user');
        $this->Session->delete('hr_role');
        $this->Session->delete('hr_employee_id');
        $this->Session->delete('HrUser');
        $this->Flash->success('Logged out successfully.');
        return $this->redirect(['action' => 'login']);
    }

    public function changePassword()
    {
        $this->checkHrSession();
        if ($this->request->is(['post', 'put'])) {
            $userId = (int)$this->Session->read('HrUser.id');
            $user = $this->fetchTable('HrUsers')->get($userId);
            $current = (string)$this->request->getData('current_password');
            $new = (string)$this->request->getData('new_password');
            $confirm = (string)$this->request->getData('confirm_password');
            if ($user->password !== $this->hashPassword($current)) {
                $this->Flash->error('Current password is incorrect.');
            } elseif ($new === '' || $new !== $confirm) {
                $this->Flash->error('New passwords do not match.');
            } else {
                $user->password = $this->hashPassword($new);
                $this->fetchTable('HrUsers')->save($user);
                $this->Flash->success('Password updated.');
                return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
            }
        }
    }
}
