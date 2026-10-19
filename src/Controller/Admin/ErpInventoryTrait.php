<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Utility\ErpStock;
use Cake\Http\Response;

trait ErpInventoryTrait
{
    public function inventory(): void
    {
        $rows = $this->stockRows();
        $low = 0;
        foreach ($this->fetchTable('ErpItems')->find()->where(['status' => 'active', 'reorder_level >' => 0]) as $item) {
            if (ErpStock::onHand((int)$item->id) + 0.0001 < (float)$item->reorder_level) {
                $low++;
            }
        }
        $this->set('pageTitle', 'Inventory');
        $this->set('rows', $rows);
        $this->set('low', $low);
        $this->set('items', $this->fetchTable('ErpItems')->find()->where(['status' => 'active'])->orderBy(['name' => 'ASC'])->limit(300)->all());
    }

    public function items(): ?Response
    {
        return $this->renderList('Items', 'erp-items.csv', 'ErpItems', ['Code', 'Name', 'Type', 'Reorder', 'GST %', 'Status'], [
            'selects' => ['item_type' => ['raw' => 'Raw material', 'finished' => 'Finished good'], 'status' => ['active' => 'Active', 'inactive' => 'Inactive']],
            'search' => ['code', 'name'],
        ], function ($row) {
            return [$row->code, $row->name, $row->item_type === 'finished' ? 'Finished good' : 'Raw material', $row->reorder_level, $row->gst_percent, $row->status];
        }, 'itemForm', 'items');
    }

    public function itemForm($id = null): ?Response
    {
        return $this->renderForm('Item', 'ErpItems', 'items', $id, [
            'item_type' => ['label' => 'Type', 'type' => 'select', 'required' => true, 'options' => ['raw' => 'Raw material', 'finished' => 'Finished good'], 'default' => 'raw'],
            'code' => ['label' => 'Code', 'placeholder' => 'Leave empty to fill automatically'],
            'name' => ['label' => 'Name', 'required' => true, 'placeholder' => 'Material or finished product'],
            'iso_material_id' => ['label' => 'ISO material', 'type' => 'select', 'options' => $this->materialOptions(), 'placeholder' => 'Link a raw material from the ISO list'],
            'unit_id' => ['label' => 'Unit', 'type' => 'select', 'required' => true, 'options' => $this->unitOptionStrings(), 'placeholder' => 'Select the unit'],
            'reorder_level' => ['label' => 'Reorder level', 'placeholder' => 'Warn when stock falls below this'],
            'gst_percent' => ['label' => 'GST %', 'placeholder' => '18'],
            'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['active' => 'Active', 'inactive' => 'Inactive'], 'default' => 'active'],
        ], function ($table, array $data, $existing) {
            if (($data['code'] ?? '') === '') {
                $n = $table->find()->count() + 1;
                $data['code'] = sprintf('ITM-%04d', $n);
            }
            $data['code'] = strtoupper((string)$data['code']);
            $clash = $table->find()->where(['code' => $data['code']]);
            if ($existing) {
                $clash->where(['id !=' => $existing->id]);
            }
            if ($clash->count() > 0) {
                return 'Item code ' . $data['code'] . ' already exists.';
            }
            $level = str_replace(',', '', (string)($data['reorder_level'] ?? ''));
            $gst = trim((string)($data['gst_percent'] ?? ''));
            if ($gst === '') {
                $gst = '18';
            }
            if (($level !== '' && !is_numeric($level)) || !is_numeric($gst)) {
                return 'Reorder level and GST percent must be numbers.';
            }
            $data['reorder_level'] = $level === '' ? 0 : $level;
            $data['gst_percent'] = $gst;
            $data['iso_material_id'] = (int)($data['iso_material_id'] ?? 0) > 0 ? (int)$data['iso_material_id'] : null;

            return $data;
        });
    }

    public function transfer(): ?Response
    {
        return $this->stockMove('transfer');
    }

    public function adjustment(): ?Response
    {
        return $this->stockMove('adjustment');
    }

