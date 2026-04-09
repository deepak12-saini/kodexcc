<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Admin categories CRUD (/admin/categories/...).
 */
class CategoriesController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Categories');
        $this->checkAdminSession();

        $this->paginate = [
            'limit' => 25,
            'order' => ['Category.id' => 'DESC'],
            'sortableFields' => [
                'Category.id',
                'Category.category_name',
                'Category.slug',
                'Category.description',
                'Category.image',
                'Category.status',
                'Category.created',
            ],
        ];
        $page = $this->Paginator->paginate('Category');
        // CakePHP 5 PaginatorHelper requires a PaginatedInterface on a view var (see setPaginated / auto-detect).
        $this->set('categoriesPaginated', $page);

        $categories = [];
        foreach ($page as $row) {
            if ($row instanceof EntityInterface) {
                $categories[] = ['Category' => $row->toArray()];
            } elseif (is_array($row) && isset($row['Category'])) {
                $categories[] = $row;
            }
        }
        $this->set('categories', $categories);
    }

    public function add(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add New Category');
        $this->checkAdminSession();

        $built = $this->buildCategoryPostData();
        if ($built === null) {
            return null;
        }
        [$cat, $image] = $built;

        $cat['slug'] = $this->slugifyCategoryName((string)($cat['category_name'] ?? ''));

        if (!empty($image['name'])) {
            $ext = substr(strtolower((string)strrchr($image['name'], '.')), 1);
            $arrExt = ['jpg', 'jpeg', 'gif', 'png'];
            if (in_array($ext, $arrExt, true)) {
                $tmp = $image['tmp_name'] ?? null;
                if (is_string($tmp) && is_uploaded_file($tmp)) {
                    move_uploaded_file($tmp, WWW_ROOT . 'category/' . $image['name']);
                    $cat['image'] = $image['name'];
                }
            } else {
                $this->Session->setFlash('Please upload the valid image extention.', 'default', ['class' => 'alert alert-danger']);

                return $this->redirect(['action' => 'add']);
            }
        } else {
            $cat['image'] = '250250-defult.png';
        }

        $this->Category->create();
        if ($this->Category->save(['Category' => $cat])) {
            $this->Session->setFlash('The Category has been saved', 'default', ['class' => 'alert alert-success']);

            return $this->redirect(['action' => 'index']);
        }
        $this->Session->setFlash('The Category could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);

        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Category');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid category.');
        }

        if (!$this->Category->exists($id)) {
            throw new NotFoundException('Invalid sevice category');
        }

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $built = $this->buildCategoryPostData();
            if ($built === null) {
                return null;
            }
            [$cat, $image] = $built;
            $cat['id'] = $id;
            $cat['slug'] = $this->slugifyCategoryName((string)($cat['category_name'] ?? ''));

            if (!empty($image['name'])) {
                $ext = substr(strtolower((string)strrchr($image['name'], '.')), 1);
                $arrExt = ['jpg', 'jpeg', 'gif', 'png'];
                if (in_array($ext, $arrExt, true)) {
                    $tmp = $image['tmp_name'] ?? null;
                    if (is_string($tmp) && is_uploaded_file($tmp)) {
                        move_uploaded_file($tmp, WWW_ROOT . 'category/' . $image['name']);
                        $cat['image'] = $image['name'];
                    }
                } else {
                    $this->Session->setFlash('Please upload the valid image extention.', 'default', ['class' => 'alert alert-danger']);

                    return $this->redirect(['action' => 'edit', $id]);
                }
            }

            if ($this->Category->save(['Category' => $cat])) {
                $this->Session->setFlash('The Category has been updated', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash('The Category could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        } else {
            $category = $this->Category->find('first', [
                'conditions' => ['Category.id' => $id],
            ]);
            $this->set('Category', $category);
            if (!empty($category['Category'])) {
                $this->setRequestData(['Category' => $category['Category']]);
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
            throw new NotFoundException('Invalid Category');
        }

        if (!$this->Category->exists($id)) {
            throw new NotFoundException('Invalid Category');
        }

        $this->Category->id = $id;
        if ($this->Category->delete()) {
            $this->Session->setFlash('The Category has been deleted.', 'default', ['class' => 'alert alert-success']);
        } else {
            $this->Session->setFlash('The Category could not be deleted.Please, try again.', 'default', ['class' => 'alert alert-danger']);
        }

        return $this->redirect(['action' => 'index']);
    }

    private function slugifyCategoryName(string $name): string
    {
        $slug = str_replace(' ', '-', strtolower($name));

        return str_replace('&', 'and', $slug);
    }

    /**
     * @return array{0: array<string, mixed>, 1: array<string, mixed>|null}|null
     */
    private function buildCategoryPostData(): ?array
    {
        if (!$this->getRequest()->is(['post', 'put', 'patch'])) {
            return null;
        }
        $d = $this->requestData();
        $cat = [];
        if (!empty($d['Category']) && is_array($d['Category'])) {
            $cat = $d['Category'];
        }
        if (!empty($d['data']['Category']) && is_array($d['data']['Category'])) {
            $cat = array_merge($cat, $d['data']['Category']);
        }
        $image = null;
        if (!empty($d['data']['image']) && is_array($d['data']['image'])) {
            $image = $d['data']['image'];
        } elseif (!empty($d['image']) && is_array($d['image'])) {
            $image = $d['image'];
        }

        return [$cat, $image];
    }
}
