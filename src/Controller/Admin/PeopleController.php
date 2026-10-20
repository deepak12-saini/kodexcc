<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Utility\IsoAccess;
use Cake\Event\EventInterface;

class PeopleController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
        $this->viewBuilder()->setLayout('admin_layout');
        $this->viewBuilder()->addHelper('Paginator');
        $this->viewBuilder()->addHelper('Form');
        $this->viewBuilder()->addHelper('Time');
        if ((string)$this->request->getParam('action') === 'myProfile') {
            if (!$this->Session->read('is_admin') && !$this->Session->read('is_iso_user')) {
                $this->Flash->error('You need to be logged in to access this area.');
                throw new \Cake\Routing\Exception\RedirectException(\Cake\Routing\Router::url('/admin'));
            }
        } else {
            $this->checkAdminSession();
        }
        $this->set('title_for_layout', 'People');
        $this->set('isoRoles', IsoAccess::ROLES);
    }

    public function employees(): void
    {
        $q = trim((string)$this->request->getQuery('q'));
        $removed = $this->request->getQuery('removed') === '1';
        $query = $this->fetchTable('HrEmployees')->find()
            ->contain(['HrDepartments', 'HrDesignations', 'HrUsers', 'Managers'])
            ->orderBy(['HrEmployees.id' => 'DESC']);
        $query->where($removed ? ['HrEmployees.status' => 'deleted'] : ['HrEmployees.status !=' => 'deleted']);
        if ($q !== '') {
            $query->where([
                'OR' => [
                    'HrEmployees.full_name LIKE' => '%' . $q . '%',
                    'HrEmployees.employee_code LIKE' => '%' . $q . '%',
                    'HrEmployees.email LIKE' => '%' . $q . '%',
                ],
            ]);
        }
        $this->paginate = ['limit' => 20, 'maxLimit' => 100];
        $this->set('items', $this->paginate($query));
        $this->set('q', $q);
        $this->set('removed', $removed);
        $this->set('pageTitle', 'Employees');
    }

    public function employeeRemove($id = null): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post']);
        $table = $this->fetchTable('HrEmployees');
        $entity = $table->get((int)$id);
        $entity->status = 'deleted';
        $entity->modified = date('Y-m-d H:i:s');
        if ($table->save($entity)) {
            $this->setLoginActive((int)$entity->id, 0);
            $this->Flash->success('Employee removed. You can recover them from Removed employees.');
        } else {
            $this->Flash->error('Could not remove this employee.');
        }

        return $this->redirect(['action' => 'employees']);
    }

    public function employeeRecover($id = null): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post']);
        $table = $this->fetchTable('HrEmployees');
        $entity = $table->get((int)$id);
        $entity->status = 'active';
        $entity->modified = date('Y-m-d H:i:s');
        if ($table->save($entity)) {
            $this->setLoginActive((int)$entity->id, 1);
            $this->Flash->success('Employee recovered.');
        } else {
            $this->Flash->error('Could not recover this employee.');
        }

        return $this->redirect(['action' => 'employees', '?' => ['removed' => '1']]);
    }

    public function departmentRemove($id = null): ?\Cake\Http\Response
    {
        return $this->removeSimple('HrDepartments', 'departments', 'department_id', (int)$id, 'Department');
    }

    public function designationRemove($id = null): ?\Cake\Http\Response
    {
        return $this->removeSimple('HrDesignations', 'designations', 'designation_id', (int)$id, 'Designation');
    }

    public function employeeView($id = null): ?\Cake\Http\Response
    {
        $entity = $this->fetchTable('HrEmployees')->find()
            ->contain(['HrDepartments', 'HrDesignations', 'HrUsers', 'Managers'])
            ->where(['HrEmployees.id' => (int)$id])
            ->first();
        if (!$entity) {
            $this->Flash->error('Employee not found.');

            return $this->redirect(['action' => 'employees']);
        }
        $this->set(compact('entity'));
        $this->set('pageTitle', 'Staff KPI');

        return null;
    }

    public function myProfile(): ?\Cake\Http\Response
    {
        $employeeId = (int)$this->Session->read('iso_employee_id');
        if ($employeeId <= 0) {
            $this->Flash->error('Your login is not linked to an employee profile.');

            return $this->redirect('/admin/iso');
        }
        $table = $this->fetchTable('HrEmployees');
        $entity = $table->find()
            ->contain(['HrDepartments', 'HrDesignations', 'Managers'])
            ->where(['HrEmployees.id' => $employeeId])
            ->first();
        if (!$entity) {
            $this->Flash->error('Your employee profile was not found.');

            return $this->redirect('/admin/iso');
        }
        $managers = $this->managerChoices($employeeId);
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            $managerId = ($data['manager_id'] ?? '') === '' ? null : (int)$data['manager_id'];
            if ($managerId !== null && !isset($managers[$managerId]) && !isset($managers[(string)$managerId])) {
                $managerId = $entity->manager_id;
            }
            $entity = $table->patchEntity($entity, [
                'full_name' => trim((string)($data['full_name'] ?? '')),
                'email' => trim((string)($data['email'] ?? '')),
                'mobile' => trim((string)($data['mobile'] ?? '')),
                'kpi' => trim((string)($data['kpi'] ?? '')),
                'manager_id' => $managerId,
                'modified' => date('Y-m-d H:i:s'),
            ], [
                'fields' => ['full_name', 'email', 'mobile', 'kpi', 'manager_id', 'modified'],
            ]);
            if ($entity->getError('full_name') === [] && trim((string)$entity->full_name) !== '' && $table->save($entity)) {
                $this->Flash->success('Your profile was saved.');

                return $this->redirect(['action' => 'myProfile']);
            }
            $this->Flash->error('Could not save your profile.');
        }
        $this->set(compact('entity', 'managers'));
        $this->set('pageTitle', 'My profile');

        return null;
    }

    public function employeeForm($id = null): ?\Cake\Http\Response
    {
        $table = $this->fetchTable('HrEmployees');
        $entity = $id ? $table->get($id, contain: ['HrUsers']) : $table->newEmptyEntity();
        $departments = $this->fetchTable('HrDepartments')->find('list')->where(['status' => 1])->orderBy(['name' => 'ASC'])->toArray();
        $designations = $this->fetchTable('HrDesignations')->find('list')->where(['status' => 1])->orderBy(['name' => 'ASC'])->toArray();
        $managers = $this->managerChoices($entity->isNew() ? null : (int)$entity->id);
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            $username = trim((string)($data['username'] ?? ''));
            $password = (string)($data['password'] ?? '');
            $isoRole = (string)($data['iso_role'] ?? '');
            $isoMenus = \App\Utility\IsoAccess::cleanMenus($data['iso_menus'] ?? []);
            unset($data['username'], $data['password'], $data['iso_role'], $data['iso_menus']);
            if (empty($data['employee_code'])) {
                $data['employee_code'] = 'KDX-EMP-' . str_pad((string)((int)$table->find()->count() + 1), 3, '0', STR_PAD_LEFT);
            }
            $data['status'] = $data['status'] ?? 'active';
            $data['modified'] = date('Y-m-d H:i:s');
            if ($entity->isNew()) {
                $data['created'] = date('Y-m-d H:i:s');
            }
            foreach (['department_id', 'designation_id', 'manager_id'] as $key) {
                if (($data[$key] ?? '') === '') {
                    $data[$key] = null;
                }
            }
            if (!$entity->isNew() && (int)($data['manager_id'] ?? 0) === (int)$entity->id) {
                $data['manager_id'] = null;
            }
            $entity = $table->patchEntity($entity, $data);
            if ($table->save($entity)) {
                if (!$this->saveLogin((int)$entity->id, $username, $password, $isoRole, $isoMenus, $entity->hr_user ?? null)) {
                    return $this->redirect(['action' => 'employeeForm', $entity->id]);
                }
                $this->Flash->success('Employee saved.');

                return $this->redirect(['action' => 'employees']);
            }
            $this->Flash->error('Could not save this employee.');
        }
        $storedMenus = $entity->hr_user->iso_sections ?? null;
        $roleForMenus = (string)($entity->hr_user->iso_role ?? '');
        $checkedMenus = ($storedMenus === null || $storedMenus === '')
            ? IsoAccess::menusForRole($roleForMenus)
            : IsoAccess::cleanMenus(json_decode((string)$storedMenus, true));
        $roleMenuMap = [];
        foreach (array_keys(IsoAccess::ROLES) as $roleKey) {
            $roleMenuMap[$roleKey] = IsoAccess::menusForRole($roleKey);
        }
        $this->set(compact('entity', 'departments', 'designations', 'managers'));
        $this->set('isoMenus', IsoAccess::MENUS);
        $this->set('checkedMenus', $checkedMenus);
        $this->set('roleMenuMap', $roleMenuMap);
        $this->set('pageTitle', $id ? 'Edit employee' : 'Add employee');

        return null;
    }

    public function departments(): void
    {
        $query = $this->fetchTable('HrDepartments')->find()->orderBy(['HrDepartments.id' => 'DESC']);
        $this->paginate = ['limit' => 20, 'maxLimit' => 100];
        $this->set('items', $this->paginate($query));
        $this->set('pageTitle', 'Departments');
    }

    public function departmentForm($id = null): ?\Cake\Http\Response
    {
        return $this->simpleForm('HrDepartments', 'departments', 'Department', $id, ['name', 'code']);
    }

    public function designations(): void
    {
        $query = $this->fetchTable('HrDesignations')->find()->contain(['HrDepartments'])->orderBy(['HrDesignations.id' => 'DESC']);
        $this->paginate = ['limit' => 20, 'maxLimit' => 100];
        $this->set('items', $this->paginate($query));
        $this->set('pageTitle', 'Designations');
    }

    public function designationForm($id = null): ?\Cake\Http\Response
    {
        $table = $this->fetchTable('HrDesignations');
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        $departments = $this->fetchTable('HrDepartments')->find('list')->where(['status' => 1])->orderBy(['name' => 'ASC'])->toArray();
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            if (($data['department_id'] ?? '') === '') {
                $data['department_id'] = null;
            }
            $data['status'] = isset($data['status']) ? (int)$data['status'] : 1;
            $data['modified'] = date('Y-m-d H:i:s');
            if ($entity->isNew()) {
                $data['created'] = date('Y-m-d H:i:s');
            }
            $entity = $table->patchEntity($entity, $data);
            if ($table->save($entity)) {
                $this->Flash->success('Designation saved.');

                return $this->redirect(['action' => 'designations']);
            }
            $this->Flash->error('Could not save this designation.');
        }
        $this->set(compact('entity', 'departments'));
        $this->set('label', 'Designation');
        $this->set('listAction', 'designations');
        $this->set('pageTitle', $id ? 'Edit designation' : 'Add designation');
        $this->render('simple_form');

        return null;
    }

    private function simpleForm(string $alias, string $listAction, string $label, $id, array $textFields): ?\Cake\Http\Response
    {
        $table = $this->fetchTable($alias);
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            $data['status'] = isset($data['status']) ? (int)$data['status'] : 1;
            $data['modified'] = date('Y-m-d H:i:s');
            if ($entity->isNew()) {
                $data['created'] = date('Y-m-d H:i:s');
            }
            $entity = $table->patchEntity($entity, $data);
            if ($table->save($entity)) {
                $this->Flash->success($label . ' saved.');

                return $this->redirect(['action' => $listAction]);
            }
            $this->Flash->error('Could not save this ' . strtolower($label) . '.');
        }
        $this->set(compact('entity', 'textFields', 'listAction', 'label'));
        $this->set('pageTitle', ($id ? 'Edit ' : 'Add ') . strtolower($label));
        $this->render('simple_form');

        return null;
    }

    private function saveLogin(int $employeeId, string $username, string $password, string $isoRole, array $isoMenus, $existing): bool
    {
        if (!isset(IsoAccess::ROLES[$isoRole])) {
            $isoRole = '';
        }
        $users = $this->fetchTable('HrUsers');
        if ($username === '' && $existing === null) {
            return true;
        }
        $user = $existing ?: $users->find()->where(['employee_id' => $employeeId])->first() ?: $users->newEmptyEntity();
        $menus = json_encode(IsoAccess::cleanMenus($isoMenus));
        $data = [
            'employee_id' => $employeeId,
            'iso_role' => $isoRole !== '' ? $isoRole : null,
            'iso_sections' => $menus,
            'is_active' => 1,
            'modified' => date('Y-m-d H:i:s'),
        ];
        if ($username !== '') {
            $taken = $users->find()->where(['username' => $username]);
            if (!$user->isNew()) {
                $taken->where(['id !=' => $user->id]);
            }
            if ($taken->count() > 0) {
                $this->Flash->error('That username is already used.');

                return false;
            }
            $data['username'] = $username;
        }
        if ($password !== '') {
            $data['password'] = hash('sha256', $password);
        }
        if ($user->isNew()) {
            if ($username === '' || $password === '') {
                return true;
            }
            $data['role'] = 'employee';
            $data['created'] = date('Y-m-d H:i:s');
        }
        try {
            $saved = $users->save($users->patchEntity($user, $data));
        } catch (\Throwable) {
            $saved = false;
        }
        if (!$saved) {
            $this->Flash->error('Could not save the menu permissions.');

            return false;
        }
        try {
            $fresh = $users->find()->select(['id', 'iso_sections'])->where(['id' => $user->id])->enableHydration(false)->first();
        } catch (\Throwable) {
            $fresh = null;
        }
        if (!is_array($fresh) || (string)($fresh['iso_sections'] ?? '') !== $menus) {
            $this->Flash->error('Menu permissions were not stored. Run the iso sections database update, then save again.');

            return false;
        }

        return true;
    }

    /**
     * Active employees who can be chosen as a reporting manager.
     *
     * @return array<int, string>
     */
    private function managerChoices(?int $exceptId): array
    {
        $query = $this->fetchTable('HrEmployees')->find('list')
            ->where(['status' => 'active'])
            ->orderBy(['full_name' => 'ASC']);
        if ($exceptId) {
            $query->where(['HrEmployees.id !=' => $exceptId]);
        }

        return $query->toArray();
    }

    private function setLoginActive(int $employeeId, int $active): void
    {
        $users = $this->fetchTable('HrUsers');
        $user = $users->find()->where(['employee_id' => $employeeId])->first();
        if (!$user) {
            return;
        }
        $user->is_active = $active;
        $user->modified = date('Y-m-d H:i:s');
        $users->save($user);
    }

    private function removeSimple(string $alias, string $listAction, string $employeeField, int $id, string $label): ?\Cake\Http\Response
    {
        $this->request->allowMethod(['post']);
        $used = $this->fetchTable('HrEmployees')->find()
            ->where([$employeeField => $id, 'status !=' => 'deleted'])
            ->count();
        if ($used > 0) {
            $this->Flash->error('This ' . strtolower($label) . ' is still used by an employee, so it was not deleted.');

            return $this->redirect(['action' => $listAction]);
        }
        $table = $this->fetchTable($alias);
        $entity = $table->find()->where(['id' => $id])->first();
        if ($entity && $table->delete($entity)) {
            $this->Flash->success($label . ' deleted.');
        } else {
            $this->Flash->error('Could not delete this ' . strtolower($label) . '.');
        }

        return $this->redirect(['action' => $listAction]);
    }
}
