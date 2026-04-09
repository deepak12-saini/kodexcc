<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Admin reward products (/admin/reward-products/...).
 */
class RewardProductsController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Reward Product');
        $this->checkAdminSession();

        $rows = $this->RewardProduct->find('all', [
            'contain' => ['Product'],
            'order' => ['RewardProduct.id' => 'DESC'],
        ]);
        $this->normalizeProductRows($rows);
        $this->set('RewardProduct', $rows);
    }

    public function add(): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Add Reward Product');
        $this->checkAdminSession();

        $product = $this->Product->find('all');
        $this->set('product', $product);

        $post = $this->legacyRewardProductPost();
        if ($post !== null) {
            $this->setRequestData($post);
            if ($this->RewardProduct->save($post)) {
                $this->Session->setFlash('The reward product has been saved', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash('The reward product could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        }

        return null;
    }

    public function edit(?string $id = null): ?Response
    {
        $this->viewBuilder()->setLayout('admin_layout');
        $this->set('title_for_layout', SITENAME . ' Edit Reward Product');
        $this->checkAdminSession();

        if ($id === null || $id === '') {
            throw new NotFoundException('Invalid reward product.');
        }

        $product = $this->Product->find('all');
        $this->set('product', $product);

        $rewardProduct = $this->RewardProduct->find('first', [
            'conditions' => ['RewardProduct.id' => $id],
            'contain' => ['Product'],
        ]);
        if (empty($rewardProduct['RewardProduct'])) {
            throw new NotFoundException('Reward product not found.');
        }
        $this->normalizeRewardProductRow($rewardProduct);
        $this->set('RewardProductArr', $rewardProduct);

        $post = $this->legacyRewardProductPost();
        if ($post !== null) {
            $post['RewardProduct']['id'] = $id;
            $this->setRequestData($post);
            if ($this->RewardProduct->save($post)) {
                $this->Session->setFlash('The reward product has been saved', 'default', ['class' => 'alert alert-success']);

                return $this->redirect(['action' => 'index']);
            }
            $this->Session->setFlash('The reward product could not be saved. Please, try again.', 'default', ['class' => 'alert alert-danger']);
        } else {
            $this->setRequestData(['RewardProduct' => $rewardProduct['RewardProduct']]);
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function legacyRewardProductPost(): ?array
    {
        if (!$this->getRequest()->is('post')) {
            return null;
        }
        $d = $this->requestData();
        if (isset($d['data']['RewardProduct'])) {
            $row = $d['data']['RewardProduct'];
            if (!is_array($row)) {
                return null;
            }
            return ['RewardProduct' => $this->normalizeRewardPostRow($row)];
        }
        if (isset($d['RewardProduct'])) {
            $row = $d['RewardProduct'];
            if (!is_array($row)) {
                return null;
            }
            return ['RewardProduct' => $this->normalizeRewardPostRow($row)];
        }

        return null;
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function normalizeProductRows(array &$rows): void
    {
        foreach ($rows as &$row) {
            $this->normalizeRewardProductRow($row);
        }
        unset($row);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function normalizeRewardProductRow(array &$row): void
    {
        if (!empty($row['RewardProduct']['product'])) {
            $row['Product'] = $row['RewardProduct']['product'];
            unset($row['RewardProduct']['product']);
        }
        // Support both DB variants: dealer_point and dealer_points.
        if (
            empty($row['RewardProduct']['dealer_point']) &&
            !empty($row['RewardProduct']['dealer_points'])
        ) {
            $row['RewardProduct']['dealer_point'] = $row['RewardProduct']['dealer_points'];
        }
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function normalizeRewardPostRow(array $row): array
    {
        $schema = $this->fetchTable('RewardProduct')->getSchema();

        // Normalize dealer points key name to whatever DB currently has.
        if (!isset($row['dealer_point']) && isset($row['dealer_points'])) {
            $row['dealer_point'] = $row['dealer_points'];
        }
        if (!isset($row['dealer_points']) && isset($row['dealer_point'])) {
            $row['dealer_points'] = $row['dealer_point'];
        }
        if ($schema->hasColumn('dealer_points') && !$schema->hasColumn('dealer_point')) {
            $row['dealer_points'] = $row['dealer_point'] ?? $row['dealer_points'] ?? null;
            unset($row['dealer_point']);
        } elseif ($schema->hasColumn('dealer_point') && !$schema->hasColumn('dealer_points')) {
            $row['dealer_point'] = $row['dealer_point'] ?? $row['dealer_points'] ?? null;
            unset($row['dealer_points']);
        }

        return $row;
    }
}
