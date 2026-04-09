<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\ORM\TableRegistry;
use Cake\View\View;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/5/en/views.html#the-app-view
 * @extends \Cake\View\View<\App\View\AppView>
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like adding helpers.
     *
     * e.g. `$this->addHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->loadHelper('Flash');
        $this->loadHelper('Session');
        $this->loadHelper('params');
    }

    /**
     * Legacy CakePHP 2.x compatibility shim for templates still using requestAction().
     */
    public function requestAction(string $path): mixed
    {
        $path = strtolower(trim($path));

        return match ($path) {
            '/app/getcate' => $this->legacyGetCate(),
            '/app/chkuserpermission' => $this->legacyChkUserPermission(),
            '/app/getproducttype' => $this->legacyGetProductType(1),
            '/app/getproductbyuse' => $this->legacyGetProductType(2),
            '/app/getcarttotal' => $this->legacyGetCartTotal(),
            '/app/getproduct' => $this->legacyGetProduct(),
            default => null,
        };
    }

    private function legacyGetCate(): array
    {
        $categoryTable = TableRegistry::getTableLocator()->get('Category');
        $rows = $categoryTable->find()
            ->where(['Category.status' => 1])
            ->contain(['Product' => function ($q) {
                return $q->where(['Product.status' => 1]);
            }])
            ->enableHydration(false)
            ->toArray();

        $out = [];
        foreach ($rows as $row) {
            $out[] = [
                'Category' => $row,
                'Product' => $row['product'] ?? [],
            ];
        }

        return $out;
    }

    private function legacyChkUserPermission(): array
    {
        $userId = $this->getRequest()->getSession()->read('Customer.id');
        if (!$userId) {
            return [];
        }

        $table = TableRegistry::getTableLocator()->get('UserPermission');
        return $table->find('list', [
            'conditions' => ['UserPermission.user_id' => $userId],
            'valueField' => 'permssion_id',
        ])->toArray();
    }

    private function legacyGetProductType(int $type): array
    {
        $table = TableRegistry::getTableLocator()->get('Product');
        $rows = $table->find()
            ->where(['Product.product_type' => $type])
            ->select(['title', 'slug'])
            ->enableHydration(false)
            ->toArray();

        return array_map(fn($row) => ['Product' => $row], $rows);
    }

    private function legacyGetCartTotal(): int
    {
        $sessionId = $this->getRequest()->getSession()->read('cart_session_id');
        if (!$sessionId) {
            return 0;
        }

        $table = TableRegistry::getTableLocator()->get('Cart');
        return $table->find()->where(['Cart.cart_session_id' => $sessionId])->count();
    }

    private function legacyGetProduct(): array
    {
        $table = TableRegistry::getTableLocator()->get('Product');
        $rows = $table->find()
            ->select(['title'])
            ->enableHydration(false)
            ->toArray();

        return array_map(fn($row) => ['Product' => $row], $rows);
    }
}
