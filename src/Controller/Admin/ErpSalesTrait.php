<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Utility\ErpBooks;
use App\Utility\ErpStock;
use Cake\Http\Response;

trait ErpSalesTrait
{
    private const SALES_ORDER_STATUS = [
        'draft' => 'Draft',
        'approved' => 'Approved',
        'partial' => 'Partly delivered',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    private function unpaidSalesCount(): int
    {
        $open = 0;
        foreach ($this->fetchTable('ErpArInvoices')->find()->where(['status' => 'open']) as $invoice) {
            if ((float)$invoice->paid_amount + 0.001 < (float)$invoice->total) {
                $open++;
            }
        }

        return $open;
    }

    public function sales(): void
    {
        $this->set('pageTitle', 'Sales');
        $this->set('unpaid', $this->unpaidSalesCount());
        $this->set('orders', $this->fetchTable('ErpSalesOrders')->find()->where(['status IN' => ['approved', 'partial']])->count());
    }

    public function salesQuotations(): ?Response
    {
        return $this->renderList('Sales quotations', 'erp-sales-quotations.csv', 'ErpSalesQuotations', ['Quotation', 'Date', 'Customer', 'Status', 'Total'], [
            'selects' => ['status' => ['open' => 'Open', 'selected' => 'Selected']],
            'search' => ['quotation_no', 'notes'],
            'dates' => 'quoted_on',
            'lineTable' => 'ErpSalesQuotationLines',
            'lineFk' => 'quotation_id',
        ], function ($row) {
            return [$row->quotation_no, $this->erpDate($row->quoted_on), $this->partnerName((int)$row->partner_id), $row->status, $this->docTotal('ErpSalesQuotationLines', 'quotation_id', (int)$row->id)];
        }, 'salesQuotationForm', 'salesQuotations');
    }

    public function salesQuotationForm($id = null): ?Response
    {
        return $this->saveDocument('Sales quotation', 'ErpSalesQuotations', 'quotation_no', 'SQ', 'ErpSalesQuotationLines', 'quotation_id', 'salesQuotations', $id, [
            'partner_id' => ['label' => 'Customer', 'type' => 'select', 'required' => true, 'options' => $this->customerOptions(), 'placeholder' => 'Select the customer'],
            'quoted_on' => ['label' => 'Quoted on', 'type' => 'date', 'required' => true],
            'valid_until' => ['label' => 'Valid until', 'type' => 'date'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea'],
        ], $this->salesLineSpec(), function ($entity) {
            return $this->fetchTable('ErpSalesOrders')->find()->where(['quotation_id' => $entity->id, 'status !=' => 'cancelled'])->count() > 0;
        }, function ($table, array $data, $existing) {
            $customer = $this->requireCustomer($data['partner_id'] ?? null);
            if (is_string($customer)) {
                return $customer;
            }
            $data['status'] = $existing ? $existing->status : 'open';

            return $data;
        }, function ($entity, bool $isNew) {
            return !$isNew && $entity->status !== 'rejected' ? [['label' => 'Make sales order', 'url' => 'sales-order-form?quotation=' . (int)$entity->id]] : [];
        });
    }

    public function salesOrders(): ?Response
    {
        return $this->renderList('Sales orders', 'erp-sales-orders.csv', 'ErpSalesOrders', ['Order', 'Date', 'Customer', 'Status', 'Total'], [
            'selects' => ['status' => self::SALES_ORDER_STATUS],
            'search' => ['so_no', 'notes'],
            'dates' => 'order_date',
            'lineTable' => 'ErpSalesOrderLines',
            'lineFk' => 'order_id',
            'deleteCheck' => function ($row): ?string {
                return $row->status === 'draft' ? null : 'Only a draft sales order can be deleted.';
            },
        ], function ($row) {
            return [$row->so_no, $this->erpDate($row->order_date), $this->partnerName((int)$row->partner_id), self::SALES_ORDER_STATUS[$row->status] ?? $row->status, $this->docTotal('ErpSalesOrderLines', 'order_id', (int)$row->id)];
        }, 'salesOrderForm', 'salesOrders');
    }

    public function salesOrderForm($id = null): ?Response
    {
        $prefill = null;
        if (!$id && ($quoteId = (int)$this->request->getQuery('quotation')) > 0) {
            $quote = $this->fetchTable('ErpSalesQuotations')->find()->where(['id' => $quoteId])->first();
            $used = $this->fetchTable('ErpSalesOrders')->find()->where(['quotation_id' => $quoteId, 'status !=' => 'cancelled'])->first();
            if ($used) {
                $this->Flash->success('This quotation already has sales order ' . $used->so_no . '.');

                return $this->redirect(['action' => 'salesOrderForm', $used->id]);
            }
            if ($quote) {
                $prefill = [
                    'header' => ['quotation_id' => $quote->id, 'partner_id' => $quote->partner_id, 'order_date' => date('Y-m-d')],
                    'lines' => $this->copyLines('ErpSalesQuotationLines', 'quotation_id', (int)$quote->id),
                ];
            }
        }

        return $this->saveDocument('Sales order', 'ErpSalesOrders', 'so_no', 'SO', 'ErpSalesOrderLines', 'order_id', 'salesOrders', $id, [
            'quotation_id' => ['label' => 'Quotation', 'type' => 'hidden'],
            'partner_id' => ['label' => 'Customer', 'type' => 'select', 'required' => true, 'options' => $this->customerOptions(), 'placeholder' => 'Select the customer'],
            'order_date' => ['label' => 'Order date', 'type' => 'date', 'required' => true],
            'notes' => ['label' => 'Notes', 'type' => 'textarea'],
        ], $this->salesLineSpec(), function ($entity) {
            return $entity->status !== 'draft';
        }, function ($table, array $data, $existing) {
            $customer = $this->requireCustomer($data['partner_id'] ?? null);
            if (is_string($customer)) {
                return $customer;
            }
            $data['status'] = $existing ? $existing->status : 'draft';

            return $data;
        }, function ($entity, bool $isNew) {
            if ($entity->quotation_id) {
                $quote = $this->fetchTable('ErpSalesQuotations')->find()->where(['id' => $entity->quotation_id, 'status' => 'open'])->first();
                if ($quote) {
                    $quote->status = 'selected';
                    $quote->modified = date('Y-m-d H:i:s');
                    $this->fetchTable('ErpSalesQuotations')->save($quote);
                }
            }
            $links = [];
            if (!$isNew && in_array($entity->status, ['approved', 'partial'], true)) {
                $links[] = ['label' => 'Deliver', 'url' => 'delivery-form?order=' . (int)$entity->id];
            }

            return $links;
        }, $prefill);
    }

    public function approveSalesOrder($id = null): Response
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'salesOrders']);
        }
        $table = $this->fetchTable('ErpSalesOrders');
        $row = $table->get((int)$id);
        if ($row->status !== 'draft' || (string)$this->request->getData('decision') !== 'approved') {
            $this->Flash->error('Only a draft sales order can be approved.');

            return $this->redirect(['action' => 'salesOrderForm', $row->id]);
        }
        $row->status = 'approved';
        $row->modified = date('Y-m-d H:i:s');
        $table->save($row);
        $this->audit('update', 'ErpSalesOrders', (int)$row->id, $row->so_no . ' approved');
        $this->Flash->success($row->so_no . ' approved.');

        return $this->redirect(['action' => 'salesOrderForm', $row->id]);
    }

    public function deliveries(): ?Response
    {
        return $this->renderList('Deliveries', 'erp-deliveries.csv', 'ErpDeliveries', ['Delivery', 'Order', 'Customer', 'Date'], [
            'search' => ['delivery_no', 'notes'],
            'dates' => 'delivered_on',
            'allowDelete' => false,
        ], function ($row) {
            return [$row->delivery_no, $this->docNo('ErpSalesOrders', (int)$row->order_id, 'so_no'), $this->partnerName((int)$row->partner_id), $this->erpDate($row->delivered_on)];
        }, 'deliveryForm', 'deliveries');
    }

    public function deliveryForm($id = null): ?Response
    {
        if ($id) {
            $entity = $this->fetchTable('ErpDeliveries')->get($id);
            $this->set('pageTitle', $entity->delivery_no);
            $this->set('entity', $entity);
            $this->set('lines', $this->loadLines('ErpDeliveryLines', 'delivery_id', (int)$entity->id));
            $this->set('billed', $this->fetchTable('ErpArInvoices')->find()->where(['delivery_id' => $entity->id])->count() > 0);
            $this->render('delivery');

            return null;
        }
        $orderId = (int)($this->request->getQuery('order') ?: $this->request->getData('order_id'));
        $order = $orderId ? $this->fetchTable('ErpSalesOrders')->find()->where(['id' => $orderId, 'status IN' => ['approved', 'partial']])->first() : null;
        if ($this->request->is('post')) {
            $saved = $order ? $this->postDelivery($order) : 'Choose an approved sales order.';
            if ($saved instanceof \Cake\Datasource\EntityInterface) {
                $this->Flash->success($saved->delivery_no . ' posted. Finished stock was reduced.');

                return $this->redirect(['action' => 'deliveryForm', $saved->id]);
            }
            $this->Flash->error(is_string($saved) ? $saved : 'Could not save this delivery.');
        }
        $open = [];
        foreach ($this->fetchTable('ErpSalesOrders')->find()->where(['status IN' => ['approved', 'partial']])->orderBy(['id' => 'DESC'])->limit(200) as $row) {
            $open[(string)$row->id] = $row->so_no . ' — ' . $this->partnerName((int)$row->partner_id);
        }
        $pending = [];
        if ($order) {
            foreach ($this->loadLines('ErpSalesOrderLines', 'order_id', (int)$order->id) as $line) {
                $remain = round((float)$line->quantity - (float)$line->delivered_qty, 3);
                if ($remain > 0) {
                    $line->set('remain', $remain);
                    $line->set('item_label', $this->itemName((int)$line->item_id));
                    $pending[] = $line;
                }
            }
        }
        $this->set('pageTitle', 'Delivery');
        $this->set('entity', $this->fetchTable('ErpDeliveries')->newEmptyEntity());
        $this->set(compact('order', 'open', 'pending'));
        $this->set('godowns', $this->godownOptions());
        $this->render('delivery');

        return null;
    }

    public function salesInvoices(): ?Response
    {
        return $this->renderList('Sales invoices', 'erp-sales-invoices.csv', 'ErpArInvoices', ['Invoice', 'Date', 'Customer', 'Total', 'Paid', 'Status'], [
            'selects' => ['status' => ['open' => 'Open']],
            'search' => ['invoice_no', 'notes'],
            'dates' => 'invoice_date',
            'lineTable' => 'ErpArInvoiceLines',
            'lineFk' => 'invoice_id',
            'deleteCheck' => function ($row): ?string {
                return (float)$row->paid_amount > 0 ? 'This invoice has a receipt, so it cannot be deleted.' : null;
            },
        ], function ($row) {
            return [$row->invoice_no, $this->erpDate($row->invoice_date), $this->partnerName((int)$row->partner_id), $this->money((float)$row->total), $this->money((float)$row->paid_amount), $row->status];
        }, 'salesInvoiceForm', 'salesInvoices');
    }

    public function salesInvoiceForm($id = null): ?Response
    {
        $prefill = null;
        if (!$id && ($deliveryId = (int)$this->request->getQuery('delivery')) > 0) {
            $delivery = $this->fetchTable('ErpDeliveries')->find()->where(['id' => $deliveryId])->first();
            $used = $delivery ? $this->fetchTable('ErpArInvoices')->find()->where(['delivery_id' => $delivery->id])->first() : null;
            if ($used) {
                $this->Flash->success('This delivery is already invoiced as ' . $used->invoice_no . '.');

                return $this->redirect(['action' => 'salesInvoiceForm', $used->id]);
            }
            if ($delivery) {
                $rates = [];
                foreach ($this->loadLines('ErpSalesOrderLines', 'order_id', (int)$delivery->order_id) as $line) {
                    $rates[(int)$line->id] = $line;
                }
                $lines = [];
                foreach ($this->loadLines('ErpDeliveryLines', 'delivery_id', (int)$delivery->id) as $line) {
                    $source = $rates[(int)$line->order_line_id] ?? null;
                    $lines[] = [
                        'item_name' => $this->itemName((int)$line->item_id),
                        'quantity' => $line->quantity,
                        'rate' => $source->rate ?? 0,
                        'gst_percent' => $source->gst_percent ?? 0,
                    ];
                }
                $prefill = ['header' => [
                    'delivery_id' => $delivery->id,
                    'order_id' => $delivery->order_id,
                    'partner_id' => $delivery->partner_id,
                    'invoice_date' => date('Y-m-d'),
                ], 'lines' => $lines];
            }
        }

        return $this->saveDocument('Sales invoice', 'ErpArInvoices', 'invoice_no', 'INV', 'ErpArInvoiceLines', 'invoice_id', 'salesInvoices', $id, [
            'delivery_id' => ['label' => 'Delivery', 'type' => 'hidden'],
            'order_id' => ['label' => 'Order', 'type' => 'hidden'],
            'partner_id' => ['label' => 'Customer', 'type' => 'select', 'required' => true, 'options' => $this->customerOptions(), 'placeholder' => 'Select the customer'],
            'invoice_date' => ['label' => 'Invoice date', 'type' => 'date', 'required' => true],
            'due_date' => ['label' => 'Due date', 'type' => 'date'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea'],
        ], [
            'item_name' => ['label' => 'Item', 'required' => true],
            'quantity' => ['label' => 'Qty', 'required' => true],
            'rate' => ['label' => 'Rate', 'required' => true],
            'gst_percent' => ['label' => 'GST %', 'required' => true],
        ], function ($entity) {
            return (float)$entity->paid_amount > 0;
        }, function ($table, array $data, $existing) {
            $customer = $this->requireCustomer($data['partner_id'] ?? null);
            if (is_string($customer)) {
                return $customer;
            }
            $locked = ErpBooks::lockMessage((string)$data['invoice_date']);
            if ($locked) {
                return $locked;
            }
            if (!empty($data['delivery_id'])) {
                $used = $this->fetchTable('ErpArInvoices')->find()->where(['delivery_id' => (int)$data['delivery_id']]);
                if ($existing) {
                    $used->where(['id !=' => $existing->id]);
                }
                if ($used->count() > 0) {
                    return 'This delivery already has a sales invoice.';
                }
            }
            $data['status'] = 'open';

            return $data;
        }, function ($entity, bool $isNew) {
            $error = ErpBooks::postSalesInvoice($entity);
            if ($error) {
                $this->Flash->error($error);
            }

            return [];
        }, $prefill, false, true);
    }

    public function outstanding(): void
    {
        $rows = [];
        foreach ($this->fetchTable('ErpArInvoices')->find()->where(['status' => 'open'])->orderBy(['id' => 'DESC'])->limit(300) as $invoice) {
            $due = round((float)$invoice->total - (float)$invoice->paid_amount, 2);
            if ($due <= 0) {
                continue;
            }
            $rows[] = ['invoice' => $invoice->invoice_no, 'customer' => $this->partnerName((int)$invoice->partner_id), 'date' => $this->erpDate($invoice->invoice_date), 'due' => $due];
        }
        $this->set('pageTitle', 'Customer outstanding');
        $this->set('rows', $rows);
    }

    /**
     * @return \Cake\Datasource\EntityInterface|string
     */
    private function postDelivery($order): \Cake\Datasource\EntityInterface|string
    {
        $date = trim((string)$this->request->getData('delivered_on'));
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return 'Delivered on is not a valid date.';
        }
        $posted = $this->request->getData('lines');
        if (!is_array($posted)) {
            return 'Enter the quantity delivered.';
        }
        $taken = [];
        foreach ($this->loadLines('ErpSalesOrderLines', 'order_id', (int)$order->id) as $line) {
            $row = $posted[$line->id] ?? $posted[(string)$line->id] ?? null;
            if (!is_array($row)) {
                continue;
            }
            $qty = trim((string)($row['quantity'] ?? ''));
            if ($qty === '' || (float)$qty == 0.0) {
                continue;
            }
            $remain = round((float)$line->quantity - (float)$line->delivered_qty, 3);
            $godown = (int)($row['godown_id'] ?? 0);
            $lot = trim((string)($row['lot_no'] ?? ''));
            if (!is_numeric($qty) || (float)$qty <= 0 || (float)$qty - $remain > 0.0001) {
                return $this->itemName((int)$line->item_id) . ' is above the open quantity.';
            }
            if ($godown < 1) {
                return 'Choose a godown for ' . $this->itemName((int)$line->item_id) . '.';
            }
            if (ErpStock::onHand((int)$line->item_id, $godown, $lot) + 0.0001 < (float)$qty) {
                return 'Not enough finished stock for ' . $this->itemName((int)$line->item_id) . '.';
            }
            $taken[] = ['line' => $line, 'qty' => round((float)$qty, 3), 'godown' => $godown, 'lot' => $lot];
        }
        if ($taken === []) {
            return 'Enter a quantity for at least one item.';
        }
        $table = $this->fetchTable('ErpDeliveries');
        $delivery = $table->newEntity([
            'delivery_no' => $this->nextDocNo('ErpDeliveries', 'delivery_no', 'DN'),
            'order_id' => $order->id,
            'partner_id' => $order->partner_id,
            'delivered_on' => $date,
            'notes' => trim((string)$this->request->getData('notes')) ?: null,
            'created_by' => $this->adminId(),
            'created' => date('Y-m-d H:i:s'),
            'modified' => date('Y-m-d H:i:s'),
        ]);
        $table->save($delivery);
        foreach ($taken as $row) {
            $saved = $this->fetchTable('ErpDeliveryLines')->newEntity([
                'delivery_id' => $delivery->id,
                'order_line_id' => $row['line']->id,
                'item_id' => $row['line']->item_id,
                'godown_id' => $row['godown'],
                'lot_no' => $row['lot'],
                'quantity' => $row['qty'],
            ]);
            $this->fetchTable('ErpDeliveryLines')->save($saved);
            ErpStock::move((int)$row['line']->item_id, $row['godown'], $row['lot'], 0, $row['qty'], 'delivery', (int)$saved->id, $date, $delivery->delivery_no);
            $row['line']->delivered_qty = round((float)$row['line']->delivered_qty + $row['qty'], 3);
            $this->fetchTable('ErpSalesOrderLines')->save($row['line']);
        }
        $complete = true;
        foreach ($this->loadLines('ErpSalesOrderLines', 'order_id', (int)$order->id) as $line) {
            if ((float)$line->delivered_qty + 0.0001 < (float)$line->quantity) {
                $complete = false;
            }
        }
        $order->status = $complete ? 'delivered' : 'partial';
        $order->modified = date('Y-m-d H:i:s');
        $this->fetchTable('ErpSalesOrders')->save($order);
        $this->audit('create', 'ErpDeliveries', (int)$delivery->id, $delivery->delivery_no);

        return $delivery;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function salesLineSpec(): array
    {
        return [
            'item_id' => ['label' => 'Item', 'type' => 'select', 'options' => $this->itemOptions('finished'), 'required' => true],
            'quantity' => ['label' => 'Qty', 'required' => true],
            'rate' => ['label' => 'Rate', 'required' => true],
            'gst_percent' => ['label' => 'GST %', 'required' => true],
        ];
    }

    /**
     * @return array<string, string>|string
     */
    private function requireCustomer(mixed $id): array|string
    {
        $row = $this->fetchTable('ErpPartners')->find()->where(['id' => (int)$id, 'partner_type' => 'customer'])->first();
        if (!$row) {
            return 'Select a customer.';
        }
        if ($row->status !== 'active') {
            return $row->name . ' is blocked. Choose an active customer.';
        }

        return ['id' => (int)$row->id];
    }

    /**
     * @return array<string, string>
     */
    private function customerOptions(): array
    {
        $out = [];
        foreach ($this->fetchTable('ErpPartners')->find()->where(['partner_type' => 'customer', 'status' => 'active'])->orderBy(['name' => 'ASC'])->limit(300) as $row) {
            $out[(string)$row->id] = $row->name;
        }

        return $out;
    }
}
