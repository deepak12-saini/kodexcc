<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Admin production reports list (prefix /admin/production-reports).
 */
class ProductionReportsController extends AppController
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
        $this->set('title_for_layout', SITENAME . ' Production Report');
        $this->checkAdminSession();

        $rows = $this->ProductionReport->find('all', [
            'contain' => ['NappUser'],
            'order' => ['ProductionReport.id' => 'DESC'],
        ]);
        $this->normalizeNappUserRows($rows);
        $this->set('ProductionReport', $rows);
        $this->set('user_id', $this->Session->read('Customer.id'));
        $this->set('adminView', true);
    }

    /**
     * @param list<array<string, mixed>> $rows
     */
    private function normalizeNappUserRows(array &$rows): void
    {
        foreach ($rows as &$row) {
            $this->normalizeNappUserRow($row);
        }
        unset($row);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function normalizeNappUserRow(array &$row): void
    {
        if (!empty($row['ProductionReport']['napp_user'])) {
            $row['NappUser'] = $row['ProductionReport']['napp_user'];
            unset($row['ProductionReport']['napp_user']);
        }
    }
}
