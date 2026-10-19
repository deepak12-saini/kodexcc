<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Http\Response;

/**
 * Purchasing screens for ErpController: request, quotation, order, goods receipt, supplier bill.
 */
trait ErpPurchaseTrait
{
    private const REQUEST_STATUS = [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ];

    private const QUOTE_STATUS = [
        'open' => 'Open',
        'selected' => 'Selected',
        'rejected' => 'Rejected',
    ];

    private const ORDER_STATUS = [
        'draft' => 'Draft',
        'approved' => 'Approved',
        'partial' => 'Partly received',
        'received' => 'Received',
        'cancelled' => 'Cancelled',
    ];

    public function purchasing(): void
    {
        $requests = $this->fetchTable('ErpPurchaseRequests');
        $orders = $this->fetchTable('ErpPurchaseOrders');
        $cards = [
            ['label' => 'Requests to approve', 'count' => $requests->find()->where(['status' => 'submitted'])->count(), 'url' => 'requests?status=submitted', 'icon' => 'fa-file-text-o', 'tone' => 'orange'],
            ['label' => 'Orders to approve', 'count' => $orders->find()->where(['status' => 'draft'])->count(), 'url' => 'orders?status=draft', 'icon' => 'fa-check', 'tone' => 'blue'],
            ['label' => 'Orders to receive', 'count' => $orders->find()->where(['status IN' => ['approved', 'partial']])->count(), 'url' => 'orders', 'icon' => 'fa-truck', 'tone' => 'green'],
            ['label' => 'Supplier bills open', 'count' => $this->fetchTable('ErpApInvoices')->find()->where(['status' => 'open'])->count(), 'url' => 'bills?status=open', 'icon' => 'fa-inr', 'tone' => 'red'],
        ];
        $modules = [
            ['label' => 'Purchase requests', 'hint' => 'What the factory needs, before a supplier is chosen', 'icon' => 'fa-file-text-o', 'url' => 'requests'],
            ['label' => 'Quotations', 'hint' => 'Supplier prices against a request', 'icon' => 'fa-list-alt', 'url' => 'quotations'],
            ['label' => 'Purchase orders', 'hint' => 'Approved orders sent to suppliers', 'icon' => 'fa-shopping-cart', 'url' => 'orders'],
            ['label' => 'Goods receipts', 'hint' => 'What arrived. Each line opens an ISO incoming inspection', 'icon' => 'fa-inbox', 'url' => 'goods-receipts'],
            ['label' => 'Supplier bills', 'hint' => 'A/P invoices raised from a goods receipt', 'icon' => 'fa-file-text', 'url' => 'bills'],
        ];
        $this->set('pageTitle', 'Purchasing');
        $this->set(compact('cards', 'modules'));
    }

    public function requests(): ?Response
    {
        return $this->renderList('Purchase requests', 'erp-requests.csv', 'ErpPurchaseRequests', [
            'Request', 'Date', 'Requested by', 'Status',
        ], [
            'selects' => ['status' => self::REQUEST_STATUS],
            'search' => ['request_no', 'requested_by', 'notes'],
            'dates' => 'requested_on',
            'lineTable' => 'ErpPurchaseRequestLines',
            'lineFk' => 'request_id',
            'deleteCheck' => function ($row): ?string {
                if (in_array($row->status, ['approved', 'submitted'], true)) {
                    return 'Submitted and approved requests stay on record.';
                }
                if ($this->fetchTable('ErpQuotations')->find()->where(['request_id' => $row->id])->count() > 0) {
                    return 'This request has a quotation, so it cannot be deleted.';
                }

                return null;
            },
        ], function ($row) {
            return [$row->request_no, $this->erpDate($row->requested_on), $row->requested_by, self::REQUEST_STATUS[$row->status] ?? $row->status];
        }, 'requestForm', 'requests');
    }

    public function requestForm($id = null): ?Response
    {
        $fields = [
            'requested_on' => ['label' => 'Requested on', 'type' => 'date', 'required' => true],
            'requested_by' => ['label' => 'Requested by', 'type' => 'person', 'required' => true, 'placeholder' => 'Who asked for this'],
            'status' => ['label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['draft' => 'Draft', 'submitted' => 'Submitted'], 'default' => 'draft', 'placeholder' => 'Submit when it is ready for approval'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Why this is needed'],
        ];
        $spec = $this->itemLineSpec();

        return $this->saveDocument('Purchase request', 'ErpPurchaseRequests', 'request_no', 'PR', 'ErpPurchaseRequestLines', 'request_id', 'requests', $id, $fields, $spec, function ($entity) {
            return in_array($entity->status, ['approved', 'rejected'], true);
        }, null, function ($entity, bool $isNew) use ($id) {
            $links = [];
            if (!$isNew && $entity->status === 'approved') {
                $links[] = ['label' => 'Record a quotation', 'url' => 'quotation-form?request=' . (int)$entity->id];
            }

            return $links;
        });
    }

