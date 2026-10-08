<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

use Cake\Http\Response;

class ReportsController extends ItController
{
    public function index()
    {
        $this->set('pageTitle', 'IT Reports');
    }

    public function assets(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItAssets')->find()->contain(['ItVendors'])->orderBy(['ItAssets.id' => 'DESC']),
            [
                'type' => ['label' => 'Type', 'column' => 'ItAssets.asset_type', 'options' => self::ASSET_TYPES],
                'status' => ['label' => 'Status', 'column' => 'ItAssets.status', 'options' => $this->statusOptions(self::ASSET_STATUSES)],
                'q' => ['label' => 'Code, brand, or serial', 'type' => 'search', 'kind' => 'asset_q'],
            ]
        );

        return $this->run(
            'Asset report',
            'it-assets.csv',
            ['Code', 'Type', 'Brand', 'Model', 'Serial', 'Status', 'Location', 'Vendor', 'Purchase date', 'Cost'],
            $query,
            function ($row) {
                return [
                    $row->asset_code,
                    self::ASSET_TYPES[$row->asset_type] ?? $row->asset_type,
                    $row->brand,
                    $row->model,
                    $row->serial_number,
                    $row->status,
                    $row->location,
                    $row->it_vendor->name ?? '',
                    $this->plainDate($row->purchase_date),
                    $row->purchase_cost,
                ];
            }
        );
    }

    public function assignments(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItAssetAssignments')->find()->contain(['ItAssets', 'HrEmployees'])->orderBy(['ItAssetAssignments.id' => 'DESC']),
            [
                'type' => ['label' => 'Hardware', 'column' => 'ItAssets.asset_type', 'options' => self::ASSET_TYPES],
                'status' => ['label' => 'Status', 'column' => 'ItAssetAssignments.status', 'options' => [
                    'assigned' => 'Assigned',
                    'returned' => 'Returned',
                    'transferred' => 'Transferred',
                ]],
                'q' => ['label' => 'Asset or employee', 'type' => 'search', 'kind' => 'assignment_q'],
                'from' => ['label' => 'Assigned from', 'type' => 'date', 'column' => 'ItAssetAssignments.assigned_date', 'op' => '>='],
                'to' => ['label' => 'Assigned to', 'type' => 'date', 'column' => 'ItAssetAssignments.assigned_date', 'op' => '<='],
            ]
        );

        return $this->run(
            'Assignment report',
            'it-assignments.csv',
            ['Asset', 'Hardware', 'Brand', 'Model', 'Serial', 'Processor', 'RAM', 'Storage', 'Location', 'Employee', 'Code', 'Assigned', 'Returned', 'Status', 'Condition out', 'Condition in'],
            $query,
            function ($row) {
                $asset = $row->it_asset;

                return [
                    $asset->asset_code ?? '',
                    self::ASSET_TYPES[$asset->asset_type ?? ''] ?? ($asset->asset_type ?? ''),
                    $asset->brand ?? '',
                    $asset->model ?? '',
                    $asset->serial_number ?? '',
                    $asset->processor ?? '',
                    $asset->ram ?? '',
                    $asset->storage ?? '',
                    $asset->location ?? '',
                    $row->hr_employee->full_name ?? '',
                    $row->hr_employee->employee_code ?? '',
                    $this->plainDate($row->assigned_date),
                    $this->plainDate($row->return_date),
                    $row->status,
                    $row->condition_on_assign,
                    $row->condition_on_return,
                ];
            }
        );
    }

    public function history(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItAssetEvents')->find()->contain(['ItAssets'])->orderBy(['ItAssetEvents.id' => 'DESC']),
            [
                'event' => ['label' => 'Event', 'column' => 'ItAssetEvents.event_type', 'options' => [
                    'created' => 'Created',
                    'updated' => 'Updated',
                    'assigned' => 'Assigned',
                    'returned' => 'Returned',
                    'transferred' => 'Transferred',
                    'repair' => 'Repair',
                    'maintenance' => 'Maintenance',
                ]],
                'q' => ['label' => 'Asset code', 'type' => 'search', 'kind' => 'history_q'],
                'from' => ['label' => 'From', 'type' => 'date', 'column' => 'ItAssetEvents.created', 'op' => '>='],
                'to' => ['label' => 'To', 'type' => 'date', 'column' => 'ItAssetEvents.created', 'op' => '<='],
            ]
        );

        return $this->run(
            'Asset history',
            'it-asset-history.csv',
            ['When', 'Asset', 'Event', 'From', 'To', 'Summary'],
            $query,
            function ($row) {
                return [
                    $this->plainDate($row->created, true),
                    $row->it_asset->asset_code ?? '',
                    $row->event_type,
                    $row->from_status,
                    $row->to_status,
                    $row->summary,
                ];
            }
        );
    }

    public function tickets(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItTickets')->find()->contain(['HrEmployees', 'ItAssets'])->orderBy(['ItTickets.id' => 'DESC']),
            [
                'status' => ['label' => 'Status', 'column' => 'ItTickets.status', 'options' => $this->statusOptions(self::TICKET_STATUSES)],
                'category' => ['label' => 'Category', 'column' => 'ItTickets.category', 'options' => self::TICKET_CATEGORIES],
                'priority' => ['label' => 'Priority', 'column' => 'ItTickets.priority', 'options' => $this->statusOptions(self::TICKET_PRIORITIES)],
                'q' => ['label' => 'Ticket or employee', 'type' => 'search', 'kind' => 'ticket_q'],
            ]
        );

        return $this->run(
            'IT ticket report',
            'it-tickets.csv',
            ['Ticket', 'Employee', 'Asset', 'Category', 'Priority', 'Status', 'Opened', 'Resolved'],
            $query,
            function ($row) {
                return [
                    $row->ticket_no,
                    $row->hr_employee->full_name ?? '',
                    $row->it_asset->asset_code ?? '',
                    self::TICKET_CATEGORIES[$row->category] ?? $row->category,
                    $row->priority,
                    $row->status,
                    $this->plainDate($row->created, true),
                    $this->plainDate($row->resolved_at, true),
                ];
            }
        );
    }

    public function repairs(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItRepairs')->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItRepairs.id' => 'DESC']),
            $this->repairFilterDefs()
        );

        return $this->run(
            'Repair report',
            'it-repairs.csv',
            ['Repair', 'Asset', 'Vendor', 'Status', 'Sent', 'Returned', 'Estimated', 'Actual', 'Warranty'],
            $query,
            function ($row) {
                return [
                    $row->repair_no,
                    $row->it_asset->asset_code ?? '',
                    $row->it_vendor->name ?? '',
                    $row->status,
                    $this->plainDate($row->sent_date),
                    $this->plainDate($row->returned_date),
                    $row->estimated_cost,
                    $row->actual_cost,
                    $row->under_warranty ? 'Yes' : 'No',
                ];
            }
        );
    }

    public function repairCosts(): ?Response
    {
        $defs = $this->repairFilterDefs();
        $query = $this->applyReportFilters(
            $this->fetchTable('ItRepairs')->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItRepairs.id' => 'DESC']),
            $defs
        );
        $sum = $this->applyReportFilters($this->fetchTable('ItRepairs')->find(), $defs);
        $costRow = $sum->select(['total' => $sum->func()->sum('actual_cost')], true)->enableHydration(false)->first();
        $this->set('costTotal', (float)($costRow['total'] ?? 0));

        return $this->run(
            'Repair costs',
            'it-repair-costs.csv',
            ['Repair', 'Asset', 'Vendor', 'Status', 'Actual cost'],
            $query,
            function ($row) {
                return [
                    $row->repair_no,
                    $row->it_asset->asset_code ?? '',
                    $row->it_vendor->name ?? '',
                    $row->status,
                    $row->actual_cost,
                ];
            }
        );
    }

    public function purchases(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItPurchaseRequests')->find()->contain(['Requesters', 'ItVendors'])->orderBy(['ItPurchaseRequests.id' => 'DESC']),
            [
                'status' => ['label' => 'Status', 'column' => 'ItPurchaseRequests.approval_status', 'options' => $this->statusOptions(self::PURCHASE_STATUSES)],
                'from' => ['label' => 'Purchased from', 'type' => 'date', 'column' => 'ItPurchaseRequests.purchase_date', 'op' => '>='],
                'to' => ['label' => 'Purchased to', 'type' => 'date', 'column' => 'ItPurchaseRequests.purchase_date', 'op' => '<='],
            ]
        );

        return $this->run(
            'Purchase report',
            'it-purchases.csv',
            ['Request', 'Item', 'Qty', 'Requester', 'Vendor', 'Status', 'Estimated', 'Actual', 'Purchased'],
            $query,
            function ($row) {
                return [
                    $row->request_no,
                    $row->item_name,
                    $row->quantity,
                    $row->requester->full_name ?? '',
                    $row->it_vendor->name ?? '',
                    $row->approval_status,
                    $row->estimated_cost,
                    $row->actual_cost,
                    $this->plainDate($row->purchase_date),
                ];
            }
        );
    }

    public function vendors(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItVendors')->find()->orderBy(['ItVendors.id' => 'DESC']),
            [
                'status' => ['label' => 'Status', 'column' => 'ItVendors.status', 'options' => ['1' => 'Active', '0' => 'Inactive']],
                'q' => ['label' => 'Vendor name', 'type' => 'search', 'column' => 'ItVendors.name'],
            ]
        );

        return $this->run(
            'Vendor report',
            'it-vendors.csv',
            ['Vendor', 'Contact', 'Phone', 'Email', 'GST', 'Status'],
            $query,
            function ($row) {
                return [
                    $row->name,
                    $row->contact_person,
                    $row->phone,
                    $row->email,
                    $row->gst_number,
                    $row->status ? 'Active' : 'Inactive',
                ];
            }
        );
    }

    public function maintenance(): ?Response
    {
        $query = $this->applyReportFilters(
            $this->fetchTable('ItMaintenance')->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItMaintenance.id' => 'DESC']),
            [
                'status' => ['label' => 'Status', 'column' => 'ItMaintenance.status', 'options' => $this->statusOptions(self::MAINTENANCE_STATUSES)],
                'q' => ['label' => 'Asset code', 'type' => 'search', 'kind' => 'history_q'],
                'from' => ['label' => 'Scheduled from', 'type' => 'date', 'column' => 'ItMaintenance.scheduled_date', 'op' => '>='],
                'to' => ['label' => 'Scheduled to', 'type' => 'date', 'column' => 'ItMaintenance.scheduled_date', 'op' => '<='],
            ]
        );

        return $this->run(
            'Maintenance report',
            'it-maintenance.csv',
            ['Asset', 'Type', 'Scheduled', 'Completed', 'Vendor', 'Person', 'Cost', 'Status'],
            $query,
            function ($row) {
                return [
                    $row->it_asset->asset_code ?? '',
                    $row->maintenance_type,
                    $this->plainDate($row->scheduled_date),
                    $this->plainDate($row->completed_date),
                    $row->it_vendor->name ?? '',
                    $row->performed_by,
                    $row->cost,
                    $row->status,
                ];
            }
        );
    }
    /**
     * @param array<string, array<string, mixed>> $defs
     */
    private function applyReportFilters($query, array $defs)
    {
        $values = [];
        foreach ($defs as $key => $def) {
            $value = trim((string)$this->request->getQuery($key));
            $values[$key] = $value;
            if ($value === '') {
                continue;
            }
            $type = (string)($def['type'] ?? 'select');
            if ($type === 'select') {
                $options = $def['options'] ?? [];
                if (!array_key_exists($value, $options)) {
                    continue;
                }
                $query->where([(string)$def['column'] => $value]);
                continue;
            }
            if ($type === 'date') {
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    continue;
                }
                $op = (string)($def['op'] ?? '=');
                if (!in_array($op, ['=', '>=', '<='], true)) {
                    continue;
                }
                $bound = $op === '<=' ? $value . ' 23:59:59' : $value;
                $query->where([(string)$def['column'] . ' ' . $op => $bound]);
                continue;
            }
            if ($type === 'search') {
                $this->applySearch($query, (string)($def['kind'] ?? ''), (string)($def['column'] ?? ''), $value);
            }
        }
        $this->set('reportFilters', $defs);
        $this->set('reportFilterValues', $values);

        return $query;
    }

    private function applySearch($query, string $kind, string $column, string $value): void
    {
        $like = '%' . $value . '%';
        if ($kind === 'asset_q') {
            $query->where([
                'OR' => [
                    'ItAssets.asset_code LIKE' => $like,
                    'ItAssets.brand LIKE' => $like,
                    'ItAssets.model LIKE' => $like,
                    'ItAssets.serial_number LIKE' => $like,
                ],
            ]);

            return;
        }
        if ($kind === 'assignment_q') {
            $query->where([
                'OR' => [
                    'ItAssetAssignments.asset_id IN' => $this->fetchTable('ItAssets')->find()->select(['id'])->where([
                        'OR' => [
                            'asset_code LIKE' => $like,
                            'asset_type LIKE' => $like,
                            'brand LIKE' => $like,
                            'model LIKE' => $like,
                            'serial_number LIKE' => $like,
                        ],
                    ]),
                    'ItAssetAssignments.employee_id IN' => $this->fetchTable('HrEmployees')->find()->select(['id'])->where([
                        'OR' => [
                            'full_name LIKE' => $like,
                            'employee_code LIKE' => $like,
                        ],
                    ]),
                ],
            ]);

            return;
        }
        if ($kind === 'history_q') {
            $query->where([
                'asset_id IN' => $this->fetchTable('ItAssets')->find()->select(['id'])->where(['asset_code LIKE' => $like]),
            ]);

            return;
        }
        if ($kind === 'ticket_q') {
            $query->where([
                'OR' => [
                    'ItTickets.ticket_no LIKE' => $like,
                    'ItTickets.employee_id IN' => $this->fetchTable('HrEmployees')->find()->select(['id'])->where(['full_name LIKE' => $like]),
                ],
            ]);

            return;
        }
        if ($column !== '') {
            $query->where([$column . ' LIKE' => $like]);
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function repairFilterDefs(): array
    {
        return [
            'status' => ['label' => 'Status', 'column' => 'ItRepairs.status', 'options' => $this->statusOptions(self::REPAIR_STATUSES)],
            'from' => ['label' => 'Sent from', 'type' => 'date', 'column' => 'ItRepairs.sent_date', 'op' => '>='],
            'to' => ['label' => 'Sent to', 'type' => 'date', 'column' => 'ItRepairs.sent_date', 'op' => '<='],
        ];
    }

    /**
     * @param list<string> $keys
     * @return array<string, string>
     */
    private function statusOptions(array $keys): array
    {
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = ucwords(str_replace('_', ' ', $key));
        }

        return $out;
    }

    private function run(string $title, string $file, array $header, $query, callable $map): ?Response
    {
        $this->set('pageTitle', $title);
        if ($this->request->getQuery('export') === 'csv') {
            $rows = [];
            foreach ($query->limit(5000)->all() as $row) {
                $rows[] = $map($row);
            }

            return $this->csvResponse($file, $header, $rows);
        }
        $this->paginate = ['limit' => 30, 'maxLimit' => 100];
        $this->set('items', $this->paginate($query));
        $this->set(compact('title', 'header'));
        $this->set('map', $map);
        $this->render('report');

        return null;
    }

    private function plainDate(mixed $v, bool $time = false): string
    {
        if ($v === null || $v === '') {
            return '';
        }
        if (is_object($v) && method_exists($v, 'format')) {
            return $v->format($time ? 'Y-m-d H:i' : 'Y-m-d');
        }
        $ts = strtotime((string)$v);

        return $ts ? date($time ? 'Y-m-d H:i' : 'Y-m-d', $ts) : (string)$v;
    }
}
