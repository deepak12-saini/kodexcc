<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use App\Controller\Concerns\PurchaseLegacyTrait;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

/**
 * Admin purchase routes: /admin/purchases/...
 */
class PurchasesController extends AppController
{
    use PurchaseLegacyTrait;

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

    public function autologin(): void
    {
        $this->autoRender = false;

        $admin_arr = $this->User->find('first');

        if (!empty($admin_arr)) {
            $this->Session->write('User', $admin_arr['User']);
            $this->Session->write('is_admin', 1);
            $this->redirect('/admin/purchases');
        }
    }

    public function index(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Purchases List');
        $this->checkAdminSession();

        $contain = ['NappUser', 'NappUser1', 'NappUser2', 'PurchaseRequirements'];
        $query = $this->fetchTable('Purchase')->find()
            ->contain($contain)
            ->where(['Purchase.purchase_type' => 0])
            ->orderBy(['Purchase.id' => 'DESC']);

        $this->paginate = [
            'limit' => 25,
            'order' => ['Purchase.id' => 'DESC'],
            'sortableFields' => [
                'Purchase.unique_id',
                'Purchase.item_details',
                'Purchase.requisitioner_name',
                'Purchase.date',
                'Purchase.prepared_by',
                'Purchase.permitted_by',
                'Purchase.authorized_by',
                'Purchase.name_of_receiver',
                'Purchase.status',
                'Purchase.created',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('purchaseArr', $this->mapPurchasePageToLegacy($page));
        // CakePHP 5 PaginatorHelper requires a PaginatedInterface on a view var (see PaginatorHelper::paginated()).
        $this->set('purchasesPaginated', $page);
    }

    public function resourceRequirement(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Resource Requirement List');
        $this->checkAdminSession();

        $contain = ['NappUser', 'NappUser1', 'NappUser2', 'PurchaseRequirements'];
        $query = $this->fetchTable('Purchase')->find()
            ->contain($contain)
            ->where(['Purchase.purchase_type' => 1])
            ->orderBy(['Purchase.id' => 'DESC']);

        $this->paginate = [
            'limit' => 25,
            'order' => ['Purchase.id' => 'DESC'],
            'sortableFields' => [
                'Purchase.unique_id',
                'Purchase.item_details',
                'Purchase.requisitioner_name',
                'Purchase.date',
                'Purchase.status',
                'Purchase.created',
            ],
        ];
        $page = $this->paginate($query);
        $this->set('purchaseArr', $this->mapPurchasePageToLegacy($page));
        $this->set('purchasesPaginated', $page);
    }

    public function received(?string $id = null): void
    {
        $this->checkAdminSession();
        $this->autoRender = false;
        $sendername = 'Raghujeet';

        $contain = ['NappUser', 'NappUser1', 'NappUser2', 'PurchaseRequirements'];
        $entity = $this->fetchTable('Purchase')->find()
            ->contain($contain)
            ->where(['Purchase.id' => $id])
            ->first();

        if ($entity === null) {
            $this->redirect(['action' => 'index']);

            return;
        }

        $PurchaseArr = $this->purchaseEntityToLegacyArray($entity);

        if ($PurchaseArr !== []) {
            $update = [
                'Purchase' => [
                    'id' => $id,
                    'status' => 2,
                    'name_of_receiver' => $sendername,
                ],
            ];
            $this->Purchase->save($update);

            $unique_id = $PurchaseArr['Purchase']['unique_id'] ?? '';
            $item_details = $PurchaseArr['Purchase']['item_details'] ?? '';
            $subject = SITENAME . ' :- Purchase Request(' . $unique_id . ') has been closed by ' . $sendername;

            if (!empty($PurchaseArr['NappUser_1']['email'])) {
                $id_2 = $PurchaseArr['NappUser_1']['id'];
                $email_2 = $PurchaseArr['NappUser_1']['email'];
                $name_2 = $PurchaseArr['NappUser_1']['name'] . ' ' . $PurchaseArr['NappUser_1']['lname'];
                $url = SITEURL . 'purchases/autologin/' . base64_encode((string)$id_2);
                $template_name = 'closerequest';
                $variables = ['unique_id' => $unique_id, 'sendername' => $sendername, 'receivername' => $name_2, 'url' => $url, 'item_details' => $item_details];
                try {
                    $this->sendemail($email_2, $subject, $template_name, $variables);
                } catch (\Exception $e) {
                }
            }

            if (!empty($PurchaseArr['NappUser']['email'])) {
                $id_1 = $PurchaseArr['NappUser']['id'];
                $email_1 = $PurchaseArr['NappUser']['email'];
                $name_1 = $PurchaseArr['NappUser']['name'] . ' ' . $PurchaseArr['NappUser']['lname'];
                $url = SITEURL . 'purchases/autologin/' . base64_encode((string)$id_1);
                $template_name = 'closerequest';
                $variables = ['unique_id' => $unique_id, 'sendername' => $sendername, 'receivername' => $name_1, 'url' => $url, 'item_details' => $item_details];
                try {
                    $this->sendemail($email_1, $subject, $template_name, $variables);
                } catch (\Exception $e) {
                }
            }

            $authorized_by = (int)($PurchaseArr['Purchase']['authorized_by'] ?? 0);
            if ($authorized_by > 0) {
                $NappUser = $this->NappUser->find('first', ['conditions' => ['NappUser.id' => $authorized_by]]);
                if (!empty($NappUser)) {
                    $id_3 = $NappUser['NappUser']['id'];
                    $email_3 = $NappUser['NappUser']['email'];
                    $name_3 = $NappUser['NappUser']['name'] . ' ' . $NappUser['NappUser']['lname'];
                    $url = SITEURL . 'purchases/autologin/' . base64_encode((string)$id_3);
                    $template_name = 'closerequest';
                    $variables = ['unique_id' => $unique_id, 'sendername' => $sendername, 'receivername' => $name_3, 'url' => $url, 'item_details' => $item_details];
                    try {
                        $this->sendemail($email_3, $subject, $template_name, $variables);
                    } catch (\Exception $e) {
                    }
                }
            }

            $this->Session->setFlash('Received successfully.', 'default', ['class' => 'alert alert-success']);
            $this->redirect(['action' => 'index']);

            return;
        }
        $this->redirect(['action' => 'index']);
    }

    public function edit(?string $id = null): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add Purchase Request');
        $this->checkAdminSession();
        $sendername = 'Raghu';

        if (!empty($this->requestData())) {
            $data = $this->requestData();
            $data['Purchase']['id'] = $id;
            $data['Purchase']['created'] = date('Y-m-d H:i:s');
            $this->setRequestData($data);
            if ($this->Purchase->save($data)) {
                if (($data['Purchase']['status'] ?? null) == 1) {
                    $PurchaseArr = $this->Purchase->find('first', ['conditions' => ['Purchase.id' => $id]]);
                    $unique_id = $PurchaseArr['Purchase']['unique_id'] ?? '';
                    $item_details = $PurchaseArr['Purchase']['item_details'] ?? '';
                    $subject = SITENAME . ':- Purchase Request(' . $unique_id . ') has been approved by ' . $sendername;
                    $StockReturnkArr = $this->UserPermission->find('all', ['conditions' => ['UserPermission.permssion_id' => 30]]);

                    if (!empty($StockReturnkArr)) {
                        foreach ($StockReturnkArr as $StockReturnkArrs) {
                            $id_2 = $StockReturnkArrs['NappUser']['id'] ?? null;
                            $email_2 = $StockReturnkArrs['NappUser']['email'] ?? null;
                            $name_2 = ($StockReturnkArrs['NappUser']['name'] ?? '') . ' ' . ($StockReturnkArrs['NappUser']['lname'] ?? '');
                            if ($id_2 && $email_2) {
                                $url = SITEURL . 'purchases/autologin/' . base64_encode((string)$id_2);
                                $template_name = 'requestapproved';
                                $variables = ['unique_id' => $unique_id, 'sendername' => $sendername, 'receivername' => $name_2, 'url' => $url, 'item_details' => $item_details];
                                try {
                                    $this->sendemail($email_2, $subject, $template_name, $variables);
                                } catch (\Exception $e) {
                                }
                            }
                        }
                    }
                }
                $this->PurchaseRequirement->query('delete from purchase_requirements where purchase_id = ' . $id . ' ');
                if (!empty($data['item_name'])) {
                    $i = 0;
                    foreach ($data['item_name'] as $item_name) {
                        $PurchaseRequirement = [];
                        $PurchaseRequirement['PurchaseRequirement']['id'] = '';
                        $PurchaseRequirement['PurchaseRequirement']['purchase_id'] = $id;
                        $PurchaseRequirement['PurchaseRequirement']['item_name'] = $item_name;
                        $PurchaseRequirement['PurchaseRequirement']['comments'] = $data['comments'][$i] ?? '';
                        $PurchaseRequirement['PurchaseRequirement']['quantity'] = $data['quantity'][$i] ?? '';
                        $PurchaseRequirement['PurchaseRequirement']['description_item'] = $data['description_item'][$i] ?? '';
                        $this->PurchaseRequirement->save($PurchaseRequirement);
                        $i++;
                    }
                }

                $this->Session->setFlash('Request has been created successfully.', 'default', ['class' => 'alert alert-success']);
                $this->redirect(['action' => 'index']);

                return;
            }
        }

        $user_id = $this->UserPermission->find('list', ['conditions' => ['UserPermission.permssion_id' => 30], 'fields' => ['user_id']]);
        $employeArr = $this->NappUser->find('all', ['conditions' => ['NappUser.is_staff_id' => 1, 'id' => $user_id]]);
        $this->set('employeArr', $employeArr);

        $contain = ['PurchaseRequirements'];
        $entity = $this->fetchTable('Purchase')->find()
            ->contain($contain)
            ->where(['Purchase.id' => $id])
            ->first();

        $PurchaseArr = $entity instanceof EntityInterface ? $this->purchaseEntityToLegacyArray($entity) : [];
        $this->set('PurchaseArr', $PurchaseArr);
        $this->setRequestData($PurchaseArr);
    }
}
