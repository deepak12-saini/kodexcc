<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class RequestsController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Helpdesk Requests');
        $status = (string)($this->request->getQuery('status') ?: '');
        $query = $this->fetchTable('HrRequests')->find()
            ->contain(['HrEmployees', 'HrRequestTypes', 'HrAssets']);
        if ($status !== '') {
            $query->where(['HrRequests.status' => $status]);
        }
        $this->hrPaginate($query, [
            'order' => ['HrRequests.id' => 'DESC'],
            'sortableFields' => ['HrRequests.id', 'HrRequests.status', 'HrRequests.created'],
        ]);
        $this->set(compact('status'));
    }

    public function view($id = null)
    {
        $this->requireHrRole(['admin', 'hr']);
        $item = $this->fetchTable('HrRequests')->get($id, contain: [
            'HrEmployees', 'HrRequestTypes', 'HrAssets',
        ]);
        $this->set('pageTitle', 'Request ' . $item->request_no);

        $availableAssets = [];
        if (!empty($item->hr_request_type->needs_asset)) {
            $availableAssets = $this->fetchTable('HrAssets')->find('list', [
                'keyField' => 'id',
                'valueField' => function ($row) {
                    return $row->asset_code . ' — ' . $row->name;
                },
            ])->where(['status' => 'available'])->toArray();
        }

        if ($this->request->is(['post', 'put']) && $item->status === 'pending') {
            $decision = (string)$this->request->getData('decision');
            $remark = (string)$this->request->getData('hr_remark');
            $item->hr_remark = $remark;
            $item->reviewed_by = (int)$this->Session->read('HrUser.id');
            $item->reviewed_at = date('Y-m-d H:i:s');
            $item->modified = date('Y-m-d H:i:s');

            if ($decision === 'rejected') {
                $item->status = 'rejected';
                $this->fetchTable('HrRequests')->save($item);
                $this->auditLog('request_reject', 'request', $item->request_no . ' rejected', (int)$item->id, (int)$item->employee_id);
                $this->Flash->success('Request rejected.');
                return $this->redirect(['action' => 'index']);
            }

            if ($decision === 'approved') {
                if (!empty($item->hr_request_type->needs_asset)) {
                    $assetId = (int)$this->request->getData('linked_asset_id');
                    if (!$assetId) {
                        $this->Flash->error('Select an available asset to assign.');
                        $this->set(compact('item', 'availableAssets'));
                        return;
                    }
                    $asset = $this->fetchTable('HrAssets')->get($assetId);
                    if ($asset->status !== 'available') {
                        $this->Flash->error('Selected asset is not available.');
                        $this->set(compact('item', 'availableAssets'));
                        return;
                    }
                    $assign = $this->fetchTable('HrAssetAssignments')->newEntity([
                        'asset_id' => $asset->id,
                        'employee_id' => $item->employee_id,
                        'issue_date' => date('Y-m-d'),
                        'condition_on_issue' => $asset->condition_label ?: 'Good',
                        'status' => 'assigned',
                        'created' => date('Y-m-d H:i:s'),
                        'modified' => date('Y-m-d H:i:s'),
                    ]);
                    if (!$this->fetchTable('HrAssetAssignments')->save($assign)) {
                        $this->Flash->error('Could not assign asset.');
                        $this->set(compact('item', 'availableAssets'));
                        return;
                    }
                    $asset->status = 'assigned';
                    $this->fetchTable('HrAssets')->save($asset);
                    $item->linked_asset_id = $asset->id;
                    $this->auditLog(
                        'asset_assign',
                        'asset',
                        'Assigned ' . $asset->asset_code . ' via ' . $item->request_no,
                        (int)$asset->id,
                        (int)$item->employee_id
                    );
                }
                $item->status = 'approved';
                $this->fetchTable('HrRequests')->save($item);
                $this->auditLog('request_approve', 'request', $item->request_no . ' approved', (int)$item->id, (int)$item->employee_id);
                $this->Flash->success('Request approved.');
                return $this->redirect(['action' => 'index']);
            }
        }

        $this->set(compact('item', 'availableAssets'));
    }
}
