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
        return $this->run(
            'Asset report',
            'it-assets.csv',
            ['Code', 'Type', 'Brand', 'Model', 'Serial', 'Status', 'Location', 'Vendor', 'Purchase date', 'Cost'],
            $this->fetchTable('ItAssets')->find()->contain(['ItVendors'])->orderBy(['ItAssets.asset_code' => 'ASC']),
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
        return $this->run(
            'Assignment report',
            'it-assignments.csv',
            ['Asset', 'Employee', 'Code', 'Assigned', 'Returned', 'Status', 'Condition out', 'Condition in'],
            $this->fetchTable('ItAssetAssignments')->find()->contain(['ItAssets', 'HrEmployees'])->orderBy(['ItAssetAssignments.id' => 'DESC']),
            function ($row) {
                return [
                    $row->it_asset->asset_code ?? '',
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
        return $this->run(
            'Asset history',
            'it-asset-history.csv',
            ['When', 'Asset', 'Event', 'From', 'To', 'Summary'],
            $this->fetchTable('ItAssetEvents')->find()->contain(['ItAssets'])->orderBy(['ItAssetEvents.id' => 'DESC']),
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
        return $this->run(
            'IT ticket report',
            'it-tickets.csv',
            ['Ticket', 'Employee', 'Asset', 'Category', 'Priority', 'Status', 'Opened', 'Resolved'],
            $this->fetchTable('ItTickets')->find()->contain(['HrEmployees', 'ItAssets'])->orderBy(['ItTickets.id' => 'DESC']),
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
        return $this->run(
            'Repair report',
            'it-repairs.csv',
            ['Repair', 'Asset', 'Vendor', 'Status', 'Sent', 'Returned', 'Estimated', 'Actual', 'Warranty'],
            $this->fetchTable('ItRepairs')->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItRepairs.id' => 'DESC']),
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
        $query = $this->fetchTable('ItRepairs')->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItRepairs.id' => 'DESC']);
        $all = $query->all();
        $total = 0.0;
        foreach ($all as $row) {
            $total += (float)$row->actual_cost;
        }
        $this->set('costTotal', $total);

        return $this->run(
            'Repair costs',
            'it-repair-costs.csv',
            ['Repair', 'Asset', 'Vendor', 'Status', 'Actual cost'],
            $this->fetchTable('ItRepairs')->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItRepairs.id' => 'DESC']),
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
        return $this->run(
            'Purchase report',
            'it-purchases.csv',
            ['Request', 'Item', 'Qty', 'Requester', 'Vendor', 'Status', 'Estimated', 'Actual', 'Purchased'],
            $this->fetchTable('ItPurchaseRequests')->find()->contain(['Requesters', 'ItVendors'])->orderBy(['ItPurchaseRequests.id' => 'DESC']),
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
        return $this->run(
            'Vendor report',
            'it-vendors.csv',
            ['Vendor', 'Contact', 'Phone', 'Email', 'GST', 'Status'],
            $this->fetchTable('ItVendors')->find()->orderBy(['ItVendors.name' => 'ASC']),
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
        return $this->run(
            'Maintenance report',
            'it-maintenance.csv',
            ['Asset', 'Type', 'Scheduled', 'Completed', 'Vendor', 'Person', 'Cost', 'Status'],
            $this->fetchTable('ItMaintenance')->find()->contain(['ItAssets', 'ItVendors'])->orderBy(['ItMaintenance.id' => 'DESC']),
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
