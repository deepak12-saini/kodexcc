<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Admin package stock list at /admin/package-stocks (and legacy /admin/package_stocks).
 */
class PackageStocksController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Package Stock List');
        $this->checkAdminSession();

        $PackageStock = $this->PackageStock->find('all');
        $this->set('PackageStock', $PackageStock);
    }
}
