<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use RuntimeException;

/**
 * Morning email for the director: open non-conformances and what closed yesterday.
 */
class IsoDailyReport
{
    public static function recipient(): string
    {
        $to = trim((string)Configure::read('Iso.dailyReportTo'));
        if ($to === '' || filter_var($to, FILTER_VALIDATE_EMAIL) === false) {
            throw new RuntimeException('Set Iso.dailyReportTo in config/app.php to the director email address.');
        }

        return $to;
    }

    /**
     * @return array{subject: string, html: string, open: int, closed: int}
     */
    public static function build(): array
    {
        $ncrs = TableRegistry::getTableLocator()->get('IsoNonconformances')->find()
            ->orderBy(['IsoNonconformances.id' => 'DESC'])
            ->all();
        $actions = [];
        foreach (TableRegistry::getTableLocator()->get('IsoActions')->find() as $action) {
            $actions[(int)$action->nonconformance_id][] = $action;
        }

        $openRows = [];
        $closedRows = [];
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        foreach ($ncrs as $row) {
            $status = (string)$row->status;
            $modified = self::stamp($row->modified);
            if ($status === 'closed') {
                if ($modified !== null && date('Y-m-d', $modified) === $yesterday) {
                    $closedRows[] = $row;
                }
                continue;
            }
            $openRows[] = $row;
        }

        $containers = TableRegistry::getTableLocator()->get('IsoContainers')->find()
            ->where(['IsoContainers.status' => 'in_transit'])
            ->orderBy(['IsoContainers.dispatched_on' => 'ASC', 'IsoContainers.id' => 'ASC'])
            ->all()
            ->toList();

        $today = date('d M Y');
        $subject = 'KodexCC daily report — ' . $today;
        $html = self::html($today, $openRows, $closedRows, $actions, $containers);

        return [
            'subject' => $subject,
            'html' => $html,
            'open' => count($openRows),
            'closed' => count($closedRows),
        ];
    }

    /**
     * @param list<object> $openRows
     * @param list<object> $closedRows
     * @param array<int, list<object>> $actions
     * @param list<object> $containers
     */
    private static function html(string $today, array $openRows, array $closedRows, array $actions, array $containers): string
    {
        $body = '<p style="margin:0 0 12px;">Good morning. This is the non-conformance position for <strong>' . self::e($today) . '</strong>.</p>';
        $body .= '<p style="margin:0 0 6px;font-weight:700;">Still open (' . count($openRows) . ')</p>';
        $body .= $openRows === []
            ? '<p style="margin:0 0 16px;">Nothing is waiting.</p>'
            : self::table($openRows, $actions, true);
        $body .= '<p style="margin:16px 0 6px;font-weight:700;">Closed yesterday (' . count($closedRows) . ')</p>';
        $body .= $closedRows === []
            ? '<p style="margin:0 0 16px;">Nothing was closed yesterday.</p>'
            : self::table($closedRows, $actions, false);
        $body .= '<p style="margin:16px 0 6px;font-weight:700;">Containers in transit (' . count($containers) . ')</p>';
        $body .= $containers === []
            ? '<p style="margin:0;">No containers are in transit.</p>'
            : self::containerTable($containers);

        return '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#eef2f6;">'
            . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef2f6;padding:28px 12px;"><tr><td align="center">'
            . '<table role="presentation" width="640" cellpadding="0" cellspacing="0" style="width:640px;max-width:640px;background:#ffffff;border-radius:16px;">'
            . '<tr><td style="background:#07111f;padding:18px 28px;"><img src="https://kodexcc.com/img/kodex-logo.png" alt="Kodex" height="48" style="display:block;height:48px;border:0;"></td></tr>'
            . '<tr><td style="padding:28px;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.5;color:#111827;">' . $body . '</td></tr>'
            . '<tr><td style="padding:0 28px 28px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#6b7280;">Sent automatically each morning to the director.</td></tr>'
            . '</table></td></tr></table></body></html>';
    }

    /**
     * @param list<object> $rows
     * @param array<int, list<object>> $actions
     */
    private static function table(array $rows, array $actions, bool $running): string
    {
        $html = '<table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-size:13px;">'
            . '<tr style="background:#f3f4f6;text-align:left;">'
            . '<th>NCR</th><th>Department</th><th>Raised</th><th>Timer</th><th>Status</th><th>Corrective action</th></tr>';
        foreach ($rows as $row) {
            $raised = self::stamp($row->created);
            $tasks = $actions[(int)$row->id] ?? [];
            $taskText = $tasks === [] ? 'None yet' : self::taskLine($tasks);
            $html .= '<tr style="border-top:1px solid #e5e7eb;vertical-align:top;">'
                . '<td>' . self::e((string)$row->ncr_no) . '</td>'
                . '<td>' . self::e((string)$row->department) . '</td>'
                . '<td>' . ($raised ? self::e(date('d M Y h:i A', $raised)) : '') . '</td>'
                . '<td>' . self::e(self::timer($raised, (string)$row->status, self::stamp($row->modified))) . '</td>'
                . '<td>' . self::e((string)$row->status === 'closed' ? 'Closed' : 'Open') . '</td>'
                . '<td>' . $taskText . '</td>'
                . '</tr>';
        }
        $html .= '</table>';
        if (!$running) {
            return $html;
        }

        return $html;
    }

    /**
     * @param list<object> $tasks
     */
    private static function taskLine(array $tasks): string
    {
        $parts = [];
        foreach ($tasks as $task) {
            $parts[] = self::e((string)$task->action_no)
                . ' · ' . self::e((string)$task->owner_name)
                . ' · ' . self::e((string)$task->status);
        }

        return implode('<br>', $parts);
    }

    private static function timer(?int $raised, string $status, ?int $modified): string
    {
        if ($raised === null) {
            return '';
        }
        $end = $status === 'closed' ? ($modified ?? $raised) : time();
        $seconds = max(0, $end - $raised);
        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $mins = intdiv($seconds % 3600, 60);
        if ($days > 0) {
            return $days . ' day' . ($days === 1 ? '' : 's') . ($hours > 0 ? ' ' . $hours . ' hr' : '');
        }
        if ($hours > 0) {
            return $hours . ' hr' . ($mins > 0 ? ' ' . $mins . ' min' : '');
        }

        return max(1, $mins) . ' min';
    }

    private static function stamp(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_object($value) && method_exists($value, 'getTimestamp')) {
            return $value->getTimestamp();
        }
        $ts = strtotime((string)$value);

        return $ts ?: null;
    }

    /**
     * @param list<object> $containers
     */
    private static function containerTable(array $containers): string
    {
        $html = '<table width="100%" cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-size:13px;">'
            . '<tr style="background:#f3f4f6;text-align:left;">'
            . '<th>Container</th><th>Customer</th><th>Destination</th><th>Left factory</th><th>Days so far</th></tr>';
        foreach ($containers as $row) {
            $left = ContainerTransit::date($row->dispatched_on);
            $html .= '<tr style="border-top:1px solid #e5e7eb;vertical-align:top;">'
                . '<td>' . self::e((string)$row->container_no) . '</td>'
                . '<td>' . self::e((string)$row->customer) . '</td>'
                . '<td>' . self::e((string)$row->destination) . '</td>'
                . '<td>' . self::e($left !== '' ? date('d M Y', strtotime($left)) : '') . '</td>'
                . '<td>' . self::e(ContainerTransit::label(ContainerTransit::days($left))) . '</td>'
                . '</tr>';
        }

        return $html . '</table>';
    }

    private static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
