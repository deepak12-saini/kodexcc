<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class RepairsController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'Repairs & Service');
        $status = (string)$this->request->getQuery('status');
        $query = $this->fetchTable('ItRepairs')->find()->contain(['ItAssets', 'ItVendors', 'ItTickets']);
        if (in_array($status, self::REPAIR_STATUSES, true)) {
            $query->where(['ItRepairs.status' => $status]);
        }
        $this->hrPaginate($query, [
            'order' => ['ItRepairs.id' => 'DESC'],
            'sortableFields' => ['ItRepairs.repair_no', 'ItRepairs.status', 'ItRepairs.sent_date'],
        ]);
        $this->set(compact('status'));
        $this->set('repairStatuses', self::REPAIR_STATUSES);
    }

    public function add()
    {
        $this->set('pageTitle', 'New Repair');
        $entity = $this->fetchTable('ItRepairs')->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), ['sent_date', 'expected_return_date', 'returned_date'], ['estimated_cost', 'actual_cost']);
            if (empty($data['asset_id'])) {
                $this->Flash->error('Choose an asset.');
            } else {
                $entity = $this->fetchTable('ItRepairs')->patchEntity($entity, $data + [
                    'repair_no' => $this->nextCode('ItRepairs', 'repair_no', 'IT-RPR-'),
                    'status' => in_array($data['status'] ?? '', self::REPAIR_STATUSES, true) ? $data['status'] : 'pending',
                    'under_warranty' => !empty($data['under_warranty']) ? 1 : 0,
                    'created' => $this->now(),
                    'modified' => $this->now(),
                ]);
                if ($this->fetchTable('ItRepairs')->save($entity)) {
                    $this->syncAsset((int)$entity->asset_id, (string)$entity->status, (string)$entity->repair_no);
                    $this->saveFile((int)$entity->id);
                    $this->auditLog('it_repair_create', 'it_repair', 'Opened ' . $entity->repair_no, (int)$entity->id);
                    $this->Flash->success('Repair ' . $entity->repair_no . ' saved.');

                    return $this->redirect(['action' => 'view', $entity->id]);
                }
                $this->Flash->error('Could not save the repair.');
            }
        } else {
            $assetId = (int)$this->request->getQuery('asset_id');
            $ticketId = (int)$this->request->getQuery('ticket_id');
            if ($assetId) {
                $entity->asset_id = $assetId;
            }
            if ($ticketId) {
                $entity->ticket_id = $ticketId;
                $ticket = $this->fetchTable('ItTickets')->find()->where(['id' => $ticketId])->first();
                if ($ticket && $ticket->asset_id) {
                    $entity->asset_id = $ticket->asset_id;
                }
                if ($ticket) {
                    $entity->problem = $ticket->problem;
                }
            }
        }
        $this->setLookups($entity);
        $this->render('form');
    }

    public function view($id = null)
    {
        $repair = $this->fetchTable('ItRepairs')->get($id, contain: ['ItAssets', 'ItVendors', 'ItTickets', 'ItRepairFiles']);
        $this->set('pageTitle', $repair->repair_no);
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), ['sent_date', 'expected_return_date', 'returned_date'], ['estimated_cost', 'actual_cost']);
            $old = (string)$repair->status;
            $repair = $this->fetchTable('ItRepairs')->patchEntity($repair, $data + [
                'under_warranty' => !empty($data['under_warranty']) ? 1 : 0,
                'modified' => $this->now(),
            ]);
            if (!in_array((string)$repair->status, self::REPAIR_STATUSES, true)) {
                $repair->status = $old;
            }
            if ($this->fetchTable('ItRepairs')->save($repair)) {
                if ((string)$repair->status !== $old) {
                    $this->syncAsset((int)$repair->asset_id, (string)$repair->status, (string)$repair->repair_no);
                }
                $this->saveFile((int)$repair->id);
                $this->auditLog('it_repair_update', 'it_repair', $repair->repair_no . ' → ' . $repair->status, (int)$repair->id);
                $this->Flash->success('Repair updated.');

                return $this->redirect(['action' => 'view', $repair->id]);
            }
            $this->Flash->error('Could not update the repair.');
        }
        $vendors = $this->vendorOptions();
        $repairStatuses = self::REPAIR_STATUSES;
        $this->set(compact('repair', 'vendors', 'repairStatuses'));
    }

    private function setLookups($entity): void
    {
        $assets = $this->assetOptions();
        $vendors = $this->vendorOptions();
        $tickets = $this->fetchTable('ItTickets')->find('list', [
            'keyField' => 'id',
            'valueField' => 'ticket_no',
        ])->orderBy(['id' => 'DESC'])->limit(200)->toArray();
        $repairStatuses = self::REPAIR_STATUSES;
        $this->set(compact('entity', 'assets', 'vendors', 'tickets', 'repairStatuses'));
    }

    private function saveFile(int $repairId): void
    {
        $path = $this->storeUpload($this->request->getData('attachment'), 'repairs');
        if ($path === null) {
            return;
        }
        $this->fetchTable('ItRepairFiles')->save(
            $this->fetchTable('ItRepairFiles')->newEntity([
                'repair_id' => $repairId,
                'title' => trim((string)$this->request->getData('attachment_title')) ?: 'Attachment',
                'file_path' => $path,
                'uploaded_by' => (int)($this->Session->read('HrUser.id') ?: 0) ?: null,
                'created' => $this->now(),
            ])
        );
    }

    private function syncAsset(int $assetId, string $repairStatus, string $repairNo): void
    {
        $asset = $this->fetchTable('ItAssets')->get($assetId);
        $open = $this->openAssignment($assetId);
        $from = (string)$asset->status;
        $to = $from;
        if (in_array($repairStatus, ['sent_to_vendor', 'under_repair', 'waiting_for_parts'], true)) {
            $to = 'under_repair';
        } elseif (in_array($repairStatus, ['completed', 'returned'], true)) {
            $to = $open ? 'assigned' : 'available';
        } elseif ($repairStatus === 'cancelled' && $from === 'under_repair') {
            $to = $open ? 'assigned' : 'faulty';
        }
        if ($to === $from) {
            $this->recordAssetEvent($assetId, 'repair', 'Repair ' . $repairNo . ' is ' . str_replace('_', ' ', $repairStatus), $from, $from);

            return;
        }
        $asset->status = $to;
        $asset->modified = $this->now();
        $this->fetchTable('ItAssets')->save($asset);
        $this->recordAssetEvent($assetId, 'repair', 'Repair ' . $repairNo . ' moved asset to ' . str_replace('_', ' ', $to), $from, $to);
    }
}
