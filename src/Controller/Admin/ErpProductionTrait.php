<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Utility\ErpStock;
use Cake\Http\Response;

trait ErpProductionTrait
{
    public function production(): void
    {
        $this->set('pageTitle', 'Production');
        $this->set('orders', $this->fetchTable('ErpProductionOrders')->find()->orderBy(['id' => 'DESC'])->limit(30)->all());
        $this->set('boms', $this->fetchTable('ErpBoms')->find()->orderBy(['id' => 'DESC'])->limit(30)->all());
    }

    public function bomForm($id = null): ?Response
    {
        $table = $this->fetchTable('ErpBoms');
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        $lines = $id ? $this->loadLines('ErpBomLines', 'bom_id', (int)$entity->id) : [[]];
        if ($this->request->is(['post', 'put'])) {
            $itemId = (int)$this->request->getData('item_id');
            $product = $this->fetchTable('ErpItems')->find()->where(['id' => $itemId, 'item_type' => 'finished', 'status' => 'active'])->first();
            $parsed = $this->bomLines();
            if (!$product) {
                $this->Flash->error('Choose a finished good.');
            } elseif (is_string($parsed)) {
                $this->Flash->error($parsed);
            } else {
                $isNew = $entity->isNew();
                if ($isNew) {
                    $entity->bom_no = $this->nextDocNo('ErpBoms', 'bom_no', 'BOM');
                    $entity->created_by = $this->adminId();
                    $entity->created = date('Y-m-d H:i:s');
                    $entity->status = 'active';
                }
                $entity->item_id = $itemId;
                $entity->notes = trim((string)$this->request->getData('notes')) ?: null;
                $entity->modified = date('Y-m-d H:i:s');
                if ($table->save($entity)) {
                    $this->replaceLines('ErpBomLines', 'bom_id', (int)$entity->id, $parsed);
                    $this->audit($isNew ? 'create' : 'update', 'ErpBoms', (int)$entity->id, $entity->bom_no);
                    $this->Flash->success($entity->bom_no . ' saved.');

                    return $this->redirect(['action' => 'production']);
                }
                $this->Flash->error('Could not save this bill of materials.');
            }
            $lines = is_array($parsed ?? null) ? $parsed : $lines;
        }
        $this->set('pageTitle', 'Bill of materials');
        $this->set('finished', $this->itemOptions('finished'));
        $this->set('raw', $this->itemOptions('raw'));
        $this->set(compact('entity', 'lines'));

        return null;
    }

    public function productionForm($id = null): ?Response
    {
        $table = $this->fetchTable('ErpProductionOrders');
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        if (!$id && $this->request->is('post')) {
            $itemId = (int)$this->request->getData('item_id');
            $bomId = (int)$this->request->getData('bom_id');
            $qty = (float)$this->request->getData('planned_qty');
            $bom = $this->fetchTable('ErpBoms')->find()->where(['id' => $bomId, 'item_id' => $itemId, 'status' => 'active'])->first();
            if (!$bom || $qty <= 0) {
                $this->Flash->error('Choose a finished good, its bill of materials, and a quantity.');
            } else {
                $entity = $table->newEntity([
                    'prod_no' => $this->nextDocNo('ErpProductionOrders', 'prod_no', 'MO'),
                    'item_id' => $itemId,
                    'bom_id' => $bomId,
                    'planned_qty' => round($qty, 3),
                    'status' => 'released',
                    'started_on' => date('Y-m-d'),
                    'notes' => trim((string)$this->request->getData('notes')) ?: null,
                    'created_by' => $this->adminId(),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ]);
                $table->save($entity);
                $this->audit('create', 'ErpProductionOrders', (int)$entity->id, $entity->prod_no);
                $this->Flash->success($entity->prod_no . ' released. Issue the raw material next.');

                return $this->redirect(['action' => 'productionForm', $entity->id]);
            }
        }
        $issues = $id ? $this->loadLines('ErpProductionIssues', 'order_id', (int)$entity->id) : [];
        $requirements = [];
        if ($id) {
            foreach ($this->loadLines('ErpBomLines', 'bom_id', (int)$entity->bom_id) as $line) {
                $need = round((float)$line->quantity * (float)$entity->planned_qty, 3);
                $requirements[] = ['item_id' => (int)$line->item_id, 'name' => $this->itemName((int)$line->item_id), 'need' => $need];
            }
        }
        $this->set('pageTitle', $id ? (string)$entity->prod_no : 'Production order');
        $this->set('finished', $this->itemOptions('finished'));
        $this->set('boms', $this->bomOptions());
        $this->set('godowns', $this->godownOptions());
        $this->set(compact('entity', 'issues', 'requirements'));

        return null;
    }

