<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Admin product management (/admin/products/...).
 */
class ProductsController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Product List');
        $this->checkAdminSession();

        $session = $this->getRequest()->getSession();
        if ($this->getRequest()->is('post') && $this->getRequest()->getData('search') !== null) {
            $name = trim((string)$this->getRequest()->getData('productname'));
            $session->write('Admin.productSearch', $name);
        }
        $name = (string)$session->read('Admin.productSearch');

        $productTable = $this->fetchTable('Product');
        $query = $productTable->find()
            ->contain(['Category'])
            ->orderBy(['Product.id' => 'DESC']);
        if ($name !== '') {
            $query->where(['Product.title LIKE' => '%' . $name . '%']);
        }

        $this->paginate = [
            'limit' => 25,
            'sortableFields' => [
                'Product.id',
                'Product.title',
                'Product.product_code',
                'Product.brief_description',
                'Product.image',
                'Product.status',
                'Product.is_featured',
                'Product.created',
                'Category.category_name',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('productsPaginated', $page);

        $product = [];
        foreach ($page as $row) {
            if ($row instanceof EntityInterface) {
                $a = $row->toArray();
                $cat = $a['category'] ?? [];
                unset($a['category']);
                foreach (['created', 'modified'] as $dtKey) {
                    if (!empty($a[$dtKey]) && $a[$dtKey] instanceof \DateTimeInterface) {
                        $a[$dtKey] = $a[$dtKey]->format('Y-m-d H:i:s');
                    }
                }
                $product[] = ['Product' => $a, 'Category' => $cat];
            }
        }
        $this->set('product', $product);
        $this->set('name', $name);
        $this->render('/Products/admin_index');
    }

    public function add(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add Product');
        $this->checkAdminSession();

        $this->setCategoriesForProductForms();
        $this->set('product_code', '');

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $built = $this->buildProductPostData();
            if ($built !== null) {
                [$row, $image] = $built;
                if (empty($row['title'])) {
                    $this->Session->setFlash('Please enter product name.', 'default', ['class' => 'alert alert-danger']);
                } else {
                    $row['slug'] = $this->uniqueProductSlug((string)$row['title']);
                    if (!empty($image['name'])) {
                        $moved = $this->moveProductImageUpload($image, 'productimg');
                        if ($moved === null) {
                            $this->Session->setFlash('Please upload a valid image.', 'default', ['class' => 'alert alert-danger']);

                            return $this->redirect(['action' => 'add']);
                        }
                        $row['image'] = $moved;
                    }
                    $this->Product->create();
                    if ($this->Product->save(['Product' => $row])) {
                        $this->Session->setFlash('The Product has been saved', 'default', ['class' => 'alert alert-success']);

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Session->setFlash('The Product could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
                }
            }
        }

        $this->render('/Products/admin_add');

        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Product');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid product.');
        }
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product.');
        }

        $this->setCategoriesForProductForms();
        $this->set('subcategory', []);

        $productRow = $this->Product->find('first', [
            'conditions' => ['Product.id' => $id],
        ]);
        $this->set('product_arr', $productRow);

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $built = $this->buildProductPostData();
            if ($built !== null) {
                [$row, $image] = $built;
                $row['id'] = $id;
                if (empty($row['title'])) {
                    $this->Session->setFlash('Please enter product name.', 'default', ['class' => 'alert alert-danger']);
                } else {
                    $row['slug'] = $this->uniqueProductSlug((string)$row['title'], $id);
                    if (!empty($image['name'])) {
                        $moved = $this->moveProductImageUpload($image, 'productimg');
                        if ($moved === null) {
                            $this->Session->setFlash('Please upload a valid image.', 'default', ['class' => 'alert alert-danger']);

                            return $this->redirect(['action' => 'edit', $id]);
                        }
                        $row['image'] = $moved;
                    } elseif (!empty($productRow['Product']['image'])) {
                        $row['image'] = $productRow['Product']['image'];
                    }
                    $tdsFile = $this->nestedUploadedFileArray('data.Product.tds_image');
                    if (!empty($tdsFile['name'])) {
                        $tdsMoved = $this->moveProductImageUpload($tdsFile, 'productimg');
                        if ($tdsMoved === null) {
                            $this->Session->setFlash('Please upload a valid TDS image.', 'default', ['class' => 'alert alert-danger']);

                            return $this->redirect(['action' => 'edit', $id]);
                        }
                        $row['tds_image'] = $tdsMoved;
                    } elseif (!empty($productRow['Product']['tds_image'])) {
                        $row['tds_image'] = $productRow['Product']['tds_image'];
                    }
                    if ($this->Product->save(['Product' => $row])) {
                        $this->Session->setFlash('The Product has been updated', 'default', ['class' => 'alert alert-success']);

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Session->setFlash('The Product could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
                }
            }
        } else {
            if (!empty($productRow['Product'])) {
                $this->setRequestData(['Product' => $productRow['Product']]);
            }
        }

        if (empty($productRow['Product'])) {
            throw new NotFoundException('Invalid product.');
        }

        $this->render('/Products/admin_edit');

        return null;
    }

    public function delete(?string $id = null): Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid product.');
        }
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product.');
        }
        $this->Product->id = $id;
        if ($this->Product->delete()) {
            $this->Session->setFlash('The Product has been deleted.', 'default', ['class' => 'alert alert-success']);
        } else {
            $this->Session->setFlash('The Product could not be deleted. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        }

        return $this->redirect(['action' => 'index']);
    }

    public function project(?string $productId = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Product Project Images');
        $this->checkAdminSession();

        if ($productId === null || $productId === '' || !$this->Product->exists($productId)) {
            throw new NotFoundException('Invalid product.');
        }

        $imageArr = $this->Project->find('all', [
            'conditions' => ['Project.product_id' => $productId],
            'order' => ['Project.id' => 'DESC'],
        ]);
        $this->set('imageArr', $imageArr);
        $this->set('product_id', $productId);
        $this->render('/Products/admin_project');
    }

    public function projectAdd(?string $productId = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add Project Image');
        $this->checkAdminSession();

        if ($productId === null || $productId === '' || !$this->Product->exists($productId)) {
            throw new NotFoundException('Invalid product.');
        }

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $image = $this->nestedUploadedFileArray('data.Product.image');
            if ($image === null || empty($image['name'])) {
                $this->Session->setFlash('Please select an image.', 'default', ['class' => 'alert alert-danger']);
            } else {
                $moved = $this->moveProductImageUpload($image, 'productimg');
                if ($moved === null) {
                    $this->Session->setFlash('Please upload a valid image.', 'default', ['class' => 'alert alert-danger']);
                } else {
                    $projectTable = $this->fetchTable('Project');
                    $schema = $projectTable->getSchema();
                    $imgField = $schema->hasColumn('images') ? 'images' : 'image';
                    $row = [
                        'product_id' => $productId,
                        $imgField => $moved,
                    ];
                    $entity = $projectTable->newEntity($row);
                    if ($projectTable->save($entity)) {
                        $this->Session->setFlash('Image uploaded.', 'default', ['class' => 'alert alert-success']);

                        return $this->redirect(['action' => 'project', $productId]);
                    }
                    $this->Session->setFlash('Could not save image.', 'default', ['class' => 'alert alert-danger']);
                }
            }
        }

        $this->render('/Products/admin_projectadd');

        return null;
    }

    public function projectDelete(?string $productId = null, ?string $projectId = null): Response
    {
        $this->checkAdminSession();
        if ($productId === null || $projectId === null || $projectId === '') {
            throw new NotFoundException('Invalid project image.');
        }
        if (!$this->Product->exists($productId)) {
            throw new NotFoundException('Invalid product.');
        }

        $projectTable = $this->fetchTable('Project');
        $proj = $projectTable->find()
            ->where(['Project.id' => $projectId, 'Project.product_id' => $productId])
            ->first();
        if ($proj === null) {
            throw new NotFoundException('Invalid project image.');
        }
        $projectTable->delete($proj);
        $this->Session->setFlash('Image removed.', 'default', ['class' => 'alert alert-success']);

        return $this->redirect(['action' => 'project', $productId]);
    }

    public function label(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Product Labels');
        $this->checkAdminSession();

        $labelCateId = $this->getRequest()->getData('category_id')
            ?? $this->getRequest()->getData('label_cate_id');
        if ($labelCateId === null) {
            $labelCateId = $this->getRequest()->getQuery('category_id')
                ?? $this->getRequest()->getQuery('label_cate_id');
        }
        $labelCateId = $labelCateId !== null && $labelCateId !== '' ? (string)$labelCateId : '';

        $labelText = trim((string)$this->getRequest()->getData('label'));
        if ($labelText === '' && $this->getRequest()->getQuery('label') !== null) {
            $labelText = trim((string)$this->getRequest()->getQuery('label'));
        }

        $labelTable = $this->fetchTable('Label');
        $query = $labelTable->find()
            ->contain(['LabelCategory'])
            ->orderBy(['Label.id' => 'DESC']);
        if ($labelCateId !== '') {
            $query->where(['Label.category_id' => $labelCateId]);
        }
        if ($labelText !== '') {
            $query->where(['Label.name LIKE' => '%' . $labelText . '%']);
        }

        $this->paginate = [
            'limit' => 25,
            'sortableFields' => [
                'Label.id',
                'Label.name',
                'LabelCategory.name',
                'LabelCategory.category',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('labelsPaginated', $page);

        $labelArr = [];
        foreach ($page as $row) {
            if ($row instanceof EntityInterface) {
                $a = $row->toArray();
                $lc = $a['label_category'] ?? [];
                unset($a['label_category']);
                $labelArr[] = ['Label' => $a, 'LabelCategory' => $lc];
            }
        }

        $lcRows = $this->LabelCategory->find('all', ['order' => ['LabelCategory.id' => 'ASC']]);
        $this->set('labelArr', $labelArr);
        $this->set('LabelCategory', $lcRows);
        $this->set('category_id', $labelCateId);
        $this->set('label_cate_id', $labelCateId);
        $this->set('label', $labelText);
        $this->render('/Products/admin_label');
    }

    public function addLabel(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add Label');
        $this->checkAdminSession();

        $lcRows = $this->LabelCategory->find('all', ['order' => ['LabelCategory.id' => 'ASC']]);
        $this->set('LabelCategory', $lcRows);

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $d = $this->requestData();
            $label = [];
            if (!empty($d['data']['Label']) && is_array($d['data']['Label'])) {
                $label = $d['data']['Label'];
            }
            if (!empty($label['label_cate_id']) && empty($label['category_id'])) {
                $label['category_id'] = $label['label_cate_id'];
            }
            unset($label['label_cate_id']);
            $file = $this->nestedUploadedFileArray('data.Label.url');
            if (empty($label['category_id'])) {
                $this->Session->setFlash('Please select label category.', 'default', ['class' => 'alert alert-danger']);
            } elseif (empty($label['name'])) {
                $this->Session->setFlash('Please enter label name.', 'default', ['class' => 'alert alert-danger']);
            } elseif ($file === null || empty($file['name'])) {
                $this->Session->setFlash('Please upload a label file.', 'default', ['class' => 'alert alert-danger']);
            } else {
                $moved = $this->moveProductImageUpload($file, 'productimg');
                if ($moved === null) {
                    $this->Session->setFlash('Invalid upload.', 'default', ['class' => 'alert alert-danger']);
                } else {
                    $label['url'] = $moved;
                    $this->Label->create();
                    if ($this->Label->save(['Label' => $label])) {
                        $this->Session->setFlash('Label saved.', 'default', ['class' => 'alert alert-success']);

                        return $this->redirect(['action' => 'label']);
                    }
                    $this->Session->setFlash('Could not save label.', 'default', ['class' => 'alert alert-danger']);
                }
            }
        }

        $this->render('/Products/admin_addlabel');

        return null;
    }

    public function editLabel(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Label');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid label.');
        }
        $row = $this->Label->find('first', ['conditions' => ['Label.id' => $id]]);
        if (empty($row['Label'])) {
            throw new NotFoundException('Invalid label.');
        }

        $lcRows = $this->LabelCategory->find('all', ['order' => ['LabelCategory.id' => 'ASC']]);
        $this->set('LabelCategory', $lcRows);
        $this->set('labelArr', $row);

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $d = $this->requestData();
            $label = $row['Label'];
            if (!empty($d['data']['Label']) && is_array($d['data']['Label'])) {
                $label = array_merge($label, $d['data']['Label']);
            }
            if (!empty($label['label_cate_id']) && empty($label['category_id'])) {
                $label['category_id'] = $label['label_cate_id'];
            }
            unset($label['label_cate_id']);
            $label['id'] = $id;
            $file = $this->nestedUploadedFileArray('data.Label.url');
            if (!empty($file['name'])) {
                $moved = $this->moveProductImageUpload($file, 'productimg');
                if ($moved === null) {
                    $this->Session->setFlash('Invalid upload.', 'default', ['class' => 'alert alert-danger']);

                    return $this->redirect(['action' => 'editLabel', $id]);
                }
                $label['url'] = $moved;
            }
            if ($this->Label->save(['Label' => $label])) {
                $this->Session->setFlash('Label updated.', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'label']);
            }
            $this->Session->setFlash('Could not save label.', 'default', ['class' => 'alert alert-danger']);
        }

        $this->render('/Products/admin_editlabel');

        return null;
    }

    public function deleteLabel(?string $id = null): Response
    {
        $this->checkAdminSession();
        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid label.');
        }
        if (!$this->Label->exists($id)) {
            throw new NotFoundException('Invalid label.');
        }
        $this->Label->id = $id;
        $this->Label->delete();
        $this->Session->setFlash('Label deleted.', 'default', ['class' => 'alert alert-success']);

        return $this->redirect(['action' => 'label']);
    }

    public function vocVertificate(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' VOC Certificates');
        $this->checkAdminSession();

        $vocTable = $this->fetchTable('VocCertificate');
        $query = $vocTable->find()->orderBy(['VocCertificate.id' => 'DESC']);
        $this->paginate = [
            'limit' => 25,
            'sortableFields' => [
                'VocCertificate.id',
                'VocCertificate.certificate_number',
                'VocCertificate.date',
                'VocCertificate.simple_description',
                'VocCertificate.date_tested',
                'VocCertificate.test_method',
                'VocCertificate.sepecification',
                'VocCertificate.product_name',
                'VocCertificate.sepecification_2',
                'VocCertificate.weight',
                'VocCertificate.description',
                'VocCertificate.created',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('vocPaginated', $page);

        $VocCertificate = [];
        foreach ($page as $row) {
            if ($row instanceof EntityInterface) {
                $VocCertificate[] = ['VocCertificate' => $row->toArray()];
            }
        }
        $this->set('VocCertificate', $VocCertificate);
        $this->render('/Products/admin_voc_vertificate');
    }

    public function vocAdd(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add VOC Certificate');
        $this->checkAdminSession();

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $d = $this->requestData();
            $voc = [];
            if (!empty($d['data']['VocCertificate']) && is_array($d['data']['VocCertificate'])) {
                $voc = $d['data']['VocCertificate'];
            }
            $this->VocCertificate->create();
            if ($this->VocCertificate->save(['VocCertificate' => $voc])) {
                $this->Session->setFlash('Certificate saved.', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'vocVertificate']);
            }
            $this->Session->setFlash('Could not save certificate.', 'default', ['class' => 'alert alert-danger']);
        }

        $this->render('/Products/admin_vocadd');

        return null;
    }

    public function vocEdit(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Edit VOC Certificate');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid certificate.');
        }
        if (!$this->VocCertificate->exists($id)) {
            throw new NotFoundException('Invalid certificate.');
        }

        $cert = $this->VocCertificate->find('first', [
            'conditions' => ['VocCertificate.id' => $id],
        ]);
        $this->set('VocCertificateArr', ['VocCertificate' => $cert['VocCertificate'] ?? []]);

        if ($this->getRequest()->is(['post', 'put', 'patch'])) {
            $d = $this->requestData();
            $voc = $cert['VocCertificate'] ?? [];
            if (!empty($d['data']['VocCertificate']) && is_array($d['data']['VocCertificate'])) {
                $voc = array_merge($voc, $d['data']['VocCertificate']);
            }
            $voc['id'] = $id;
            if ($this->VocCertificate->save(['VocCertificate' => $voc])) {
                $this->Session->setFlash('Certificate updated.', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'vocVertificate']);
            }
            $this->Session->setFlash('Could not save certificate.', 'default', ['class' => 'alert alert-danger']);
        }

        $this->render('/Products/admin_vocedit');

        return null;
    }

    private function setCategoriesForProductForms(): void
    {
        $cats = $this->Category->find('all', [
            'conditions' => ['Category.status' => 1],
            'order' => ['Category.category_name' => 'ASC'],
        ]);
        $this->set('category', $cats);
    }

    private function slugifyProductTitle(string $name): string
    {
        $slug = str_replace(' ', '-', strtolower($name));

        return str_replace('&', 'and', $slug);
    }

    private function uniqueProductSlug(string $title, ?string $excludeId = null): string
    {
        $base = $this->slugifyProductTitle($title);
        $table = $this->fetchTable('Product');
        $slug = $base;
        $n = 0;
        while (true) {
            $conditions = ['Product.slug' => $slug];
            if ($excludeId !== null) {
                $conditions['Product.id !='] = $excludeId;
            }
            if ($table->find()->where($conditions)->count() === 0) {
                break;
            }
            $n++;
            $slug = $base . '-' . $n;
        }

        return $slug;
    }

    /**
     * @return array{0: array<string, mixed>, 1: array<string, mixed>|null}|null
     */
    private function buildProductPostData(): ?array
    {
        if (!$this->getRequest()->is(['post', 'put', 'patch'])) {
            return null;
        }
        $d = $this->requestData();
        $p = [];
        if (!empty($d['Product']) && is_array($d['Product'])) {
            $p = $d['Product'];
        }
        if (!empty($d['data']['Product']) && is_array($d['data']['Product'])) {
            $p = array_merge($p, $d['data']['Product']);
        }
        $image = $this->nestedUploadedFileArray('data.Product.image');

        return [$p, $image];
    }

    /**
     * @param array<string, mixed>|null $image
     */
    private function moveProductImageUpload(?array $image, string $subdir): ?string
    {
        if ($image === null || empty($image['name'])) {
            return null;
        }
        $ext = substr(strtolower((string)strrchr($image['name'], '.')), 1);
        $allowed = ['jpg', 'jpeg', 'gif', 'png', 'webp', 'pdf'];
        if (!in_array($ext, $allowed, true)) {
            return null;
        }
        $tmp = $image['tmp_name'] ?? null;
        if (!is_string($tmp) || !is_file($tmp)) {
            return null;
        }
        $dir = WWW_ROOT . $subdir . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename((string)$image['name']));
        $dest = $dir . $safeName;
        $ok = is_uploaded_file($tmp) ? move_uploaded_file($tmp, $dest) : @rename($tmp, $dest);
        if (!$ok) {
            return null;
        }

        return $safeName;
    }

    /**
     * CakePHP 5 file upload or legacy $_FILES shape for data[Label][url] / data[Product][image].
     *
     * @return array{name?: string, tmp_name?: string}|null
     */
    private function nestedUploadedFileArray(string $dotPath): ?array
    {
        $file = $this->getRequest()->getUploadedFile($dotPath);
        if ($file instanceof UploadedFileInterface && $file->getError() === UPLOAD_ERR_OK) {
            $client = $file->getClientFilename();
            if ($client === null || $client === '') {
                return null;
            }
            $tmp = tempnam(sys_get_temp_dir(), 'up');
            if ($tmp === false) {
                return null;
            }
            $file->moveTo($tmp);

            return ['name' => $client, 'tmp_name' => $tmp];
        }

        $keys = explode('.', $dotPath);
        $cur = $_FILES;
        foreach ($keys as $k) {
            if (!isset($cur[$k])) {
                return null;
            }
            $cur = $cur[$k];
        }
        if (!is_array($cur) || ($cur['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        return ['name' => $cur['name'] ?? '', 'tmp_name' => $cur['tmp_name'] ?? ''];
    }
}
