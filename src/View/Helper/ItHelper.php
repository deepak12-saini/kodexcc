<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

class ItHelper extends Helper
{
    public function date(mixed $v, bool $time = false): string
    {
        if ($v === null || $v === '') {
            return '—';
        }
        if (is_object($v) && method_exists($v, 'format')) {
            return $v->format($time ? 'd M Y H:i' : 'd M Y');
        }
        $ts = strtotime((string)$v);

        return $ts ? date($time ? 'd M Y H:i' : 'd M Y', $ts) : (string)$v;
    }

    public function money(mixed $v): string
    {
        if ($v === null || $v === '') {
            return '—';
        }

        return '₹' . number_format((float)$v, 2);
    }

    public function label(mixed $v): string
    {
        $s = trim((string)$v);
        if ($s === '') {
            return '—';
        }

        return ucwords(str_replace('_', ' ', $s));
    }

    public function badge(mixed $v): string
    {
        $s = strtolower(trim((string)$v));
        $cls = 'badge-muted';
        if (in_array($s, ['available', 'resolved', 'closed', 'completed', 'returned', 'approved', 'received', 'asset_created', 'assigned'], true)) {
            $cls = 'badge-ok';
        } elseif (in_array($s, ['faulty', 'lost', 'urgent', 'rejected', 'cancelled', 'high', 'disposed'], true)) {
            $cls = 'badge-danger';
        } elseif (in_array($s, ['under_repair', 'in_progress', 'waiting', 'pending', 'quotation', 'purchased', 'sent_to_vendor', 'waiting_for_parts', 'scheduled', 'reserved', 'medium', 'requested', 'open'], true)) {
            $cls = 'badge-warn';
        }

        return '<span class="badge ' . $cls . '">' . h($this->label($s)) . '</span>';
    }
}
