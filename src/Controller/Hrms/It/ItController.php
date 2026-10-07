<?php
declare(strict_types=1);

namespace App\Controller\Hrms\It;

use App\Controller\Hrms\HrmsController;
use Cake\Event\EventInterface;
use Cake\Http\Response;

class ItController extends HrmsController
{
    public const ASSET_TYPES = [
        'computer' => 'Computers',
        'laptop' => 'Laptops',
        'monitor' => 'Monitors',
        'printer' => 'Printers',
        'other' => 'Other Equipment',
    ];

    public const ASSET_STATUSES = [
        'available', 'assigned', 'faulty', 'under_repair', 'reserved', 'disposed', 'lost',
    ];

    public const TICKET_CATEGORIES = [
        'hardware' => 'Hardware',
        'windows' => 'Windows',
        'software' => 'Software',
        'printer' => 'Printer',
        'email' => 'Email',
        'internet' => 'Internet',
        'computer_slow' => 'Computer Slow',
        'other' => 'Other',
    ];

    public const TICKET_STATUSES = ['open', 'assigned', 'in_progress', 'waiting', 'resolved', 'closed'];

    public const TICKET_PRIORITIES = ['low', 'medium', 'high', 'urgent'];

    public const REPAIR_STATUSES = [
        'pending', 'sent_to_vendor', 'under_repair', 'waiting_for_parts', 'completed', 'returned', 'cancelled',
    ];

    public const PURCHASE_STATUSES = [
        'requested', 'quotation', 'approved', 'rejected', 'purchased', 'received', 'asset_created', 'cancelled',
    ];

    public const MAINTENANCE_STATUSES = ['scheduled', 'completed', 'cancelled'];

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->requireHrRole(['admin', 'it']);
        $this->viewBuilder()->addHelper('It');
        $this->set('itBase', SITEURL . 'hrms/it/');
    }

    protected function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    protected function nextCode(string $table, string $field, string $prefix): string
    {
        $n = (int)$this->fetchTable($table)->find()->count() + 1;
        do {
            $code = $prefix . date('Y') . '-' . str_pad((string)$n, 4, '0', STR_PAD_LEFT);
            $n++;
        } while ($this->fetchTable($table)->exists([$field => $code]));

        return $code;
    }

    /**
     * @param array<string, mixed> $data
     * @param list<string> $dates
     * @param list<string> $decimals
     * @return array<string, mixed>
     */
    protected function clean(array $data, array $dates = [], array $decimals = []): array
    {
        foreach ($dates as $key) {
            if (array_key_exists($key, $data) && trim((string)$data[$key]) === '') {
                $data[$key] = null;
            }
        }
        foreach ($decimals as $key) {
            if (array_key_exists($key, $data) && ($data[$key] === '' || $data[$key] === null)) {
                $data[$key] = null;
            }
        }
        foreach (['vendor_id', 'employee_id', 'asset_id', 'ticket_id', 'department_id', 'requested_by', 'assigned_user_id'] as $key) {
            if (array_key_exists($key, $data) && ($data[$key] === '' || $data[$key] === null)) {
                $data[$key] = null;
            }
        }

        return $data;
    }

    protected function employeeOptions(): array
    {
        $out = [];
        $rows = $this->fetchTable('HrEmployees')->find()
            ->select(['id', 'full_name', 'employee_code'])
            ->where(['status' => 'active'])
            ->orderBy(['full_name' => 'ASC'])
            ->all();
        foreach ($rows as $row) {
            $out[(int)$row->id] = $row->full_name . ' (' . $row->employee_code . ')';
        }

        return $out;
    }

    protected function vendorOptions(): array
    {
        return $this->fetchTable('ItVendors')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
        ])->where(['status' => 1])->orderBy(['name' => 'ASC'])->toArray();
    }

    protected function assetOptions(): array
    {
        $out = [];
        $rows = $this->fetchTable('ItAssets')->find()
            ->select(['id', 'asset_code', 'brand', 'model', 'asset_type'])
            ->where(['status NOT IN' => ['disposed', 'lost']])
            ->orderBy(['asset_code' => 'ASC'])
            ->all();
        foreach ($rows as $row) {
            $label = trim(($row->brand ?? '') . ' ' . ($row->model ?? ''));
            $out[(int)$row->id] = $row->asset_code . ($label !== '' ? ' — ' . $label : '') . ' (' . (self::ASSET_TYPES[$row->asset_type] ?? $row->asset_type) . ')';
        }

        return $out;
    }

    protected function itUserOptions(): array
    {
        $out = [];
        $rows = $this->fetchTable('HrUsers')->find()
            ->contain(['HrEmployees'])
            ->where(['HrUsers.is_active' => 1, 'HrUsers.role IN' => ['admin', 'it']])
            ->orderBy(['HrUsers.username' => 'ASC'])
            ->all();
        foreach ($rows as $row) {
            $name = $row->hr_employee->full_name ?? $row->username;
            $out[(int)$row->id] = $name . ' (' . $row->username . ')';
        }

        return $out;
    }

    protected function departmentOptions(): array
    {
        return $this->fetchTable('HrDepartments')->find('list', [
            'keyField' => 'id',
            'valueField' => 'name',
        ])->where(['status' => 1])->orderBy(['name' => 'ASC'])->toArray();
    }

    protected function recordAssetEvent(int $assetId, string $type, string $summary, ?string $from = null, ?string $to = null, ?string $notes = null): void
    {
        $this->fetchTable('ItAssetEvents')->save(
            $this->fetchTable('ItAssetEvents')->newEntity([
                'asset_id' => $assetId,
                'event_type' => $type,
                'from_status' => $from,
                'to_status' => $to,
                'summary' => mb_substr($summary, 0, 255),
                'notes' => $notes,
                'actor_user_id' => (int)($this->Session->read('HrUser.id') ?: 0) ?: null,
                'created' => $this->now(),
            ])
        );
    }

    protected function openAssignment(int $assetId)
    {
        return $this->fetchTable('ItAssetAssignments')->find()
            ->where(['asset_id' => $assetId, 'status' => 'assigned'])
            ->first();
    }

    protected function storeUpload(mixed $file, string $folder): ?string
    {
        if (!$file || !is_object($file) || !method_exists($file, 'getError') || $file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }
        $original = (string)$file->getClientFilename();
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
        if (!in_array($ext, $allowed, true)) {
            $this->Flash->error('Upload must be a PDF, image, or Office document.');

            return null;
        }
        $dir = WWW_ROOT . 'uploads' . DS . 'hrms' . DS . 'it' . DS . $folder . DS;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $original);
        $file->moveTo($dir . $name);

        return 'uploads/hrms/it/' . $folder . '/' . $name;
    }

    /**
     * @param list<string> $header
     * @param list<list<string|int|float|null>> $rows
     */
    protected function csvResponse(string $filename, array $header, array $rows): Response
    {
        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, $header);
        foreach ($rows as $row) {
            fputcsv($fh, $row);
        }
        rewind($fh);
        $csv = stream_get_contents($fh) ?: '';
        fclose($fh);

        return $this->response->withType('csv')->withDownload($filename)->withStringBody($csv);
    }
}
