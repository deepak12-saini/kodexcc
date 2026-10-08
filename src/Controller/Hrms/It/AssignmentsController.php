<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

class AssignmentsController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'Employee Assignments');
        $q = trim((string)$this->request->getQuery('q'));
        $status = (string)$this->request->getQuery('status');
        $type = (string)$this->request->getQuery('type');
        $query = $this->fetchTable('ItAssetAssignments')->find()
            ->contain(['ItAssets', 'HrEmployees']);
        if (in_array($status, ['assigned', 'returned', 'transferred'], true)) {
            $query->where(['ItAssetAssignments.status' => $status]);
        }
        if (isset(self::ASSET_TYPES[$type])) {
            $query->where(['ItAssets.asset_type' => $type]);
        }
        if ($q !== '') {
            $query->leftJoinWith('ItAssets')->leftJoinWith('HrEmployees')->where([
                'OR' => [
                    'ItAssets.asset_code LIKE' => '%' . $q . '%',
                    'HrEmployees.full_name LIKE' => '%' . $q . '%',
                    'HrEmployees.employee_code LIKE' => '%' . $q . '%',
                ],
            ])->distinct(['ItAssetAssignments.id']);
        }
        $this->hrPaginate($query, [
            'order' => ['ItAssetAssignments.id' => 'DESC'],
            'sortableFields' => ['ItAssetAssignments.assigned_date', 'ItAssetAssignments.status', 'ItAssetAssignments.id'],
        ]);
        $assetTypes = self::ASSET_TYPES;
        $this->set(compact('q', 'status', 'type', 'assetTypes'));
    }

    public function assign($assetId = null)
    {
        $asset = $this->fetchTable('ItAssets')->get($assetId);
        $this->set('pageTitle', 'Assign ' . $asset->asset_code);
        if (in_array((string)$asset->status, ['disposed', 'lost', 'under_repair'], true)) {
            $this->Flash->error('This asset cannot be assigned while it is ' . str_replace('_', ' ', (string)$asset->status) . '.');

            return $this->redirect(['controller' => 'Assets', 'action' => 'view', $asset->id]);
        }
        if ($this->openAssignment((int)$asset->id)) {
            $this->Flash->error('This asset is already assigned. Transfer or return it first.');

            return $this->redirect(['controller' => 'Assets', 'action' => 'view', $asset->id]);
        }
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), ['assigned_date'], []);
            $row = $this->fetchTable('ItAssetAssignments')->newEntity([
                'asset_id' => $asset->id,
                'employee_id' => (int)$data['employee_id'],
                'assigned_date' => $data['assigned_date'] ?: date('Y-m-d'),
                'condition_on_assign' => $data['condition_on_assign'] ?? 'Good',
                'notes' => $data['notes'] ?? null,
                'status' => 'assigned',
                'created' => $this->now(),
                'modified' => $this->now(),
            ]);
            if ($this->fetchTable('ItAssetAssignments')->save($row)) {
                $from = (string)$asset->status;
                $asset->status = 'assigned';
                $asset->modified = $this->now();
                $this->fetchTable('ItAssets')->save($asset);
                $this->recordAssetEvent((int)$asset->id, 'assigned', 'Assigned to employee #' . (int)$row->employee_id, $from, 'assigned', (string)($row->notes ?? ''));
                $this->auditLog('it_asset_assign', 'it_asset', 'Assigned ' . $asset->asset_code, (int)$asset->id, (int)$row->employee_id);
                $this->Flash->success('Asset assigned.');

                return $this->redirect(['controller' => 'Assets', 'action' => 'view', $asset->id]);
            }
            $this->Flash->error('Assignment failed.');
        }
        $employees = $this->employeeOptions();
        $this->set(compact('asset', 'employees'));
    }

    public function returnAsset($id = null)
    {
        $row = $this->fetchTable('ItAssetAssignments')->get($id, contain: ['ItAssets', 'HrEmployees']);
        $this->set('pageTitle', 'Return ' . ($row->it_asset->asset_code ?? 'Asset'));
        if ($row->status !== 'assigned') {
            $this->Flash->error('This assignment is already closed.');

            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $row->return_date = $data['return_date'] ?: date('Y-m-d');
            $row->condition_on_return = $data['condition_on_return'] ?: 'Good';
            $row->notes = trim((string)$row->notes . (empty($data['notes']) ? '' : "\n" . $data['notes']));
            $row->status = 'returned';
            $row->modified = $this->now();
            $this->fetchTable('ItAssetAssignments')->save($row);
            $next = in_array($data['asset_status'] ?? '', ['available', 'faulty', 'reserved'], true) ? $data['asset_status'] : 'available';
            $asset = $row->it_asset;
            $from = (string)$asset->status;
            $asset->status = $next;
            $asset->modified = $this->now();
            $this->fetchTable('ItAssets')->save($asset);
            $this->recordAssetEvent((int)$asset->id, 'returned', 'Returned from employee #' . (int)$row->employee_id, $from, $next, (string)($row->condition_on_return ?? ''));
            $this->auditLog('it_asset_return', 'it_asset', 'Returned ' . $asset->asset_code, (int)$asset->id, (int)$row->employee_id);
            $this->Flash->success('Asset returned. The assignment record was kept.');

            return $this->redirect(['controller' => 'Assets', 'action' => 'view', $asset->id]);
        }
        $this->set(compact('row'));
    }

    public function transfer($id = null)
    {
        $row = $this->fetchTable('ItAssetAssignments')->get($id, contain: ['ItAssets', 'HrEmployees']);
        $this->set('pageTitle', 'Transfer ' . ($row->it_asset->asset_code ?? 'Asset'));
        if ($row->status !== 'assigned') {
            $this->Flash->error('Only an open assignment can be transferred.');

            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is('post')) {
            $data = $this->clean($this->request->getData(), ['assigned_date'], []);
            $newEmployee = (int)($data['employee_id'] ?? 0);
            if ($newEmployee <= 0 || $newEmployee === (int)$row->employee_id) {
                $this->Flash->error('Choose a different employee.');
            } else {
                $row->status = 'transferred';
                $row->return_date = $data['assigned_date'] ?: date('Y-m-d');
                $row->condition_on_return = $data['condition_on_return'] ?: $row->condition_on_assign;
                $row->modified = $this->now();
                $this->fetchTable('ItAssetAssignments')->save($row);
                $next = $this->fetchTable('ItAssetAssignments')->newEntity([
                    'asset_id' => $row->asset_id,
                    'employee_id' => $newEmployee,
                    'assigned_date' => $data['assigned_date'] ?: date('Y-m-d'),
                    'condition_on_assign' => $data['condition_on_assign'] ?: 'Good',
                    'notes' => 'Transferred from assignment #' . $row->id . (empty($data['notes']) ? '' : '. ' . $data['notes']),
                    'status' => 'assigned',
                    'created' => $this->now(),
                    'modified' => $this->now(),
                ]);
                $this->fetchTable('ItAssetAssignments')->save($next);
                $asset = $row->it_asset;
                $asset->status = 'assigned';
                $asset->modified = $this->now();
                $this->fetchTable('ItAssets')->save($asset);
                $this->recordAssetEvent((int)$asset->id, 'transferred', 'Transferred to employee #' . $newEmployee, 'assigned', 'assigned', (string)($data['notes'] ?? ''));
                $this->auditLog('it_asset_transfer', 'it_asset', 'Transferred ' . $asset->asset_code, (int)$asset->id, $newEmployee);
                $this->Flash->success('Asset transferred. Both assignment records were kept.');

                return $this->redirect(['controller' => 'Assets', 'action' => 'view', $asset->id]);
            }
        }
        $employees = $this->employeeOptions();
        $this->set(compact('row', 'employees'));
    }
}