    public function approveRequest($id = null): Response
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'requests']);
        }
        $table = $this->fetchTable('ErpPurchaseRequests');
        $row = $table->get((int)$id);
        $decision = (string)$this->request->getData('decision');
        if ($row->status !== 'submitted' || !in_array($decision, ['approved', 'rejected'], true)) {
            $this->Flash->error('Only a submitted request can be approved or rejected.');

            return $this->redirect(['action' => 'requestForm', $row->id]);
        }
        $row->status = $decision;
        $row->modified = date('Y-m-d H:i:s');
        $table->save($row);
        $this->audit('update', 'ErpPurchaseRequests', (int)$row->id, $row->request_no . ' ' . $decision);
        $this->Flash->success($row->request_no . ' ' . $decision . '.');

        return $this->redirect(['action' => 'requestForm', $row->id]);
    }

    public function quotations(): ?Response
    {
        return $this->renderList('Quotations', 'erp-quotations.csv', 'ErpQuotations', [
            'Quotation', 'Date', 'Supplier', 'Status', 'Total',
        ], [
            'selects' => ['status' => self::QUOTE_STATUS],
            'search' => ['quotation_no', 'notes'],
            'dates' => 'quoted_on',
            'lineTable' => 'ErpQuotationLines',
            'lineFk' => 'quotation_id',
            'deleteCheck' => function ($row): ?string {
                if ($this->fetchTable('ErpPurchaseOrders')->find()->where(['quotation_id' => $row->id, 'status !=' => 'cancelled'])->count() > 0) {
                    return 'This quotation is used on a purchase order.';
                }

                return null;
            },
        ], function ($row) {
            return [
                $row->quotation_no,
                $this->erpDate($row->quoted_on),
                $this->partnerName((int)$row->partner_id),
                self::QUOTE_STATUS[$row->status] ?? $row->status,
                $this->docTotal('ErpQuotationLines', 'quotation_id', (int)$row->id),
            ];
        }, 'quotationForm', 'quotations');
    }

    public function quotationForm($id = null): ?Response
    {
        $fields = [
            'request_id' => ['label' => 'Purchase request', 'type' => 'select', 'options' => $this->docOptions('ErpPurchaseRequests', 'request_no', ['status' => 'approved']), 'placeholder' => 'Approved request this price is for'],
            'partner_id' => ['label' => 'Supplier', 'type' => 'select', 'required' => true, 'options' => $this->supplierOptions(), 'placeholder' => 'Select the supplier'],
            'quoted_on' => ['label' => 'Quoted on', 'type' => 'date', 'required' => true],
            'valid_until' => ['label' => 'Valid until', 'type' => 'date'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Delivery or payment notes from the supplier'],
        ];
        $prefill = null;
        if (!$id && ($requestId = (int)$this->request->getQuery('request')) > 0) {
            $request = $this->fetchTable('ErpPurchaseRequests')->find()->where(['id' => $requestId, 'status' => 'approved'])->first();
            if ($request) {
                $prefill = ['header' => ['request_id' => $requestId, 'quoted_on' => date('Y-m-d')], 'lines' => $this->copyLines('ErpPurchaseRequestLines', 'request_id', $requestId)];
            }
        }

        return $this->saveDocument('Quotation', 'ErpQuotations', 'quotation_no', 'QT', 'ErpQuotationLines', 'quotation_id', 'quotations', $id, $fields, $this->priceLineSpec(), function ($entity) {
            return $this->fetchTable('ErpPurchaseOrders')->find()->where(['quotation_id' => $entity->id, 'status !=' => 'cancelled'])->count() > 0
                || $entity->status === 'rejected';
        }, function ($table, array $data, $existing) {
            $supplier = $this->requireSupplier($data['partner_id'] ?? null);
            if (is_string($supplier)) {
                return $supplier;
            }
            if (!empty($data['request_id'])) {
                $request = $this->fetchTable('ErpPurchaseRequests')->find()->where(['id' => (int)$data['request_id'], 'status' => 'approved'])->first();
                if (!$request) {
                    return 'Quotations can only be linked to an approved purchase request.';
                }
            }
            $data['status'] = $existing ? $existing->status : 'open';

            return $data;
        }, function ($entity, bool $isNew) {
            $links = [];
            if (!$isNew && $entity->status !== 'rejected') {
                $links[] = ['label' => 'Make purchase order', 'url' => 'order-form?quotation=' . (int)$entity->id];
            }

            return $links;
        }, $prefill);
    }

    public function orders(): ?Response
    {
        return $this->renderList('Purchase orders', 'erp-orders.csv', 'ErpPurchaseOrders', [
            'Order', 'Date', 'Supplier', 'Status', 'Total',
        ], [
            'selects' => ['status' => self::ORDER_STATUS],
            'search' => ['po_no', 'notes'],
            'dates' => 'order_date',
            'lineTable' => 'ErpPurchaseOrderLines',
            'lineFk' => 'order_id',
            'deleteCheck' => function ($row): ?string {
                if ($row->status !== 'draft') {
                    return 'Only a draft order can be deleted. Cancel it instead.';
                }

                return null;
            },
        ], function ($row) {
            return [
                $row->po_no,
                $this->erpDate($row->order_date),
                $this->partnerName((int)$row->partner_id),
                self::ORDER_STATUS[$row->status] ?? $row->status,
                $this->docTotal('ErpPurchaseOrderLines', 'order_id', (int)$row->id),
            ];
        }, 'orderForm', 'orders');
    }

    public function orderForm($id = null): ?Response
    {
        $fields = [
            'quotation_id' => ['label' => 'Quotation', 'type' => 'hidden'],
            'request_id' => ['label' => 'Request', 'type' => 'hidden'],
            'partner_id' => ['label' => 'Supplier', 'type' => 'select', 'required' => true, 'options' => $this->supplierOptions(), 'placeholder' => 'Select the supplier'],
            'order_date' => ['label' => 'Order date', 'type' => 'date', 'required' => true],
            'expected_on' => ['label' => 'Expected on', 'type' => 'date'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea', 'placeholder' => 'Delivery address or instructions'],
        ];
        $prefill = null;
        if (!$id && ($quoteId = (int)$this->request->getQuery('quotation')) > 0) {
            $quote = $this->fetchTable('ErpQuotations')->find()->where(['id' => $quoteId])->first();
            $used = $this->fetchTable('ErpPurchaseOrders')->find()->where(['quotation_id' => $quoteId, 'status !=' => 'cancelled'])->first();
            if ($used) {
                $this->Flash->success('This quotation already has purchase order ' . $used->po_no . '.');

                return $this->redirect(['action' => 'orderForm', $used->id]);
            }
            if ($quote && $quote->status !== 'rejected') {
                $prefill = [
                    'header' => [
                        'quotation_id' => $quote->id,
                        'request_id' => $quote->request_id,
                        'partner_id' => $quote->partner_id,
                        'order_date' => date('Y-m-d'),
                    ],
                    'lines' => $this->copyLines('ErpQuotationLines', 'quotation_id', (int)$quote->id),
                ];
            }
        }

        return $this->saveDocument('Purchase order', 'ErpPurchaseOrders', 'po_no', 'PO', 'ErpPurchaseOrderLines', 'order_id', 'orders', $id, $fields, $this->priceLineSpec(), function ($entity) {
            return $entity->status !== 'draft';
        }, function ($table, array $data, $existing) {
            $supplier = $this->requireSupplier($data['partner_id'] ?? null);
            if (is_string($supplier)) {
                return $supplier;
            }
            $data['status'] = 'draft';
            if (!empty($data['quotation_id']) && !$this->fetchTable('ErpQuotations')->find()->where(['id' => (int)$data['quotation_id']])->first()) {
                return 'That quotation was not found.';
            }

            return $data;
        }, function ($entity, bool $isNew) {
            if ($entity->quotation_id && $entity->status !== 'cancelled') {
                $quote = $this->fetchTable('ErpQuotations')->find()->where(['id' => $entity->quotation_id])->first();
                if ($quote && $quote->status === 'open') {
                    $quote->status = 'selected';
                    $quote->modified = date('Y-m-d H:i:s');
                    $this->fetchTable('ErpQuotations')->save($quote);
                }
            }
            $links = [];
            if (!$isNew && in_array($entity->status, ['approved', 'partial'], true)) {
                $links[] = ['label' => 'Receive goods', 'url' => 'goods-receipt-form?order=' . (int)$entity->id];
            }

            return $links;
        }, $prefill, true);
    }

    public function approveOrder($id = null): Response
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'orders']);
        }
        $table = $this->fetchTable('ErpPurchaseOrders');
        $row = $table->get((int)$id);
        $decision = (string)$this->request->getData('decision');
        if ($row->status !== 'draft' || !in_array($decision, ['approved', 'cancelled'], true)) {
            $this->Flash->error('Only a draft order can be approved or rejected.');

            return $this->redirect(['action' => 'orderForm', $row->id]);
        }
        if ($this->loadLines('ErpPurchaseOrderLines', 'order_id', (int)$row->id) === []) {
            $this->Flash->error('Add at least one line before approving.');

            return $this->redirect(['action' => 'orderForm', $row->id]);
        }
        $row->status = $decision;
        $row->modified = date('Y-m-d H:i:s');
        $table->save($row);
        $this->releaseQuotation($row);
        $this->audit('update', 'ErpPurchaseOrders', (int)$row->id, $row->po_no . ' ' . $decision);
        $this->Flash->success($row->po_no . ' ' . ($decision === 'approved' ? 'approved.' : 'rejected.'));

        return $this->redirect(['action' => 'orderForm', $row->id]);
    }

    public function cancelOrder($id = null): Response
    {
        if (!$this->request->is('post')) {
            return $this->redirect(['action' => 'orders']);
        }
        $table = $this->fetchTable('ErpPurchaseOrders');
        $row = $table->get((int)$id);
        $received = $this->fetchTable('ErpGoodsReceipts')->find()->where(['order_id' => $row->id])->count();
        if (!in_array($row->status, ['draft', 'approved'], true) || $received > 0) {
            $this->Flash->error('This order already has goods received, so it cannot be cancelled.');

            return $this->redirect(['action' => 'orderForm', $row->id]);
        }
        $row->status = 'cancelled';
        $row->modified = date('Y-m-d H:i:s');
        $table->save($row);
        $this->releaseQuotation($row);
        $this->audit('update', 'ErpPurchaseOrders', (int)$row->id, $row->po_no . ' cancelled');
        $this->Flash->success($row->po_no . ' cancelled.');

        return $this->redirect(['action' => 'orders']);
    }

    public function goodsReceipts(): ?Response
    {
        return $this->renderList('Goods receipts', 'erp-goods-receipts.csv', 'ErpGoodsReceipts', [
            'Receipt', 'Order', 'Supplier', 'Received', 'Received by',
        ], [
            'search' => ['grn_no', 'received_by', 'notes'],
            'dates' => 'received_on',
            'allowDelete' => false,
        ], function ($row) {
            return [
                $row->grn_no,
                $this->docNo('ErpPurchaseOrders', (int)$row->order_id, 'po_no'),
                $this->partnerName((int)$row->partner_id),
                $this->erpDate($row->received_on),
                $row->received_by,
            ];
        }, 'goodsReceiptForm', 'goodsReceipts');
    }

    public function goodsReceiptForm($id = null): ?Response
    {
        $table = $this->fetchTable('ErpGoodsReceipts');
        if ($id) {
            $entity = $table->get($id);
            $lines = $this->loadLines('ErpGoodsReceiptLines', 'receipt_id', (int)$entity->id);
            $isoNos = [];
            foreach ($lines as $line) {
                $isoNos[(int)$line->id] = $line->iso_receipt_id ? $this->docNo('IsoReceipts', (int)$line->iso_receipt_id, 'receipt_no') : '';
            }
            $this->set('pageTitle', $entity->grn_no);
            $this->set('orderNo', $this->docNo('ErpPurchaseOrders', (int)$entity->order_id, 'po_no'));
            $this->set('supplierName', $this->partnerName((int)$entity->partner_id));
            $this->set(compact('entity', 'lines', 'isoNos'));
            $this->set('billExists', $this->fetchTable('ErpApInvoices')->find()->where(['receipt_id' => $entity->id])->count() > 0);
            $this->render('goods_receipt');

            return null;
        }

        $orderId = (int)($this->request->getQuery('order') ?: $this->request->getData('order_id'));
        $order = $orderId ? $this->fetchTable('ErpPurchaseOrders')->find()->where(['id' => $orderId, 'status IN' => ['approved', 'partial']])->first() : null;
        if ($this->request->is('post') && $order) {
            $built = $this->receiptLines($order);
            if (is_string($built)) {
                $this->Flash->error($built);
            } else {
                $saved = $this->postReceipt($order, $built);
                if (is_string($saved)) {
                    $this->Flash->error($saved);
                } elseif ($saved) {
                    $this->Flash->success($saved->grn_no . ' saved. QA can inspect it under ISO incoming material.');

                    return $this->redirect(['action' => 'goodsReceiptForm', $saved->id]);
                } else {
                    $this->Flash->error('Could not save this goods receipt.');
                }
            }
        } elseif ($this->request->is('post') && !$order) {
            $this->Flash->error('Choose an approved purchase order that still has something to receive.');
        }
        $openOrders = [];
        foreach ($this->fetchTable('ErpPurchaseOrders')->find()->where(['status IN' => ['approved', 'partial']])->orderBy(['id' => 'DESC'])->limit(200) as $row) {
            $openOrders[(string)$row->id] = $row->po_no . ' — ' . $this->partnerName((int)$row->partner_id);
        }
        $pending = [];
        if ($order) {
            foreach ($this->loadLines('ErpPurchaseOrderLines', 'order_id', (int)$order->id) as $line) {
                $remain = round((float)$line->quantity - (float)$line->received_qty, 3);
                if ($remain > 0) {
                    $line->set('remain', $remain);
                    $pending[] = $line;
                }
            }
        }
        $this->set('pageTitle', 'Goods receipt');
        $this->set('entity', $table->newEmptyEntity());
        $this->set(compact('order', 'openOrders', 'pending'));
        $this->set('unitNames', $this->unitNames());
        $this->render('goods_receipt');

        return null;
    }

    public function bills(): ?Response
    {
        return $this->renderList('Supplier bills', 'erp-bills.csv', 'ErpApInvoices', [
            'Bill', 'Supplier bill no', 'Date', 'Supplier', 'Total', 'Status',
        ], [
            'selects' => ['status' => ['open' => 'Open']],
            'search' => ['bill_no', 'supplier_bill_no', 'notes'],
            'dates' => 'bill_date',
            'lineTable' => 'ErpApInvoiceLines',
            'lineFk' => 'invoice_id',
            'deleteCheck' => function ($row): ?string {
                return (float)$row->paid_amount > 0 ? 'This bill has a payment, so it cannot be deleted.' : null;
            },
        ], function ($row) {
            return [
                $row->bill_no,
                $row->supplier_bill_no,
                $this->erpDate($row->bill_date),
                $this->partnerName((int)$row->partner_id),
                $this->money((float)$row->total),
                $row->status,
            ];
        }, 'billForm', 'bills');
    }

    public function billForm($id = null): ?Response
    {
        $fields = [
            'receipt_id' => ['label' => 'Goods receipt', 'type' => 'hidden'],
            'order_id' => ['label' => 'Order', 'type' => 'hidden'],
            'partner_id' => ['label' => 'Supplier', 'type' => 'select', 'required' => true, 'options' => $this->supplierOptions(), 'placeholder' => 'Select the supplier'],
            'supplier_bill_no' => ['label' => 'Supplier bill no', 'required' => true, 'placeholder' => 'Number printed on the supplier invoice'],
            'bill_date' => ['label' => 'Bill date', 'type' => 'date', 'required' => true],
            'due_date' => ['label' => 'Due date', 'type' => 'date'],
            'notes' => ['label' => 'Notes', 'type' => 'textarea'],
        ];
        $prefill = null;
        if (!$id && ($receiptId = (int)$this->request->getQuery('receipt')) > 0) {
            $receipt = $this->fetchTable('ErpGoodsReceipts')->find()->where(['id' => $receiptId])->first();
            $used = $receipt ? $this->fetchTable('ErpApInvoices')->find()->where(['receipt_id' => $receipt->id])->first() : null;
            if ($used) {
                $this->Flash->success('This goods receipt is already billed as ' . $used->bill_no . '.');

                return $this->redirect(['action' => 'billForm', $used->id]);
            }
            if ($receipt) {
                $rates = [];
                foreach ($this->loadLines('ErpPurchaseOrderLines', 'order_id', (int)$receipt->order_id) as $line) {
                    $rates[(int)$line->id] = $line;
                }
                $lines = [];
                foreach ($this->loadLines('ErpGoodsReceiptLines', 'receipt_id', (int)$receipt->id) as $line) {
                    $source = $rates[(int)$line->order_line_id] ?? null;
                    $lines[] = [
                        'item_name' => $line->item_name,
                        'quantity' => $line->quantity,
                        'rate' => $source->rate ?? 0,
                        'gst_percent' => $source->gst_percent ?? 0,
                    ];
                }
                $prefill = ['header' => [
                    'receipt_id' => $receipt->id,
                    'order_id' => $receipt->order_id,
                    'partner_id' => $receipt->partner_id,
                    'bill_date' => date('Y-m-d'),
                ], 'lines' => $lines];
            }
        }
        $spec = [
            'item_name' => ['label' => 'Item', 'required' => true],
            'quantity' => ['label' => 'Qty', 'required' => true],
            'rate' => ['label' => 'Rate', 'required' => true],
            'gst_percent' => ['label' => 'GST %', 'required' => true],
        ];

        return $this->saveDocument('Supplier bill', 'ErpApInvoices', 'bill_no', 'BILL', 'ErpApInvoiceLines', 'invoice_id', 'bills', $id, $fields, $spec, function ($entity) {
            return (float)$entity->paid_amount > 0;
        }, function ($table, array $data, $existing) {
            $supplier = $this->requireSupplier($data['partner_id'] ?? null);
            if (is_string($supplier)) {
                return $supplier;
            }
            $locked = \App\Utility\ErpBooks::lockMessage((string)($data['bill_date'] ?? ''));
            if ($locked) {
                return $locked;
            }
            $clash = $table->find()->where(['partner_id' => (int)$data['partner_id'], 'supplier_bill_no' => $data['supplier_bill_no']]);
            if ($existing) {
                $clash->where(['id !=' => $existing->id]);
            }
            if ($clash->count() > 0) {
                return 'This supplier bill number is already entered.';
            }
            if (!empty($data['receipt_id'])) {
                $receipt = $this->fetchTable('ErpGoodsReceipts')->find()->where(['id' => (int)$data['receipt_id']])->first();
                if (!$receipt) {
                    return 'That goods receipt was not found.';
                }
                $used = $this->fetchTable('ErpApInvoices')->find()->where(['receipt_id' => $receipt->id]);
                if ($existing) {
                    $used->where(['id !=' => $existing->id]);
                }
                if ($used->count() > 0) {
                    return 'This goods receipt already has a supplier bill.';
                }
            }
            $data['status'] = 'open';

            return $data;
        }, function ($entity, bool $isNew) {
            $error = \App\Utility\ErpBooks::postPurchaseBill($entity);
            if ($error) {
                $this->Flash->error($error);
            }

            return [];
        }, $prefill, false, true);
    }

    /**
     * @param array<string, array<string, mixed>> $fields
     * @param array<string, array<string, mixed>> $spec
     * @param array{header: array<string, mixed>, lines: list<array<string, mixed>>}|null $prefill
     */
    private function saveDocument(string $title, string $alias, string $numberField, string $prefix, string $lineAlias, string $lineFk, string $listAction, $id, array $fields, array $spec, callable $isLocked, ?callable $mutate, ?callable $after, ?array $prefill = null, bool $approveOrder = false, bool $storeTotals = false): ?Response
    {
        $table = $this->fetchTable($alias);
        $entity = $id ? $table->get($id) : $table->newEmptyEntity();
        $lines = $id ? $this->loadLines($lineAlias, $lineFk, (int)$entity->id) : ($prefill['lines'] ?? []);
        if (!$id && $prefill) {
            foreach ($prefill['header'] as $key => $value) {
                $entity->set($key, $value);
            }
        }
        $locked = $id && $isLocked($entity);
        if ($this->request->is(['post', 'put'])) {
            if ($locked) {
                $this->Flash->error('This document can no longer be changed.');

                return $this->redirect(['action' => $listAction]);
            }
            $header = $this->headerData($fields);
            $parsed = is_string($header) ? $header : $this->postedLines($spec);
            if (!is_string($header) && !is_string($parsed) && $mutate) {
                $result = $mutate($table, $header, $id ? $entity : null);
                if (is_string($result)) {
                    $parsed = $result;
                } else {
                    $header = $result;
                }
            }
            if (is_string($header) || is_string($parsed)) {
                $this->Flash->error(is_string($header) ? $header : $parsed);
                $entity = $table->patchEntity($entity, is_array($header) ? $header : []);
                $lines = is_array($parsed) ? $parsed : $lines;
            } else {
                $isNew = $entity->isNew();
                if ($isNew) {
                    $header[$numberField] = $this->nextDocNo($alias, $numberField, $prefix);
                    $header['created_by'] = $this->adminId();
                    $header['created'] = date('Y-m-d H:i:s');
                    if ($alias === 'ErpPurchaseRequests') {
                        $header['status'] = $header['status'] ?? 'draft';
                    }
                    if ($alias === 'ErpQuotations') {
                        $header['status'] = 'open';
                    }
                } else {
                    unset($header[$numberField]);
                }
                if ($storeTotals) {
                    $totals = $this->lineTotals($parsed);
                    $header['taxable'] = $totals['taxable'];
                    $header['gst'] = $totals['gst'];
                    $header['total'] = $totals['total'];
                }
                $header['modified'] = date('Y-m-d H:i:s');
                $entity = $table->patchEntity($entity, $header);
                if ($table->save($entity)) {
                    if ($alias === 'ErpPurchaseOrders') {
                        foreach ($parsed as &$line) {
                            $line['received_qty'] = 0;
                        }
                        unset($line);
                    }
                    if ($alias === 'ErpSalesOrders') {
                        foreach ($parsed as &$line) {
                            $line['delivered_qty'] = 0;
                        }
                        unset($line);
                    }
                    $this->replaceLines($lineAlias, $lineFk, (int)$entity->id, $parsed);
                    $links = $after ? $after($entity, $isNew) : [];
                    $this->audit($isNew ? 'create' : 'update', $alias, (int)$entity->id, $title . ' ' . ($entity->{$numberField} ?? ''));
                    $this->Flash->success($title . ' ' . ($entity->{$numberField} ?? '') . ' saved.');
                    if ($isNew) {
                        return $this->redirect(['action' => $this->request->getParam('action'), $entity->id]);
                    }
                    $locked = $isLocked($entity);
                    $lines = $this->loadLines($lineAlias, $lineFk, (int)$entity->id);
                    $extraLinks = $links;
                } else {
                    $this->Flash->error('Could not save this record.');
                    $lines = $parsed;
                }
            }
        }
        $totals = $this->lineTotals($this->linesAsArrays($lines));
        if (!isset($extraLinks)) {
            $extraLinks = $id && $after ? $after($entity, false) : [];
        }
        $this->set('pageTitle', $title);
        $this->set('approveAction', $approveOrder ? 'approveOrder' : ($alias === 'ErpPurchaseRequests' ? 'approveRequest' : null));
        $this->set('showOrderApprove', $approveOrder && $id && $entity->status === 'draft');
        $this->set('showRequestApprove', $alias === 'ErpPurchaseRequests' && $id && $entity->status === 'submitted');
        $this->set('showCancel', $approveOrder && $id && in_array($entity->status, ['draft', 'approved'], true));
        $this->set('showSalesApprove', $alias === 'ErpSalesOrders' && $id && $entity->status === 'draft');
        $this->set(compact('title', 'fields', 'entity', 'lines', 'spec', 'locked', 'listAction', 'totals', 'extraLinks'));
        $this->set('numberLabel', $id ? (string)($entity->{$numberField} ?? '') : 'Filled when you save');
        $this->render('document');

        return null;
    }

    /**
     * @param array<string, array<string, mixed>> $fields
     * @return array<string, mixed>|string
     */
    private function headerData(array $fields): array|string
    {
        $data = [];
        foreach ($fields as $key => $field) {
            $value = $this->request->getData($key);
            $value = is_string($value) ? trim($value) : $value;
            if (($value === '' || $value === null) && in_array($field['type'] ?? 'text', ['select', 'date', 'hidden'], true)) {
                $value = null;
            }
            if (!empty($field['required']) && ($value === null || $value === '')) {
                return $field['label'] . ' is required.';
            }
            $options = $field['options'] ?? null;
            if (is_array($options) && $value !== null && $value !== '' && !array_key_exists((string)$value, $options)) {
                return $field['label'] . ' has an unknown value.';
            }
            if (($field['type'] ?? '') === 'date' && $value !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$value)) {
                return $field['label'] . ' is not a valid date.';
            }
            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * @param array<string, array<string, mixed>> $spec
     * @return list<array<string, mixed>>|string
     */
    private function postedLines(array $spec): array|string
    {
        $rows = $this->request->getData('lines');
        if (!is_array($rows)) {
            return 'Add at least one line.';
        }
        $clean = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $item = trim((string)($row['item_name'] ?? ''));
            $itemId = (int)($row['item_id'] ?? 0);
            $qty = trim((string)($row['quantity'] ?? ''));
            if ($item === '' && $itemId < 1 && ($qty === '' || $qty === '0')) {
                continue;
            }
            if (!is_numeric($qty) || (float)$qty <= 0) {
                return 'Quantity must be greater than zero.';
            }
            if (array_key_exists('item_id', $spec)) {
                if ($itemId < 1) {
                    return 'Select an item on every line.';
                }
                $line = ['item_id' => $itemId, 'quantity' => round((float)$qty, 3)];
            } else {
                if ($item === '') {
                    return 'Each line needs an item name.';
                }
                $line = ['item_name' => $item, 'quantity' => round((float)$qty, 3)];
            }
            if (array_key_exists('iso_material_id', $spec)) {
                $mid = (int)($row['iso_material_id'] ?? 0);
                $line['iso_material_id'] = $mid > 0 ? $mid : null;
            }
            if (array_key_exists('unit_id', $spec)) {
                $uid = (int)($row['unit_id'] ?? 0);
                if ($uid < 1 || !isset($this->unitNames()[$uid])) {
                    return 'Select a unit on every line.';
                }
                $line['unit_id'] = $uid;
            }
            if (array_key_exists('rate', $spec)) {
                $rate = str_replace(',', '', trim((string)($row['rate'] ?? '')));
                if (!is_numeric($rate) || (float)$rate < 0) {
                    return 'Rate must be a number.';
                }
                $line['rate'] = round((float)$rate, 2);
            }
            if (array_key_exists('gst_percent', $spec)) {
                $gst = trim((string)($row['gst_percent'] ?? '0'));
                if ($gst === '') {
                    $gst = '0';
                }
                if (!is_numeric($gst) || (float)$gst < 0 || (float)$gst > 100) {
                    return 'GST percent must be between 0 and 100.';
                }
                $line['gst_percent'] = round((float)$gst, 2);
            }
            if (isset($line['rate'])) {
                $line['amount'] = round($line['quantity'] * $line['rate'], 2);
            }
            if (array_key_exists('notes', $spec)) {
                $note = trim((string)($row['notes'] ?? ''));
                $line['notes'] = $note === '' ? null : $note;
            }
            $clean[] = $line;
        }

        return $clean === [] ? 'Add at least one line.' : $clean;
    }

    /**
     * @return list<array<string, mixed>>|string
     */
    private function receiptLines($order): array|string
    {
        $posted = $this->request->getData('lines');
        if (!is_array($posted)) {
            return 'Enter the quantity received.';
        }
        $taken = [];
        foreach ($this->loadLines('ErpPurchaseOrderLines', 'order_id', (int)$order->id) as $line) {
            $row = $posted[$line->id] ?? $posted[(string)$line->id] ?? null;
            if (!is_array($row)) {
                continue;
            }
            $qty = trim((string)($row['quantity'] ?? ''));
            if ($qty === '' || (float)$qty == 0.0) {
                continue;
            }
            if (!is_numeric($qty) || (float)$qty < 0) {
                return 'Received quantity must be a number.';
            }
            $remain = round((float)$line->quantity - (float)$line->received_qty, 3);
            if ((float)$qty - $remain > 0.0001) {
                return $line->item_name . ' has ' . $this->qty($remain) . ' left on the order.';
            }
            $taken[] = [
                'line' => $line,
                'qty' => round((float)$qty, 3),
                'lot' => trim((string)($row['lot_no'] ?? '')) ?: null,
            ];
        }

        return $taken === [] ? 'Enter a quantity for at least one item.' : $taken;
    }

    /**
     * @param list<array<string, mixed>> $taken
     */
    private function postReceipt($order, array $taken)
    {
        $partner = $this->fetchTable('ErpPartners')->get((int)$order->partner_id);
        if (!$partner->iso_supplier_id) {
            $this->syncIsoSupplier($partner);
            $partner = $this->fetchTable('ErpPartners')->get((int)$partner->id);
        }
        $receivedOn = trim((string)$this->request->getData('received_on'));
        $receivedBy = trim((string)$this->request->getData('received_by'));
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $receivedOn)) {
            return 'Received on is not a valid date.';
        }
        if ($receivedBy === '') {
            return 'Received by is required.';
        }
        $conn = $this->fetchTable('ErpGoodsReceipts')->getConnection();
        $conn->begin();
        try {
            $grnNo = $this->nextDocNo('ErpGoodsReceipts', 'grn_no', 'GRN');
            $receipt = $this->fetchTable('ErpGoodsReceipts')->newEntity([
                'grn_no' => $grnNo,
                'order_id' => $order->id,
                'partner_id' => $order->partner_id,
                'received_on' => $receivedOn,
                'received_by' => $receivedBy,
                'notes' => trim((string)$this->request->getData('notes')) ?: null,
                'created_by' => $this->adminId(),
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ]);
            if (!$this->fetchTable('ErpGoodsReceipts')->save($receipt)) {
                throw new \RuntimeException('receipt');
            }
            $n = 1;
            $complete = true;
            foreach ($taken as $row) {
                $line = $row['line'];
                $unit = $this->unitNames()[(int)$line->unit_id] ?? '';
                $iso = $this->fetchTable('IsoReceipts')->newEntity([
                    'receipt_no' => $grnNo . '-' . $n,
                    'supplier_id' => $partner->iso_supplier_id ?: null,
                    'material' => $line->item_name,
                    'lot_no' => $row['lot'],
                    'quantity' => $this->qty($row['qty']) . ($unit !== '' ? ' ' . $unit : ''),
                    'received_on' => $receivedOn,
                    'received_by' => $receivedBy,
                    'inspection_result' => 'pending',
                    'remarks' => 'From ERP goods receipt ' . $grnNo,
                    'created_by' => $this->adminId(),
                    'created' => date('Y-m-d H:i:s'),
                    'modified' => date('Y-m-d H:i:s'),
                ]);
                if (!$this->fetchTable('IsoReceipts')->save($iso)) {
                    throw new \RuntimeException('iso');
                }
                $savedLine = $this->fetchTable('ErpGoodsReceiptLines')->newEntity([
                    'receipt_id' => $receipt->id,
                    'order_line_id' => $line->id,
                    'iso_material_id' => $line->iso_material_id,
                    'item_name' => $line->item_name,
                    'lot_no' => $row['lot'],
                    'quantity' => $row['qty'],
                    'unit_id' => $line->unit_id,
                    'iso_receipt_id' => $iso->id,
                ]);
                if (!$this->fetchTable('ErpGoodsReceiptLines')->save($savedLine)) {
                    throw new \RuntimeException('line');
                }
                $line->received_qty = round((float)$line->received_qty + $row['qty'], 3);
                $this->fetchTable('ErpPurchaseOrderLines')->save($line);
                $n++;
            }
            foreach ($this->loadLines('ErpPurchaseOrderLines', 'order_id', (int)$order->id) as $line) {
                if ((float)$line->received_qty + 0.0001 < (float)$line->quantity) {
                    $complete = false;
                }
            }
            $order->status = $complete ? 'received' : 'partial';
            $order->modified = date('Y-m-d H:i:s');
            $this->fetchTable('ErpPurchaseOrders')->save($order);
            $conn->commit();
        } catch (\Throwable) {
            $conn->rollback();

            return null;
        }
        $this->audit('create', 'ErpGoodsReceipts', (int)$receipt->id, 'Goods receipt ' . $receipt->grn_no . ' linked to ISO incoming material');

        return $receipt;
    }

    private function releaseQuotation($order): void
    {
        if (!$order->quotation_id || $order->status !== 'cancelled') {
            return;
        }
        $other = $this->fetchTable('ErpPurchaseOrders')->find()->where([
            'quotation_id' => $order->quotation_id,
            'id !=' => $order->id,
            'status !=' => 'cancelled',
        ])->count();
        if ($other > 0) {
            return;
        }
        $quote = $this->fetchTable('ErpQuotations')->find()->where(['id' => $order->quotation_id])->first();
        if ($quote && $quote->status === 'selected') {
            $quote->status = 'open';
            $quote->modified = date('Y-m-d H:i:s');
            $this->fetchTable('ErpQuotations')->save($quote);
        }
    }

    /**
     * @param list<array<string, mixed>> $lines
     */
    private function replaceLines(string $alias, string $fk, int $id, array $lines): void
    {
        $table = $this->fetchTable($alias);
        $table->deleteAll([$fk => $id]);
        foreach ($lines as $line) {
            $line[$fk] = $id;
            $table->save($table->newEntity($line));
        }
    }

    private function loadLines(string $alias, string $fk, int $id): array
    {
        return $this->fetchTable($alias)->find()->where([$fk => $id])->orderBy(['id' => 'ASC'])->all()->toList();
    }

    private function copyLines(string $alias, string $fk, int $id): array
    {
        $rows = [];
        foreach ($this->loadLines($alias, $fk, $id) as $line) {
            $rows[] = $line->toArray();
        }

        return $rows;
    }

    /**
     * @param list<mixed> $lines
     * @return list<array<string, mixed>>
     */
    private function linesAsArrays(array $lines): array
    {
        $out = [];
        foreach ($lines as $line) {
            $out[] = is_array($line) ? $line : $line->toArray();
        }

        return $out;
    }

    /**
     * @param list<array<string, mixed>> $lines
     * @return array{taxable: float, gst: float, total: float}
     */
    private function lineTotals(array $lines): array
    {
        $taxable = 0.0;
        $gst = 0.0;
        foreach ($lines as $line) {
            $amount = isset($line['amount']) ? (float)$line['amount'] : round((float)($line['quantity'] ?? 0) * (float)($line['rate'] ?? 0), 2);
            $taxable += $amount;
            $gst += round($amount * (float)($line['gst_percent'] ?? 0) / 100, 2);
        }

        return ['taxable' => round($taxable, 2), 'gst' => round($gst, 2), 'total' => round($taxable + $gst, 2)];
    }

    private function docTotal(string $alias, string $fk, int $id): string
    {
        return $this->money($this->lineTotals($this->linesAsArrays($this->loadLines($alias, $fk, $id)))['total']);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function itemLineSpec(): array
    {
        return [
            'iso_material_id' => ['label' => 'Material', 'type' => 'select', 'options' => $this->materialOptions()],
            'item_name' => ['label' => 'Item', 'required' => true],
            'quantity' => ['label' => 'Qty', 'required' => true],
            'unit_id' => ['label' => 'Unit', 'type' => 'select', 'options' => $this->unitNames(), 'required' => true],
            'notes' => ['label' => 'Notes'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function priceLineSpec(): array
    {
        $spec = $this->itemLineSpec();
        unset($spec['notes']);
        $spec['rate'] = ['label' => 'Rate', 'required' => true];
        $spec['gst_percent'] = ['label' => 'GST %', 'required' => true];

        return $spec;
    }

    /**
     * @return array<string, string>|string
     */
    private function requireSupplier(mixed $id): array|string
    {
        $row = $this->fetchTable('ErpPartners')->find()->where(['id' => (int)$id, 'partner_type' => 'supplier'])->first();
        if (!$row) {
            return 'Select a supplier.';
        }
        if ($row->status !== 'active') {
            return $row->name . ' is blocked. Choose an active supplier.';
        }

        return ['id' => (int)$row->id];
    }

    /**
     * @return array<string, string>
     */
    private function supplierOptions(): array
    {
        $out = [];
        foreach ($this->fetchTable('ErpPartners')->find()->where(['partner_type' => 'supplier', 'status' => 'active'])->orderBy(['name' => 'ASC'])->limit(300) as $row) {
            $out[(string)$row->id] = $row->name;
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    private function materialOptions(): array
    {
        $out = [];
        foreach ($this->fetchTable('IsoMaterials')->find()->where(['status' => 'active'])->orderBy(['name' => 'ASC'])->limit(300) as $row) {
            $out[(string)$row->id] = $row->name;
        }

        return $out;
    }

    /**
     * @return array<int, string>
     */
    private function unitNames(): array
    {
        $out = [];
        foreach ($this->fetchTable('ErpUnits')->find()->where(['status' => 'active'])->orderBy(['code' => 'ASC']) as $row) {
            $out[(int)$row->id] = (string)$row->code;
        }

        return $out;
    }

    /**
     * @param array<string, mixed> $where
     * @return array<string, string>
     */
    private function docOptions(string $alias, string $field, array $where): array
    {
        $out = [];
        foreach ($this->fetchTable($alias)->find()->where($where)->orderBy(['id' => 'DESC'])->limit(200) as $row) {
            $out[(string)$row->id] = (string)$row->{$field};
        }

        return $out;
    }

    private function partnerName(int $id): string
    {
        if ($id < 1) {
            return '';
        }
        $row = $this->fetchTable('ErpPartners')->find()->select(['name'])->where(['id' => $id])->first();

        return $row ? (string)$row->name : '';
    }

    private function docNo(string $alias, int $id, string $field): string
    {
        if ($id < 1) {
            return '';
        }
        $row = $this->fetchTable($alias)->find()->select([$field])->where(['id' => $id])->first();

        return $row ? (string)$row->{$field} : '';
    }

    private function nextDocNo(string $alias, string $field, string $prefix): string
    {
        $like = $prefix . '-' . date('Y') . '-%';
        $last = $this->fetchTable($alias)->find()->select([$field])->where([$field . ' LIKE' => $like])->orderBy(['id' => 'DESC'])->first();
        $n = 1;
        if ($last && preg_match('/(\d+)$/', (string)$last->{$field}, $m)) {
            $n = (int)$m[1] + 1;
        }

        return sprintf('%s-%s-%04d', $prefix, date('Y'), $n);
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    private function qty(float $amount): string
    {
        return rtrim(rtrim(number_format($amount, 3, '.', ''), '0'), '.');
    }

    private function erpDate(mixed $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }
        if (is_object($value) && method_exists($value, 'format')) {
            return $value->format('Y-m-d');
        }
        $ts = strtotime((string)$value);

        return $ts ? date('Y-m-d', $ts) : (string)$value;
    }
}
