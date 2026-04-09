<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Admin subcategories CRUD: /admin/subcategories/...
 */
class SubcategoriesController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' SubCategories');
        $this->checkAdminSession();

        $this->paginate = [
            'limit' => 25,
            'order' => ['Subcategory.id' => 'DESC'],
            'sortableFields' => [
                'Subcategory.id',
                'Subcategory.name',
                'Subcategory.status',
                'Subcategory.created',
                'Category.category_name',
            ],
        ];

        $query = $this->fetchTable('Subcategory')->find()->contain(['Category']);
        $page = $this->paginate($query);
        $this->set('subcategoriesPaginated', $page);

        $categories = [];
        foreach ($page as $entity) {
            if (!$entity instanceof EntityInterface) {
                continue;
            }
            $subArr = $entity->toArray();
            unset($subArr['category']);
            $catArr = [];
            if ($entity->has('category') && $entity->get('category') !== null) {
                $catArr = $entity->get('category')->toArray();
            }
            $categories[] = [
                'Subcategory' => $subArr,
                'Category' => $catArr,
            ];
        }
        $this->set('categories', $categories);
    }

    public function add(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add New SubCategories');
        $this->checkAdminSession();

        $category = $this->Category->find('all');
        $this->set('category', $category);

        if ($this->getRequest()->is('post')) {
            $sub = $this->subcategoryPostData();
            if (isset($sub['category_id'])) {
                $sub['category_id'] = (int)$sub['category_id'];
            }
            if (!empty($sub['name'])) {
                $sub['slug'] = $this->slugify((string)$sub['name']);
            }
            $this->Subcategory->create();
            if ($this->Subcategory->save(['Subcategory' => $sub])) {
                $this->Session->setFlash('The SubCategories has been saved', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash('The SubCategories could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        }

        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Subcategory');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid subcategory.');
        }

        $category = $this->Category->find('all');
        $this->set('category', $category);

        if (!$this->Subcategory->exists($id)) {
            throw new NotFoundException('Invalid subcategory.');
        }

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $sub = $this->subcategoryPostData();
            $sub['id'] = $id;
            if (isset($sub['category_id'])) {
                $sub['category_id'] = (int)$sub['category_id'];
            }
            if (!empty($sub['name'])) {
                $sub['slug'] = $this->slugify((string)$sub['name']);
            }
            if ($this->Subcategory->save(['Subcategory' => $sub])) {
                $this->Session->setFlash('The Subcategory has been updated', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash('The Subcategory could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        } else {
            $options = ['conditions' => ['Subcategory.id' => $id]];
            $Subcategory = $this->Subcategory->find('first', $options);
            $this->set('Subcategory', $Subcategory);
            if (!empty($Subcategory['Subcategory'])) {
                $this->setRequestData(['Subcategory' => $Subcategory['Subcategory']]);
            }
        }

        return null;
    }

    public function delete(?string $id = null): Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Delete');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid Subcategory');
        }

        $this->Subcategory->id = $id;
        if (!$this->Subcategory->exists()) {
            throw new NotFoundException('Invalid Subcategory');
        }

        if ($this->Subcategory->delete()) {
            $this->Session->setFlash('The Subcategory has been deleted.', 'default', ['class' => 'alert alert-success']);
        } else {
            $this->Session->setFlash('The Subcategory could not be deleted.Please, try again.', 'default', ['class' => 'alert alert-danger']);
        }

        return $this->redirect(['action' => 'index']);
    }

    private function slugify(string $name): string
    {
        $slug = str_replace(' ', '-', strtolower($name));

        return str_replace('&', 'and', $slug);
    }

    /**
     * Legacy forms use `data[Subcategory][field]`; CakePHP 5 nests that under request `data`.
     *
     * @return array<string, mixed>
     */
    private function subcategoryPostData(): array
    {
        $d = $this->requestData();
        $sub = [];
        if (!empty($d['Subcategory']) && is_array($d['Subcategory'])) {
            $sub = $d['Subcategory'];
        }
        if (!empty($d['data']['Subcategory']) && is_array($d['data']['Subcategory'])) {
            $sub = array_merge($sub, $d['data']['Subcategory']);
        }

        return $sub;
    }
}
