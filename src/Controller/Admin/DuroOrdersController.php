<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;

/**
 * Admin Duro Orders: /admin/duro-orders/...
 */
class DuroOrdersController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Duro Orders');
        $this->checkAdminSession();

        $this->paginate = [
            'limit' => 10,
            'order' => ['DuroOrder.id' => 'DESC'],
            'sortableFields' => [
                'DuroOrder.customer_order_no',
                'DuroOrder.date_of_order',
                'DuroOrder.contact_name',
                'DuroOrder.contact_phone',
                'DuroOrder.status',
                'DuroOrder.created',
            ],
        ];

        $query = $this->fetchTable('DuroOrder')->find()
            ->contain(['NappUser', 'OrderProduct']);

        $page = $this->paginate($query);
        $this->set('duroOrdersPaginated', $page);

        $rows = [];
        foreach ($page as $entity) {
            if (!$entity instanceof EntityInterface) {
                continue;
            }
            $d = $entity->toArray();
            $u = [];
            $p = [];
            if (!empty($d['napp_user']) && is_array($d['napp_user'])) {
                $u = $d['napp_user'];
            }
            if (!empty($d['order_product']) && is_array($d['order_product'])) {
                $p = $d['order_product'];
            }
            unset($d['napp_user'], $d['order_product']);
            $rows[] = [
                'DuroOrder' => $d,
                'NappUser' => $u,
                'OrderProduct' => $p,
            ];
        }
        $this->set('DuroOrderArr', $rows);
    }

    public function feedback(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Sample Feedback');
        $this->checkAdminSession();

        $this->paginate = [
            'limit' => 10,
            'order' => ['Feedback.id' => 'DESC'],
            'sortableFields' => [
                'Feedback.addedby',
                'Feedback.customer_name',
                'Feedback.company_name',
                'Feedback.contact',
                'Feedback.sample_given',
                'Feedback.created',
            ],
        ];
        $query = $this->fetchTable('Feedback')->find();
        $page = $this->paginate($query);
        $this->set('feedbackPaginated', $page);

        $rows = [];
        foreach ($page as $entity) {
            if (!$entity instanceof EntityInterface) {
                continue;
            }
            $rows[] = ['Feedback' => $entity->toArray()];
        }
        $this->set('FeedbackArr', $rows);
    }

    public function userreward(): void
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Reward Points');
        $this->checkAdminSession();

        $this->paginate = [
            'limit' => 10,
            'order' => ['RewardPoint.id' => 'DESC'],
            'sortableFields' => [
                'RewardPoint.contact_name',
                'RewardPoint.contact_phone',
                'RewardPoint.address',
                'RewardPoint.points',
                'RewardPoint.redeem',
                'RewardPoint.created',
            ],
        ];
        $query = $this->fetchTable('RewardPoint')->find();
        $page = $this->paginate($query);
        $this->set('rewardPointsPaginated', $page);

        $rows = [];
        foreach ($page as $entity) {
            if (!$entity instanceof EntityInterface) {
                continue;
            }
            $rows[] = ['RewardPoint' => $entity->toArray()];
        }
        $this->set('DuroOrderArr', $rows);
    }
}