    private function stockMove(string $kind): ?Response
    {
        if ($this->request->is('post')) {
            $itemId = (int)$this->request->getData('item_id');
            $qty = (float)$this->request->getData('quantity');
            $lot = trim((string)$this->request->getData('lot_no'));
            $date = trim((string)$this->request->getData('moved_on'));
            $item = $this->fetchTable('ErpItems')->find()->where(['id' => $itemId, 'status' => 'active'])->first();
            if (!$item || $qty <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                $this->Flash->error('Choose an item, a date, and a quantity greater than zero.');
            } elseif ($kind === 'transfer') {
                $from = (int)$this->request->getData('from_godown_id');
                $to = (int)$this->request->getData('to_godown_id');
                if ($from < 1 || $to < 1 || $from === $to) {
                    $this->Flash->error('Choose two different godowns.');
                } elseif (ErpStock::onHand($itemId, $from, $lot) + 0.0001 < $qty) {
                    $this->Flash->error('Not enough stock in the source godown for this lot.');
                } else {
                    $table = $this->fetchTable('ErpTransfers');
                    $row = $table->newEntity([
                        'transfer_no' => $this->nextDocNo('ErpTransfers', 'transfer_no', 'TR'),
                        'item_id' => $itemId,
                        'from_godown_id' => $from,
                        'to_godown_id' => $to,
                        'lot_no' => $lot,
                        'quantity' => round($qty, 3),
                        'moved_on' => $date,
                        'notes' => trim((string)$this->request->getData('notes')) ?: null,
                        'created_by' => $this->adminId(),
                        'created' => date('Y-m-d H:i:s'),
                    ]);
                    $table->save($row);
                    ErpStock::move($itemId, $from, $lot, 0, $qty, 'transfer', (int)$row->id, $date, 'Transfer out');
                    ErpStock::move($itemId, $to, $lot, $qty, 0, 'transfer', (int)$row->id, $date, 'Transfer in');
                    $this->audit('create', 'ErpTransfers', (int)$row->id, $row->transfer_no);
                    $this->Flash->success($row->transfer_no . ' posted.');

                    return $this->redirect(['action' => 'inventory']);
                }
            } else {
                $godown = (int)$this->request->getData('godown_id');
                $direction = (string)$this->request->getData('direction') === 'out' ? 'out' : 'in';
                if ($godown < 1) {
                    $this->Flash->error('Choose a godown.');
                } elseif ($direction === 'out' && ErpStock::onHand($itemId, $godown, $lot) + 0.0001 < $qty) {
                    $this->Flash->error('Not enough stock to adjust out.');
                } else {
                    $table = $this->fetchTable('ErpAdjustments');
                    $row = $table->newEntity([
                        'adjust_no' => $this->nextDocNo('ErpAdjustments', 'adjust_no', 'ADJ'),
                        'item_id' => $itemId,
                        'godown_id' => $godown,
                        'lot_no' => $lot,
                        'direction' => $direction,
                        'quantity' => round($qty, 3),
                        'reason' => trim((string)$this->request->getData('reason')) ?: null,
                        'moved_on' => $date,
                        'created_by' => $this->adminId(),
                        'created' => date('Y-m-d H:i:s'),
                    ]);
                    $table->save($row);
                    ErpStock::move($itemId, $godown, $lot, $direction === 'in' ? $qty : 0, $direction === 'out' ? $qty : 0, 'adjustment', (int)$row->id, $date, (string)$row->reason);
                    $this->audit('create', 'ErpAdjustments', (int)$row->id, $row->adjust_no);
                    $this->Flash->success($row->adjust_no . ' posted.');

                    return $this->redirect(['action' => 'inventory']);
                }
            }
        }
        $this->set('pageTitle', $kind === 'transfer' ? 'Stock transfer' : 'Stock adjustment');
        $this->set('kind', $kind);
        $this->set('itemOptions', $this->itemOptions());
        $this->set('godowns', $this->godownOptions());
        $this->render('move');

        return null;
    }

    /**
     * @return list<array{item: string, godown: string, lot: string, qty: float}>
     */
    private function stockRows(): array
    {
        $sql = 'SELECT i.name item_name, g.name godown_name, l.lot_no, SUM(l.qty_in) - SUM(l.qty_out) qty
                FROM erp_stock_ledger l
                INNER JOIN erp_items i ON i.id = l.item_id
                INNER JOIN erp_godowns g ON g.id = l.godown_id
                GROUP BY l.item_id, l.godown_id, l.lot_no
                HAVING qty <> 0
                ORDER BY i.name, g.name, l.lot_no';
        $rows = [];
        foreach ($this->fetchTable('ErpItems')->getConnection()->execute($sql)->fetchAll('assoc') as $row) {
            $rows[] = ['item' => (string)$row['item_name'], 'godown' => (string)$row['godown_name'], 'lot' => (string)$row['lot_no'], 'qty' => (float)$row['qty']];
        }

        return $rows;
    }

    private function lowStockCount(): int
    {
        $count = 0;
        foreach ($this->fetchTable('ErpItems')->find()->where(['status' => 'active', 'reorder_level >' => 0]) as $item) {
            if (ErpStock::onHand((int)$item->id) + 0.0001 < (float)$item->reorder_level) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return array<string, string>
     */
    private function itemOptions(?string $type = null): array
    {
        $query = $this->fetchTable('ErpItems')->find()->where(['status' => 'active'])->orderBy(['name' => 'ASC']);
        if ($type) {
            $query->where(['item_type' => $type]);
        }
        $out = [];
        foreach ($query->limit(300) as $row) {
            $out[(string)$row->id] = $row->code . ' — ' . $row->name;
        }

        return $out;
    }

    private function itemName(int $id): string
    {
        $row = $this->fetchTable('ErpItems')->find()->select(['name'])->where(['id' => $id])->first();

        return $row ? (string)$row->name : '';
    }

    /**
     * @return array<string, string>
     */
    private function godownOptions(): array
    {
        $out = [];
        foreach ($this->fetchTable('ErpGodowns')->find()->where(['status' => 'active'])->orderBy(['name' => 'ASC']) as $row) {
            $out[(string)$row->id] = $row->name;
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    private function unitOptionStrings(): array
    {
        $out = [];
        foreach ($this->unitNames() as $id => $code) {
            $out[(string)$id] = $code;
        }

        return $out;
    }
}