    public function issueProduction($id = null): Response
    {
        $this->request->allowMethod(['post']);
        $order = $this->fetchTable('ErpProductionOrders')->get((int)$id);
        if ($order->status !== 'released' || $this->loadLines('ErpProductionIssues', 'order_id', (int)$order->id) !== []) {
            $this->Flash->error('Material can be issued once, on a released order.');

            return $this->redirect(['action' => 'productionForm', $order->id]);
        }
        $posted = $this->request->getData('lines');
        $rows = [];
        foreach ($this->loadLines('ErpBomLines', 'bom_id', (int)$order->bom_id) as $line) {
            $need = round((float)$line->quantity * (float)$order->planned_qty, 3);
            $input = is_array($posted) ? ($posted[$line->item_id] ?? $posted[(string)$line->item_id] ?? []) : [];
            $godown = (int)($input['godown_id'] ?? 0);
            $lot = trim((string)($input['lot_no'] ?? ''));
            if ($godown < 1) {
                $this->Flash->error('Choose a godown for ' . $this->itemName((int)$line->item_id) . '.');

                return $this->redirect(['action' => 'productionForm', $order->id]);
            }
            if (ErpStock::onHand((int)$line->item_id, $godown, $lot) + 0.0001 < $need) {
                $this->Flash->error('Not enough ' . $this->itemName((int)$line->item_id) . ' in that godown and lot.');

                return $this->redirect(['action' => 'productionForm', $order->id]);
            }
            $rows[] = ['item_id' => (int)$line->item_id, 'godown_id' => $godown, 'lot_no' => $lot, 'quantity' => $need];
        }
        foreach ($rows as $row) {
            $issue = $this->fetchTable('ErpProductionIssues')->newEntity($row + ['order_id' => $order->id]);
            $this->fetchTable('ErpProductionIssues')->save($issue);
            ErpStock::move($row['item_id'], $row['godown_id'], $row['lot_no'], 0, $row['quantity'], 'production_issue', (int)$issue->id, date('Y-m-d'), $order->prod_no);
        }
        $this->audit('update', 'ErpProductionOrders', (int)$order->id, $order->prod_no . ' material issued');
        $this->Flash->success('Material issued for ' . $order->prod_no . '.');

        return $this->redirect(['action' => 'productionForm', $order->id]);
    }

    public function completeProduction($id = null): Response
    {
        $this->request->allowMethod(['post']);
        $table = $this->fetchTable('ErpProductionOrders');
        $order = $table->get((int)$id);
        $qty = (float)$this->request->getData('output_qty');
        $godown = (int)$this->request->getData('output_godown_id');
        $lot = trim((string)$this->request->getData('output_lot'));
        if ($order->status !== 'released' || $this->loadLines('ErpProductionIssues', 'order_id', (int)$order->id) === []) {
            $this->Flash->error('Issue the raw material before receiving finished goods.');

            return $this->redirect(['action' => 'productionForm', $order->id]);
        }
        if ($qty <= 0 || $qty - (float)$order->planned_qty > 0.0001 || $godown < 1) {
            $this->Flash->error('Output quantity cannot be above the planned quantity.');

            return $this->redirect(['action' => 'productionForm', $order->id]);
        }
        $batch = $this->fetchTable('IsoBatches')->newEntity([
            'batch_no' => $order->prod_no,
            'product' => $this->itemName((int)$order->item_id),
            'started_on' => $order->started_on ?: date('Y-m-d'),
            'ended_on' => date('Y-m-d'),
            'notes' => 'From ERP production order ' . $order->prod_no,
            'created_by' => $this->adminId(),
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ]);
        $this->fetchTable('IsoBatches')->save($batch);
        ErpStock::move((int)$order->item_id, $godown, $lot, $qty, 0, 'production_receipt', (int)$order->id, date('Y-m-d'), $order->prod_no);
        $order->status = 'completed';
        $order->output_qty = round($qty, 3);
        $order->output_lot = $lot;
        $order->output_godown_id = $godown;
        $order->iso_batch_id = $batch->id;
        $order->modified = date('Y-m-d H:i:s');
        $table->save($order);
        $this->audit('update', 'ErpProductionOrders', (int)$order->id, $order->prod_no . ' completed and linked to ISO batch');
        $this->Flash->success($order->prod_no . ' received into stock and linked to ISO production.');

        return $this->redirect(['action' => 'productionForm', $order->id]);
    }

    /**
     * @return list<array<string, mixed>>|string
     */
    private function bomLines(): array|string
    {
        $rows = $this->request->getData('lines');
        if (!is_array($rows)) {
            return 'Add at least one raw material.';
        }
        $clean = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $itemId = (int)($row['item_id'] ?? 0);
            $qty = trim((string)($row['quantity'] ?? ''));
            if ($itemId < 1 && ($qty === '' || $qty === '0')) {
                continue;
            }
            if ($itemId < 1 || !is_numeric($qty) || (float)$qty <= 0) {
                return 'Each line needs a raw material and a quantity per finished unit.';
            }
            $clean[] = ['item_id' => $itemId, 'quantity' => round((float)$qty, 3)];
        }

        return $clean === [] ? 'Add at least one raw material.' : $clean;
    }

    /**
     * @return array<string, string>
     */
    private function bomOptions(): array
    {
        $out = [];
        foreach ($this->fetchTable('ErpBoms')->find()->where(['status' => 'active'])->orderBy(['id' => 'DESC'])->limit(200) as $row) {
            $out[(string)$row->id] = $row->bom_no . ' — ' . $this->itemName((int)$row->item_id);
        }

        return $out;
    }
}
