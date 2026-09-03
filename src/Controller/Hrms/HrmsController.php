<?php
declare(strict_types=1);

namespace App\Controller\Hrms;

use App\Controller\AppController;
use Cake\Event\EventInterface;
use Cake\Http\Response;

class HrmsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
        $this->viewBuilder()->setLayout('hrms_layout');
        $this->set('hrUser', $this->Session->read('HrUser'));
        $this->set('hrRole', $this->Session->read('hr_role'));
        $this->set('hrEmployeeId', $this->Session->read('hr_employee_id'));
    }

    protected function hrIsAdminOrHr(): bool
    {
        return in_array((string)$this->Session->read('hr_role'), ['admin', 'hr'], true);
    }

    protected function hrIsManager(): bool
    {
        return (string)$this->Session->read('hr_role') === 'manager';
    }

    protected function hashPassword(string $plain): string
    {
        return hash('sha256', $plain);
    }

    protected function ensureLeaveBalances(int $employeeId, ?int $year = null): void
    {
        $year = $year ?? (int)date('Y');
        $types = $this->fetchTable('HrLeaveTypes')->find()->where(['status' => 1])->all();
        $balances = $this->fetchTable('HrLeaveBalances');
        foreach ($types as $type) {
            $exists = $balances->find()->where([
                'employee_id' => $employeeId,
                'leave_type_id' => $type->id,
                'year' => $year,
            ])->first();
            if (!$exists) {
                $entity = $balances->newEntity([
                    'employee_id' => $employeeId,
                    'leave_type_id' => $type->id,
                    'year' => $year,
                    'allocated' => $type->annual_quota,
                    'used' => 0,
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ]);
                $balances->save($entity);
            }
        }
    }

    protected function redirectDenied(): ?Response
    {
        $this->Flash->error('Access denied.');
        return $this->redirect(['prefix' => 'Hrms', 'controller' => 'Dashboard', 'action' => 'index']);
    }

    /**
     * Paginate a query and set $items for list templates.
     *
     * @param \Cake\Datasource\RepositoryInterface|\Cake\Datasource\QueryInterface|string $query
     * @param array<string, mixed> $settings
     * @return \Cake\Datasource\Paging\PaginatedInterface
     */
    protected function hrPaginate($query, array $settings = [])
    {
        $this->paginate = array_merge([
            'limit' => 20,
            'maxLimit' => 100,
        ], $settings);
        $items = $this->paginate($query);
        $this->set('items', $items);

        return $items;
    }

    /**
     * Write an HRMS audit log row.
     *
     * @param array<string, mixed>|null $before
     * @param array<string, mixed>|null $after
     */
    protected function auditLog(
        string $action,
        string $entityType,
        string $summary,
        ?int $entityId = null,
        ?int $employeeId = null,
        ?array $before = null,
        ?array $after = null
    ): void {
        try {
            $this->fetchTable('HrAuditLogs')->save(
                $this->fetchTable('HrAuditLogs')->newEntity([
                    'actor_user_id' => (int)($this->Session->read('HrUser.id') ?: 0) ?: null,
                    'actor_role' => (string)$this->Session->read('hr_role') ?: null,
                    'action' => $action,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                    'employee_id' => $employeeId,
                    'summary' => mb_substr($summary, 0, 255),
                    'before_json' => $before !== null ? json_encode($before) : null,
                    'after_json' => $after !== null ? json_encode($after) : null,
                    'ip' => $this->request->clientIp(),
                    'created' => date('Y-m-d H:i:s'),
                ])
            );
        } catch (\Throwable $e) {
            // Never block main flow on audit failure
        }
    }

    protected function nextRequestNo(): string
    {
        $n = (int)$this->fetchTable('HrRequests')->find()->count() + 1;

        return 'REQ-' . date('Y') . '-' . str_pad((string)$n, 4, '0', STR_PAD_LEFT);
    }
}
