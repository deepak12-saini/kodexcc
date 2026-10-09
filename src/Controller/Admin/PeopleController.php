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
        $this->checkAdminSession();
        $this->set('title_for_layout', 'People');
        $this->set('isoRoles', IsoAccess::ROLES);
    }

    public function employees(): void
    {
        $q = trim((string)$this->request->getQuery('q'));
        $query = $this->fetchTable('HrEmployees')->find()
            ->contain(['HrDepartments', 'HrDesignations', 'HrUsers'])
            ->orderBy(['HrEmployees.id' => 'DESC']);
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
        $this->set('pageTitle', 'Employees');
    }

    public function employeeForm($id = null): ?\Cake\Http\Response
    {
        $table = $this->fetchTable('HrEmployees');
        $entity = $id ? $table->get($id, contain: ['HrUsers']) : $table->newEmptyEntity();
        $departments = $this->fetchTable('HrDepartments')->find('list')->where(['status' => 1])->orderBy(['name' => 'ASC'])->toArray();
        $designations = $this->fetchTable('HrDesignations')->find('list')->where(['status' => 1])->orderBy(['name' => 'ASC'])->toArray();
        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();
            $username = trim((string)($data['username'] ?? ''));
            $password = (string)($data['password'] ?? '');
            $isoRole = (string)($data['iso_role'] ?? '');
            unset($data['username'], $data['password'], $data['iso_role']);
            if (empty($data['employee_code'])) {
                $data['employee_code'] = 'KDX-EMP-' . str_pad((string)((int)$table->find()->count() + 1), 3, '0', STR_PAD_LEFT);
            }
            $data['status'] = $data['status'] ?? 'active';
            $data['modified'] = date('Y-m-d H:i:s');
            if ($entity->isNew()) {
                $data['created'] = date('Y-m-d H:i:s');
            }
            foreach (['department_id', 'designation_id'] as $key) {
                if (($data[$key] ?? '') === '') {
                    $data[$key] = null;
                }
            }
            $entity = $table->patchEntity($entity, $data);
            if ($table->save($entity)) {
                $this->saveLogin((int)$entity->id, $username, $password, $isoRole, $entity->hr_user ?? null);
                $this->Flash->success('Employee saved.');

                return $this->redirect(['action' => 'employees']);
            }
            $this->Flash->error('Could not save this employee.');
        }
        $this->set(compact('entity', 'departments', 'designations'));
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

    private function saveLogin(int $employeeId, string $username, string $password, string $isoRole, $existing): void
    {
        if (!isset(IsoAccess::ROLES[$isoRole])) {
            $isoRole = '';
        }
        $users = $this->fetchTable('HrUsers');
        if ($username === '' && $existing === null) {
            return;
        }
        $user = $existing ?: $users->find()->where(['employee_id' => $employeeId])->first() ?: $users->newEmptyEntity();
        $data = [
            'employee_id' => $employeeId,
            'iso_role' => $isoRole !== '' ? $isoRole : null,
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

                return;
            }
            $data['username'] = $username;
        }
        if ($password !== '') {
            $data['password'] = hash('sha256', $password);
        }
        if ($user->isNew()) {
            if ($username === '' || $password === '') {
                return;
            }
            $data['role'] = 'employee';
            $data['created'] = date('Y-m-d H:i:s');
        }
        $users->save($users->patchEntity($user, $data));
    }
}
