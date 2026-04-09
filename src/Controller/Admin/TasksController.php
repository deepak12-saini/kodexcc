<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Admin Durolab pages: /admin/tasks/type and /admin/tasks/index
 */
class TasksController extends AppController
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

    public function type(?string $data = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' :: DuroLab Access');
        $this->checkAdminSession();

        $allowed = ['product', 'technical', 'project_enquiry'];
        if ($data !== null && in_array($data, $allowed, true)) {
            $this->Session->write('durolab_type', $data);

            return $this->redirect(['prefix' => 'Admin', 'controller' => 'Tasks', 'action' => 'index']);
        }

        $selectedType = (string)$this->Session->read('durolab_type');
        if ($selectedType === '') {
            $selectedType = 'product';
            $this->Session->write('durolab_type', $selectedType);
        }
        $this->set('selectedType', $selectedType);

        return null;
    }

    public function index(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' DuroLab');
        $this->checkAdminSession();

        $durolabType = (string)$this->Session->read('durolab_type');
        if ($durolabType === '') {
            $durolabType = 'product';
            $this->Session->write('durolab_type', $durolabType);
        }
        $keyword = trim((string)($this->requestData()['keyowrd'] ?? ''));

        $tasks = [];
        $page = null;
        try {
            $query = $this->fetchTable('SheetTaskCreate')
                ->find()
                ->where(['SheetTaskCreate.type' => $durolabType]);
            if ($keyword !== '') {
                $query->andWhere([
                    'OR' => [
                        'SheetTaskCreate.title LIKE' => '%' . $keyword . '%',
                        'SheetTaskCreate.task_id LIKE' => '%' . $keyword . '%',
                    ],
                ]);
            }
            $this->paginate = [
                'limit' => 25,
                'order' => ['SheetTaskCreate.id' => 'DESC'],
                'sortableFields' => [
                    'SheetTaskCreate.id',
                    'SheetTaskCreate.task_id',
                    'SheetTaskCreate.title',
                    'SheetTaskCreate.type',
                    'SheetTaskCreate.created',
                    'SheetTaskCreate.status',
                ],
            ];
            $page = $this->paginate($query);
            foreach ($page as $entity) {
                if (!$entity instanceof EntityInterface) {
                    continue;
                }
                $tasks[] = ['Task' => $entity->toArray()];
            }
        } catch (\Throwable) {
            // Keep page usable even when legacy task tables vary by environment.
            $tasks = [];
        }

        $this->set('task', $tasks);
        $this->set('tasksPaginated', $page);
        $this->set('keyowrd', $keyword);
        $this->set('durolabType', $durolabType);
    }
}

