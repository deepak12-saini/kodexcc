<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class AssetsController extends ItController
{
    public function index()
    {
        $type = (string)$this->request->getQuery('type');
        $status = (string)$this->request->getQuery('status');
        $q = trim((string)$this->request->getQuery('q'));
        $this->set('pageTitle', self::ASSET_TYPES[$type] ?? 'Assets');
        $query = $this->fetchTable('ItAssets')->find()->contain(['ItVendors', 'ItAssetAssignments' => ['HrEmployees']]);
        if (isset(self::ASSET_TYPES[$type])) {
            $query->where(['ItAssets.asset_type' => $type]);
        }
        if (in_array($status, self::ASSET_STATUSES, true)) {
            $query->where(['ItAssets.status' => $status]);
        }
        if ($q !== '') {
            $query->where([
                'OR' => [
                    'ItAssets.asset_code LIKE' => '%' . $q . '%',
                    'ItAssets.brand LIKE' => '%' . $q . '%',
                    'ItAssets.model LIKE' => '%' . $q . '%',
                    'ItAssets.serial_number LIKE' => '%' . $q . '%',
                ],
            ]);
        }
        $this->hrPaginate($query, [
            'order' => ['ItAssets.id' => 'DESC'],
            'sortableFields' => ['ItAssets.asset_code', 'ItAssets.brand', 'ItAssets.status', 'ItAssets.asset_type'],
        ]);
        $this->set(compact('type', 'status', 'q'));
        $this->set('assetTypes', self::ASSET_TYPES);
        $this->set('assetStatuses', self::ASSET_STATUSES);
    }

    public function add()
    {
        $this->set('pageTitle', 'Add Asset');
        $table = $this->fetchTable('ItAssets');
        $entity = $table->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), ['purchase_date', 'warranty_until'], ['purchase_cost']);
            $type = (string)($data['asset_type'] ?? 'other');
            if (!isset(self::ASSET_TYPES[$type])) {
                $type = 'other';
            }
            if (empty($data['asset_code'])) {
                $data['asset_code'] = $this->nextCode('ItAssets', 'asset_code', 'IT-AST-');
            }
            if (!$this->serialFree((string)($data['serial_number'] ?? ''), 0)) {
                $this->Flash->error('That serial number is already registered.');
            } else {
                $entity = $table->patchEntity($entity, $data + [
                    'asset_type' => $type,
                    'status' => in_array($data['status'] ?? '', self::ASSET_STATUSES, true) ? $data['status'] : 'available',
                    'created' => $this->now(),
                    'modified' => $this->now(),
                ]);
                if ($table->save($entity)) {
                    $this->recordAssetEvent((int)$entity->id, 'created', 'Asset registered', null, (string)$entity->status);
                    $this->auditLog('it_asset_create', 'it_asset', 'Registered ' . $entity->asset_code, (int)$entity->id);
                    $this->Flash->success('Asset saved.');

                    return $this->redirect(['action' => 'view', $entity->id]);
                }
                $this->Flash->error('Could not save the asset.');
            }
        } else {
            $preset = (string)$this->request->getQuery('type');
            if (isset(self::ASSET_TYPES[$preset])) {
                $entity->asset_type = $preset;
            }
        }
        $this->setForm($entity);
    }

    public function edit($id = null)
    {
        $table = $this->fetchTable('ItAssets');
        $entity = $table->get($id);
        $this->set('pageTitle', 'Edit ' . $entity->asset_code);
        $oldStatus = (string)$entity->status;
        if ($this->request->is(['post', 'put', 'patch'])) {
            $data = $this->clean($this->request->getData(), ['purchase_date', 'warranty_until'], ['purchase_cost']);
            if (!$this->serialFree((string)($data['serial_number'] ?? ''), (int)$entity->id)) {
                $this->Flash->error('That serial number is already registered.');
            } else {
                $entity = $table->patchEntity($entity, $data + ['modified' => $this->now()]);
                if ($table->save($entity)) {
                    $summary = (string)$entity->status !== $oldStatus
                        ? 'Status changed from ' . $oldStatus . ' to ' . $entity->status
                        : 'Asset details updated';
                    $this->recordAssetEvent((int)$entity->id, 'updated', $summary, $oldStatus, (string)$entity->status);
                    if (in_array((string)$entity->status, ['disposed', 'lost'], true)) {
                        $this->closeOpenAssignment((int)$entity->id, 'Closed because asset was marked ' . $entity->status);
                    }
                    $this->auditLog('it_asset_update', 'it_asset', $summary, (int)$entity->id);
                    $this->Flash->success('Asset updated.');

                    return $this->redirect(['action' => 'view', $entity->id]);
                }
                $this->Flash->error('Could not update the asset.');
            }
        }
        $this->setForm($entity);
        $this->render('form');
    }

    public function view($id = null)
    {
        $asset = $this->fetchTable('ItAssets')->get($id, contain: [
            'ItVendors',
            'ItAssetEvents',
            'ItAssetAssignments' => ['HrEmployees'],
            'ItTickets',
            'ItRepairs' => ['ItVendors'],
            'ItMaintenance' => ['ItVendors'],
            'ItPurchaseRequests',
        ]);
        $this->set('pageTitle', $asset->asset_code);
        $events = $asset->it_asset_events ?? [];
        usort($events, fn($a, $b) => (int)$b->id <=> (int)$a->id);
        $assignments = $asset->it_asset_assignments ?? [];
        usort($assignments, fn($a, $b) => (int)$b->id <=> (int)$a->id);
        $this->set(compact('asset', 'events', 'assignments'));
    }

    private function setForm($entity): void
    {
        $vendors = $this->vendorOptions();
        $assetTypes = self::ASSET_TYPES;
        $assetStatuses = self::ASSET_STATUSES;
        $this->set(compact('entity', 'vendors', 'assetTypes', 'assetStatuses'));
        $this->render('form');
    }

    private function serialFree(string $serial, int $ignoreId): bool
    {
        $serial = trim($serial);
        if ($serial === '') {
            return true;
        }
        $q = $this->fetchTable('ItAssets')->find()->where(['serial_number' => $serial]);
        if ($ignoreId > 0) {
            $q->where(['id !=' => $ignoreId]);
        }

        return $q->count() === 0;
    }

    private function closeOpenAssignment(int $assetId, string $note): void
    {
        $row = $this->openAssignment($assetId);
        if (!$row) {
            return;
        }
        $row->status = 'returned';
        $row->return_date = date('Y-m-d');
        $row->notes = trim((string)$row->notes . "\n" . $note);
        $row->modified = $this->now();
        $this->fetchTable('ItAssetAssignments')->save($row);
    }
}
