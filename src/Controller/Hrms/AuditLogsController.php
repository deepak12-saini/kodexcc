<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

class AuditLogsController extends HrmsController
{
    public function index()
    {
        $this->requireHrRole(['admin', 'hr']);
        $this->set('pageTitle', 'Audit Log');

        $query = $this->fetchTable('HrAuditLogs')->find()
            ->contain(['HrUsers', 'HrEmployees']);

        $action = trim((string)$this->request->getQuery('action'));
        $entity = trim((string)$this->request->getQuery('entity_type'));
        $from = (string)$this->request->getQuery('from');
        $to = (string)$this->request->getQuery('to');

        if ($action !== '') {
            $query->where(['HrAuditLogs.action' => $action]);
        }
        if ($entity !== '') {
            $query->where(['HrAuditLogs.entity_type' => $entity]);
        }
        if ($from !== '') {
            $query->where(['HrAuditLogs.created >=' => $from . ' 00:00:00']);
        }
        if ($to !== '') {
            $query->where(['HrAuditLogs.created <=' => $to . ' 23:59:59']);
        }

        $this->hrPaginate($query, [
            'limit' => 30,
            'order' => ['HrAuditLogs.id' => 'DESC'],
            'sortableFields' => ['HrAuditLogs.id', 'HrAuditLogs.created', 'HrAuditLogs.action'],
        ]);
        $this->set(compact('action', 'entity', 'from', 'to'));
    }

    public function deleteSelected()
    {
        $this->request->allowMethod(['post']);
        $this->requireHrRole(['admin', 'hr']);
        $ids = (array)$this->request->getData('ids');
        $ids = array_values(array_filter(array_map('intval', $ids)));

        $redirectQuery = array_filter([
            'action' => (string)$this->request->getData('filter_action'),
            'entity_type' => (string)$this->request->getData('filter_entity'),
            'from' => (string)$this->request->getData('filter_from'),
            'to' => (string)$this->request->getData('filter_to'),
        ], static fn ($v) => $v !== '');

        if ($ids === []) {
            $this->Flash->error('Select at least one record to delete.');
            return $this->redirect(['action' => 'index', '?' => $redirectQuery]);
        }

        $table = $this->fetchTable('HrAuditLogs');
        $deleted = $table->deleteAll(['id IN' => $ids]);
        $this->auditLog(
            'audit_delete_selected',
            'audit_log',
            'Deleted ' . (int)$deleted . ' audit log record(s)',
            null,
            null,
            null,
            ['ids' => $ids, 'deleted' => $deleted]
        );
        $this->Flash->success((int)$deleted . ' record(s) deleted.');
        return $this->redirect(['action' => 'index', '?' => $redirectQuery]);
    }

    public function deleteAll()
    {
        $this->request->allowMethod(['post']);
        $this->requireHrRole(['admin', 'hr']);

        $confirm = (string)$this->request->getData('confirm');
        $scope = (string)$this->request->getData('scope'); // filtered | all

        $action = trim((string)$this->request->getData('filter_action'));
        $entity = trim((string)$this->request->getData('filter_entity'));
        $from = (string)$this->request->getData('filter_from');
        $to = (string)$this->request->getData('filter_to');

        $redirectQuery = array_filter([
            'action' => $action,
            'entity_type' => $entity,
            'from' => $from,
            'to' => $to,
        ], static fn ($v) => $v !== '');

        if ($confirm !== 'DELETE') {
            $this->Flash->error('Type DELETE to confirm bulk delete.');
            return $this->redirect(['action' => 'index', '?' => $redirectQuery]);
        }

        $table = $this->fetchTable('HrAuditLogs');
        $conditions = [];
        if ($scope === 'filtered') {
            if ($action !== '') {
                $conditions['action'] = $action;
            }
            if ($entity !== '') {
                $conditions['entity_type'] = $entity;
            }
            if ($from !== '') {
                $conditions['created >='] = $from . ' 00:00:00';
            }
            if ($to !== '') {
                $conditions['created <='] = $to . ' 23:59:59';
            }
        }

        $deleted = $table->deleteAll($conditions ?: ['id >' => 0]);
        $this->auditLog(
            'audit_delete_all',
            'audit_log',
            'Bulk deleted ' . (int)$deleted . ' audit log(s) (' . ($scope ?: 'all') . ')',
            null,
            null,
            null,
            ['scope' => $scope, 'deleted' => $deleted, 'filters' => $redirectQuery]
        );
        $this->Flash->success((int)$deleted . ' record(s) deleted.');
        return $this->redirect(['action' => 'index']);
    }
}
