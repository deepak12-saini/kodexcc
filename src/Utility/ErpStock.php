<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Quantity stock. A row is added only after QC marks the ISO receipt as Pass.
 */
class ErpStock
{
    use LocatorAwareTrait;

    public static function onHand(int $itemId, ?int $godownId = null, ?string $lot = null): float
    {
        $sql = 'SELECT COALESCE(SUM(qty_in),0) - COALESCE(SUM(qty_out),0) FROM erp_stock_ledger WHERE item_id = :item';
        $params = ['item' => $itemId];
        if ($godownId) {
            $sql .= ' AND godown_id = :godown';
            $params['godown'] = $godownId;
        }
        if ($lot !== null) {
            $sql .= ' AND lot_no = :lot';
            $params['lot'] = $lot;
        }

        return round((float)self::db()->execute($sql, $params)->fetchColumn(0), 3);
    }

    public static function syncInspection(object $receipt): void
    {
        $db = self::db();
        $existing = $db->execute(
            'SELECT id FROM erp_stock_ledger WHERE ref_type = :type AND ref_id = :id',
            ['type' => 'iso_receipt', 'id' => (int)$receipt->id]
        )->fetchAll('assoc');
        $passed = (string)$receipt->inspection_result === 'pass';
        if (!$passed) {
            if ($existing !== []) {
                $db->execute('DELETE FROM erp_stock_ledger WHERE ref_type = :type AND ref_id = :id', ['type' => 'iso_receipt', 'id' => (int)$receipt->id]);
            }

            return;
        }
        if ($existing !== []) {
            return;
        }
        $line = $db->execute(
            'SELECT * FROM erp_goods_receipt_lines WHERE iso_receipt_id = :id',
            ['id' => (int)$receipt->id]
        )->fetch('assoc');
        $qty = $line ? (float)$line['quantity'] : self::leadingNumber((string)$receipt->quantity);
        if ($qty <= 0) {
            return;
        }
        $itemId = self::ensureItem($receipt, $line ?: null);
        $godownId = self::godownId('RM');
        $lot = trim((string)($line['lot_no'] ?? $receipt->lot_no ?? ''));
        self::move($itemId, $godownId, $lot, $qty, 0, 'iso_receipt', (int)$receipt->id, (string)($receipt->received_on ?? date('Y-m-d')), 'QC pass');
    }

    public static function move(int $itemId, int $godownId, string $lot, float $qtyIn, float $qtyOut, string $refType, ?int $refId, string $movedOn, string $notes): void
    {
        self::db()->execute(
            'INSERT INTO erp_stock_ledger (item_id, godown_id, lot_no, qty_in, qty_out, ref_type, ref_id, moved_on, notes, created)
             VALUES (:item, :godown, :lot, :qin, :qout, :ref, :rid, :moved, :notes, :created)',
            [
                'item' => $itemId,
                'godown' => $godownId,
                'lot' => $lot,
                'qin' => round($qtyIn, 3),
                'qout' => round($qtyOut, 3),
                'ref' => $refType,
                'rid' => $refId,
                'moved' => $movedOn,
                'notes' => mb_substr($notes, 0, 255),
                'created' => date('Y-m-d H:i:s'),
            ]
        );
    }

    public static function godownId(string $code): int
    {
        $id = self::db()->execute('SELECT id FROM erp_godowns WHERE code = :code', ['code' => $code])->fetchColumn(0);
        if ($id) {
            return (int)$id;
        }
        $fallback = self::db()->execute("SELECT id FROM erp_godowns WHERE status = 'active' ORDER BY id ASC LIMIT 1")->fetchColumn(0);

        return (int)$fallback;
    }

    /**
     * @param array<string, mixed>|null $line
     */
    private static function ensureItem(object $receipt, ?array $line): int
    {
        $db = self::db();
        $materialId = (int)($line['iso_material_id'] ?? 0);
        if ($materialId > 0) {
            $found = $db->execute('SELECT id FROM erp_items WHERE iso_material_id = :id', ['id' => $materialId])->fetchColumn(0);
            if ($found) {
                return (int)$found;
            }
        }
        $name = trim((string)($line['item_name'] ?? $receipt->material ?? 'Material'));
        $byName = $db->execute('SELECT id FROM erp_items WHERE name = :name', ['name' => $name])->fetchColumn(0);
        if ($byName) {
            return (int)$byName;
        }
        $n = (int)$db->execute('SELECT COUNT(*) FROM erp_items')->fetchColumn(0) + 1;
        $statement = $db->execute(
            'INSERT INTO erp_items (code, name, item_type, iso_material_id, unit_id, gst_percent, status, created, modified)
             VALUES (:code, :name, :type, :material, :unit, 18, :status, :created, :modified)',
            [
                'code' => sprintf('ITM-%04d', $n),
                'name' => $name,
                'type' => 'raw',
                'material' => $materialId > 0 ? $materialId : null,
                'unit' => $line['unit_id'] ?? null,
                'status' => 'active',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]
        );

        return (int)$statement->lastInsertId();
    }

    private static function leadingNumber(string $value): float
    {
        return preg_match('/[\d.]+/', $value, $m) ? (float)$m[0] : 0.0;
    }

    private static function db(): \Cake\Database\Connection
    {
        return ConnectionManager::get('default');
    }
}
