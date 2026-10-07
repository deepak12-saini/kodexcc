<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class MaintenanceController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'Maintenance');
        $status = (string)$this->request->getQuery('status');
        $query = $this->fetchTable('ItMaintenance')->find()->contain(['ItAssets', 'ItVendors']);
        if (in_array($status, self::MAINTENANCE_STATUSES, true)) {
            $query->where(['ItMaintenance.status' => $status]);
        }
        $this->hrPaginate($query, [
            'order' => ['ItMaintenance.scheduled_date' => 'DESC'],
            'sortableFields' => ['ItMaintenance.scheduled_date', 'ItMaintenance.status', 'ItMaintenance.id'],
        ]);
        $this->set(compact('status'));
        $this->set('maintenanceStatuses', self::MAINTENANCE_STATUSES);
    }

    public function add()
    {
        $this->set('pageTitle', 'Schedule Maintenance');
        $entity = $this->fetchTable('ItMaintenance')->newEmptyEntity();
        $assetId = (int)$this->request->getQuery('asset_id');
        if ($assetId) {
            $entity->asset_id = $assetId;
        }
        if ($this->request->is('post') && $this->saveRow($entity, true)) {
            return $this->redirect(['action' => 'index']);
        }
        $this->setLookups($entity);
        $this->render('form');
    }

    public function edit($id = null)
    {
        $entity = $this->fetchTable('ItMaintenance')->get($id);
        $this->set('pageTitle', 'Edit Maintenance');
        if ($this->request->is(['post', 'put', 'patch']) && $this->saveRow($entity, false)) {
            return $this->redirect(['action' => 'index']);
        }
        $this->setLookups($entity);
        $this->render('form');
    }

    private function saveRow($entity, bool $isNew): bool
    {
        $data = $this->clean($this->request->getData(), ['scheduled_date', 'completed_date'], ['cost']);
        if (empty($data['asset_id']) || trim((string)($data['maintenance_type'] ?? '')) === '') {
            $this->Flash->error('Asset and maintenance type are required.');

            return false;
        }
        $status = in_array($data['status'] ?? '', self::MAINTENANCE_STATUSES, true) ? $data['status'] : 'scheduled';
        if ($status === 'completed' && empty($data['completed_date'])) {
            $data['completed_date'] = date('Y-m-d');
        }
        $entity = $this->fetchTable('ItMaintenance')->patchEntity($entity, $data + [
            'status' => $status,
            'modified' => $this->now(),
            'created' => $isNew ? $this->now() : $entity->created,
        ]);
        if ($this->fetchTable('ItMaintenance')->save($entity)) {
            $this->recordAssetEvent((int)$entity->asset_id, 'maintenance', ($isNew ? 'Maintenance scheduled: ' : 'Maintenance updated: ') . $entity->maintenance_type, null, null, (string)($entity->notes ?? ''));
            $this->auditLog($isNew ? 'it_maint_create' : 'it_maint_update', 'it_maintenance', (string)$entity->maintenance_type, (int)$entity->id);
            $this->Flash->success('Maintenance saved.');

            return true;
        }
        $this->Flash->error('Could not save maintenance.');

        return false;
    }

    private function setLookups($entity): void
    {
        $assets = $this->assetOptions();
        $vendors = $this->vendorOptions();
        $maintenanceStatuses = self::MAINTENANCE_STATUSES;
        $this->set(compact('entity', 'assets', 'vendors', 'maintenanceStatuses'));
    }
}
