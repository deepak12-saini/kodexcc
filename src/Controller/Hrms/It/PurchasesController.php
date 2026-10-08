<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class PurchasesController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'Purchase Requests');
        $status = (string)$this->request->getQuery('status');
        $query = $this->fetchTable('ItPurchaseRequests')->find()->contain(['Requesters', 'HrDepartments', 'ItVendors']);
        if ($status === 'pending') {
            $query->where(['ItPurchaseRequests.approval_status IN' => ['requested', 'quotation', 'approved', 'purchased']]);
        } elseif (in_array($status, self::PURCHASE_STATUSES, true)) {
            $query->where(['ItPurchaseRequests.approval_status' => $status]);
        }
        $this->hrPaginate($query, [
            'order' => ['ItPurchaseRequests.id' => 'DESC'],
            'sortableFields' => ['ItPurchaseRequests.request_no', 'ItPurchaseRequests.approval_status', 'ItPurchaseRequests.id'],
        ]);
        $this->set(compact('status'));
        $this->set('purchaseStatuses', self::PURCHASE_STATUSES);
    }

    public function add()
    {
        $this->set('pageTitle', 'New Purchase Request');
        $entity = $this->fetchTable('ItPurchaseRequests')->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), [], ['estimated_cost']);
            if (trim((string)($data['item_name'] ?? '')) === '') {
                $this->Flash->error('Item name is required.');
            } else {
                $entity = $this->fetchTable('ItPurchaseRequests')->patchEntity($entity, $data + [
                    'request_no' => $this->nextCode('ItPurchaseRequests', 'request_no', 'IT-PR-'),
                    'quantity' => max(1, (int)($data['quantity'] ?? 1)),
                    'approval_status' => 'requested',
                    'created' => $this->now(),
                    'modified' => $this->now(),
                ]);
                if ($this->fetchTable('ItPurchaseRequests')->save($entity)) {
                    $this->auditLog('it_purchase_create', 'it_purchase', 'Requested ' . $entity->request_no, (int)$entity->id, $entity->requested_by ? (int)$entity->requested_by : null);
                    $this->Flash->success('Request ' . $entity->request_no . ' created.');

                    return $this->redirect(['action' => 'view', $entity->id]);
                }
                $this->Flash->error('Could not save the request.');
            }
        }
        $this->setLookups($entity);
        $this->render('form');
    }

    public function view($id = null)
    {
        $purchase = $this->fetchTable('ItPurchaseRequests')->get($id, contain: [
            'Requesters',
            'HrDepartments',
            'ItVendors',
            'Approvers' => ['HrEmployees'],
            'ItAssets',
        ]);
        $this->set('pageTitle', $purchase->request_no);
        $vendors = $this->vendorOptions();
        $this->set(compact('purchase', 'vendors'));
    }

    public function quotation($id = null)
    {
        $this->request->allowMethod(['post']);
        $row = $this->fetchTable('ItPurchaseRequests')->get($id);
        if (!in_array((string)$row->approval_status, ['requested', 'quotation'], true)) {
            $this->Flash->error('A quotation cannot be added at this stage.');

            return $this->redirect(['action' => 'view', $id]);
        }
        $data = $this->clean($this->request->getData(), [], ['estimated_cost']);
        $file = $this->storeUpload($this->request->getData('quotation_file'), 'quotations');
        $row->vendor_id = $data['vendor_id'] ?? $row->vendor_id;
        $row->quotation_notes = $data['quotation_notes'] ?? $row->quotation_notes;
        if ($file) {
            $row->quotation_file = $file;
        }
        if (array_key_exists('estimated_cost', $data) && $data['estimated_cost'] !== null) {
            $row->estimated_cost = $data['estimated_cost'];
        }
        $row->approval_status = 'quotation';
        $row->modified = $this->now();
        $this->fetchTable('ItPurchaseRequests')->save($row);
        $this->auditLog('it_purchase_quote', 'it_purchase', $row->request_no . ' quotation recorded', (int)$row->id);
        $this->Flash->success('Quotation recorded.');

        return $this->redirect(['action' => 'view', $id]);
    }

    public function decide($id = null)
    {
        $this->request->allowMethod(['post']);
        $row = $this->fetchTable('ItPurchaseRequests')->get($id);
        $decision = (string)$this->request->getData('decision');
        if (!in_array((string)$row->approval_status, ['requested', 'quotation'], true) || !in_array($decision, ['approved', 'rejected'], true)) {
            $this->Flash->error('This request cannot be decided now.');

            return $this->redirect(['action' => 'view', $id]);
        }
        $row->approval_status = $decision;
        $row->approved_by = (int)($this->Session->read('HrUser.id') ?: 0) ?: null;
        $row->approved_at = $this->now();
        $row->notes = trim((string)$row->notes . (empty($this->request->getData('notes')) ? '' : "\n" . $this->request->getData('notes')));
        $row->modified = $this->now();
        $this->fetchTable('ItPurchaseRequests')->save($row);
        $this->auditLog('it_purchase_decide', 'it_purchase', $row->request_no . ' ' . $decision, (int)$row->id);
        $this->Flash->success('Request ' . $decision . '.');

        return $this->redirect(['action' => 'view', $id]);
    }

    public function purchase($id = null)
    {
        $this->request->allowMethod(['post']);
        $row = $this->fetchTable('ItPurchaseRequests')->get($id);
        if ((string)$row->approval_status !== 'approved') {
            $this->Flash->error('Approve the request before marking it purchased.');

            return $this->redirect(['action' => 'view', $id]);
        }
        $data = $this->clean($this->request->getData(), ['purchase_date'], ['actual_cost']);
        $file = $this->storeUpload($this->request->getData('invoice_file'), 'invoices');
        $row->purchase_date = $data['purchase_date'] ?: date('Y-m-d');
        $row->actual_cost = $data['actual_cost'] ?? $row->estimated_cost;
        $row->invoice_no = $data['invoice_no'] ?? null;
        $row->vendor_id = $data['vendor_id'] ?? $row->vendor_id;
        if ($file) {
            $row->invoice_file = $file;
        }
        $row->approval_status = 'purchased';
        $row->modified = $this->now();
        $this->fetchTable('ItPurchaseRequests')->save($row);
        $this->auditLog('it_purchase_buy', 'it_purchase', $row->request_no . ' purchased', (int)$row->id);
        $this->Flash->success('Marked as purchased.');

        return $this->redirect(['action' => 'view', $id]);
    }

    public function receive($id = null)
    {
        $this->request->allowMethod(['post']);
        $row = $this->fetchTable('ItPurchaseRequests')->get($id);
        if ((string)$row->approval_status !== 'purchased') {
            $this->Flash->error('Only a purchased request can be marked received.');

            return $this->redirect(['action' => 'view', $id]);
        }
        $row->approval_status = 'received';
        $row->modified = $this->now();
        $this->fetchTable('ItPurchaseRequests')->save($row);
        $this->auditLog('it_purchase_receive', 'it_purchase', $row->request_no . ' received', (int)$row->id);
        $this->Flash->success('Marked as received. You can create the asset now.');

        return $this->redirect(['action' => 'view', $id]);
    }

    public function createAsset($id = null)
    {
        $purchase = $this->fetchTable('ItPurchaseRequests')->get($id);
        $this->set('pageTitle', 'Create asset from ' . $purchase->request_no);
        if (!in_array((string)$purchase->approval_status, ['received', 'asset_created'], true)) {
            $this->Flash->error('Receive the equipment before creating an asset.');

            return $this->redirect(['action' => 'view', $id]);
        }
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), ['purchase_date', 'warranty_until'], ['purchase_cost']);
            $qty = max(1, min(20, (int)$purchase->quantity));
            $type = isset(self::ASSET_TYPES[$data['asset_type'] ?? '']) ? $data['asset_type'] : 'other';
            $serial = trim((string)($data['serial_number'] ?? ''));
            if ($serial !== '' && $this->fetchTable('ItAssets')->exists(['serial_number' => $serial])) {
                $this->Flash->error('That serial number is already registered.');
            } else {
                $firstId = null;
                for ($i = 1; $i <= $qty; $i++) {
                    $asset = $this->fetchTable('ItAssets')->newEntity([
                        'asset_code' => $this->nextCode('ItAssets', 'asset_code', 'IT-AST-'),
                        'asset_type' => $type,
                        'brand' => $data['brand'] ?? null,
                        'model' => $data['model'] ?? $purchase->item_name,
                        'serial_number' => $i === 1 ? ($serial !== '' ? $serial : null) : null,
                        'processor' => $data['processor'] ?? null,
                        'ram' => $data['ram'] ?? null,
                        'storage' => $data['storage'] ?? null,
                        'purchase_date' => $data['purchase_date'] ?? $purchase->purchase_date,
                        'purchase_cost' => $data['purchase_cost'] ?? $purchase->actual_cost,
                        'vendor_id' => $purchase->vendor_id,
                        'warranty_until' => $data['warranty_until'] ?? null,
                        'warranty_notes' => $data['warranty_notes'] ?? null,
                        'location' => $data['location'] ?? null,
                        'status' => 'available',
                        'notes' => 'Created from ' . $purchase->request_no . ($qty > 1 ? ' (unit ' . $i . ' of ' . $qty . ')' : ''),
                        'purchase_request_id' => $purchase->id,
                        'created' => $this->now(),
                        'modified' => $this->now(),
                    ]);
                    $this->fetchTable('ItAssets')->save($asset);
                    $this->recordAssetEvent((int)$asset->id, 'created', 'Created from purchase ' . $purchase->request_no, null, 'available');
                    $firstId = $firstId ?: (int)$asset->id;
                }
                $purchase->asset_id = $firstId;
                $purchase->approval_status = 'asset_created';
                $purchase->modified = $this->now();
                $this->fetchTable('ItPurchaseRequests')->save($purchase);
                $this->auditLog('it_purchase_asset', 'it_purchase', 'Assets created from ' . $purchase->request_no, (int)$purchase->id);
                $this->Flash->success($qty . ' asset' . ($qty > 1 ? 's' : '') . ' created and marked Available.');

                return $this->redirect(['controller' => 'Assets', 'action' => 'view', $firstId]);
            }
        }
        $assetTypes = self::ASSET_TYPES;
        $this->set(compact('purchase', 'assetTypes'));
    }

    private function setLookups($entity): void
    {
        $employees = $this->employeeOptions();
        $departments = $this->departmentOptions();
        $vendors = $this->vendorOptions();
        $this->set(compact('entity', 'employees', 'departments', 'vendors'));
    }
}
