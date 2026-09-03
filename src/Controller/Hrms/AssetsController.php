<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class AssetsController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Assets');
        $query = $this->fetchTable('HrAssets')->find()
            ->contain(['HrAssetAssignments' => ['HrEmployees']]);
        $this->hrPaginate($query, [
            'order' => ['HrAssets.asset_code' => 'ASC'],
            'sortableFields' => ['HrAssets.asset_code', 'HrAssets.name', 'HrAssets.status'],
        ]);
    }

    public function add()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Add Asset');
        $table = $this->fetchTable('HrAssets');
        $entity = $table->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (empty($data['asset_code'])) {
                $data['asset_code'] = 'KDX-AST-' . str_pad((string)(time() % 100000), 5, '0', STR_PAD_LEFT);
            }
            $entity = $table->patchEntity($entity, $data + [
                'status' => 'available',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($table->save($entity)) {
                $this->Flash->success('Asset saved.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function edit($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $table = $this->fetchTable('HrAssets');
        $entity = $table->get($id);
        $this->set('pageTitle', 'Edit Asset');
        if ($this->request->is(['post', 'put', 'patch'])) {
            $entity = $table->patchEntity($entity, $this->request->getData() + ['modified' => date('Y-m-d H:i:s')]);
            if ($table->save($entity)) {
                $this->Flash->success('Updated.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('entity'));
        $this->render('form');
    }

    public function assign($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $asset = $this->fetchTable('HrAssets')->get($id);
        $this->set('pageTitle', 'Assign Asset');
        $employees = $this->fetchTable('HrEmployees')->find('list', [
            'keyField' => 'id',
            'valueField' => 'full_name',
        ])->where(['status' => 'active'])->toArray();

        if ($this->request->is('post')) {
            $assignTable = $this->fetchTable('HrAssetAssignments');
            $row = $assignTable->newEntity([
                'asset_id' => $asset->id,
                'employee_id' => (int)$this->request->getData('employee_id'),
                'issue_date' => $this->request->getData('issue_date') ?: date('Y-m-d'),
                'condition_on_issue' => $this->request->getData('condition_on_issue') ?: $asset->condition_label,
                'status' => 'assigned',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if ($assignTable->save($row)) {
                $asset->status = 'assigned';
                $this->fetchTable('HrAssets')->save($asset);
                $this->auditLog(
                    'asset_assign',
                    'asset',
                    'Assigned asset ' . $asset->asset_code . ' to employee #' . (int)$row->employee_id,
                    (int)$asset->id,
                    (int)$row->employee_id
                );
                $this->Flash->success('Asset assigned.');
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Assignment failed.');
        }
        $this->set(compact('asset', 'employees'));
    }

    public function returnAsset($assignmentId = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $assignTable = $this->fetchTable('HrAssetAssignments');
        $row = $assignTable->get($assignmentId, contain: ['HrAssets']);
        $row->return_date = date('Y-m-d');
        $row->status = 'returned';
        $condition = (string)$this->request->getQuery('condition');
        $row->condition_on_return = $condition !== '' ? $condition : 'Good';
        $row->modified = date('Y-m-d H:i:s');
        $assignTable->save($row);
        $asset = $row->hr_asset;
        $asset->status = 'available';
        $this->fetchTable('HrAssets')->save($asset);
        $this->auditLog(
            'asset_return',
            'asset',
            'Returned asset ' . ($asset->asset_code ?? '') . ' from employee #' . (int)$row->employee_id,
            (int)$asset->id,
            (int)$row->employee_id
        );
        $this->Flash->success('Asset returned.');
        return $this->redirect(['action' => 'index']);
    }
}
