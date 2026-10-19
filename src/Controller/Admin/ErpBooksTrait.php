<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Utility\ErpBooks;
use Cake\Http\Response;

trait ErpBooksTrait
{
    public function banking(): void
    {
        $banks = $this->fetchTable('ErpBankAccounts')->find()->where(['status' => 'active'])->all();
        $unreconciled = $this->fetchTable('ErpPayments')->find()->where(['reconciled' => 0])->count();
        $this->set('pageTitle', 'Banking');
        $this->set(compact('banks', 'unreconciled'));
    }

    public function payments(): ?Response
    {
        return $this->renderList('Payments', 'erp-payments.csv', 'ErpPayments', ['Payment', 'Date', 'Direction', 'Partner', 'Amount', 'Reconciled'], [
            'selects' => ['direction' => ['in' => 'In', 'out' => 'Out'], 'reconciled' => ['0' => 'Open', '1' => 'Reconciled']],
            'search' => ['payment_no', 'reference'],
            'dates' => 'paid_on',
            'allowDelete' => false,
        ], function ($row) {
            return [$row->payment_no, $this->erpDate($row->paid_on), $row->direction === 'in' ? 'In' : 'Out', $this->partnerName((int)$row->partner_id), $this->money((float)$row->amount), $row->reconciled ? 'Yes' : 'No'];
        }, 'paymentForm', 'payments');
    }

    public function paymentForm(): ?Response
    {
        if ($this->request->is('post')) {
            $error = $this->savePayment();
            if ($error === null) {
                return $this->redirect(['action' => 'payments']);
            }
            $this->Flash->error($error);
        }
        $this->set('pageTitle', 'Payment');
        $this->set('banks', $this->bankOptions());
        $this->set('apInvoices', $this->openInvoices('ErpApInvoices', 'bill_no', 'supplier'));
        $this->set('arInvoices', $this->openInvoices('ErpArInvoices', 'invoice_no', 'customer'));
        $this->render('payment');

        return null;
    }

    public function reconcile(): ?Response
    {
        if ($this->request->is('post')) {
            $ids = $this->request->getData('payment_id');
            $ids = is_array($ids) ? $ids : [];
            if ($ids === []) {
                $this->Flash->error('Select at least one payment.');
            } else {
                $this->fetchTable('ErpPayments')->updateAll(['reconciled' => 1], ['id IN' => array_map('intval', $ids), 'reconciled' => 0]);
                $this->audit('update', 'ErpPayments', null, count($ids) . ' payment(s) reconciled');
                $this->Flash->success('Marked as reconciled.');

                return $this->redirect(['action' => 'reconcile']);
            }
        }
        $rows = $this->fetchTable('ErpPayments')->find()->where(['reconciled' => 0])->orderBy(['paid_on' => 'ASC', 'id' => 'ASC'])->limit(300)->all();
        $book = (float)$this->fetchTable('ErpBankAccounts')->find()->select(['opening_balance'])->where(['status' => 'active'])->all()->sumOf('opening_balance');
        foreach ($this->fetchTable('ErpPayments')->find() as $payment) {
            $book += $payment->direction === 'in' ? (float)$payment->amount : -(float)$payment->amount;
        }
        $this->set('pageTitle', 'Bank reconciliation');
        $this->set('rows', $rows);
        $this->set('bookBalance', round($book, 2));

        return null;
    }

    public function financials(): void
    {
        $this->set('pageTitle', 'Financials');
        $this->set('periods', $this->fetchTable('ErpPeriods')->find()->orderBy(['period' => 'DESC'])->limit(24)->all());
    }

    public function trialBalance(): void
    {
        $this->report('Trial balance', 'trial');
    }

    public function profitLoss(): void
    {
        $this->report('Profit and loss', 'pl');
    }

    public function balanceSheet(): void
    {
        $this->report('Balance sheet', 'bs');
    }

    public function gstSummary(): void
    {
        $from = trim((string)$this->request->getQuery('from'));
        $to = trim((string)$this->request->getQuery('to'));
        $output = $this->gstTotal('erp_ar_invoices', 'invoice_date', $from, $to);
        $input = $this->gstTotal('erp_ap_invoices', 'bill_date', $from, $to);
        $this->set('pageTitle', 'GST summary');
        $this->set(compact('from', 'to', 'output', 'input'));
        $this->set('outward', $this->gstLines('erp_ar_invoices', 'invoice_no', 'invoice_date', $from, $to));
        $this->render('gst');
    }

    public function lockPeriod(): ?Response
    {
        $this->request->allowMethod(['post']);
        $period = trim((string)$this->request->getData('period'));
        if (!preg_match('/^\d{4}-\d{2}$/', $period)) {
            $this->Flash->error('Use a period like 2026-10.');

            return $this->redirect(['action' => 'financials']);
        }
        $table = $this->fetchTable('ErpPeriods');
        $row = $table->find()->where(['period' => $period])->first() ?? $table->newEntity(['period' => $period]);
        $row->locked = 1;
        $row->locked_on = date('Y-m-d H:i:s');
        $table->save($row);
        $this->audit('update', 'ErpPeriods', (int)$row->id, 'Locked ' . $period);
        $this->Flash->success($period . ' is locked.');

        return $this->redirect(['action' => 'financials']);
    }

    public function openingForm(): ?Response
    {
        if ($this->request->is('post')) {
            $accountId = (int)$this->request->getData('account_id');
            $date = trim((string)$this->request->getData('entry_date'));
            $debit = (float)$this->request->getData('debit');
            $credit = (float)$this->request->getData('credit');
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || ($debit <= 0 && $credit <= 0) || ($debit > 0 && $credit > 0)) {
                $this->Flash->error('Enter a date and either a debit or a credit.');
            } else {
                $error = ErpBooks::postOpening($accountId, $date, $debit, $credit);
                if ($error) {
                    $this->Flash->error($error);
                } else {
                    $this->Flash->success('Opening balance posted.');

                    return $this->redirect(['action' => 'trialBalance']);
                }
            }
        }
        $accounts = [];
        foreach ($this->fetchTable('ErpAccounts')->find()->where(['status' => 'active'])->orderBy(['code' => 'ASC']) as $row) {
            $accounts[(string)$row->id] = $row->code . ' — ' . $row->name;
        }
        $this->set('pageTitle', 'Opening balance');
        $this->set('accounts', $accounts);

        return null;
    }

    private function savePayment(): ?string
    {
        $direction = (string)$this->request->getData('direction') === 'in' ? 'in' : 'out';
        $kind = $direction === 'in' ? 'ar' : 'ap';
        $invoiceId = (int)$this->request->getData('invoice_id');
        $bankId = (int)$this->request->getData('bank_account_id');
        $amount = round((float)$this->request->getData('amount'), 2);
        $date = trim((string)$this->request->getData('paid_on'));
        $alias = $kind === 'ar' ? 'ErpArInvoices' : 'ErpApInvoices';
        $invoice = $this->fetchTable($alias)->find()->where(['id' => $invoiceId])->first();
        $bank = $this->fetchTable('ErpBankAccounts')->find()->where(['id' => $bankId, 'status' => 'active'])->first();
        if (!$invoice || !$bank || $amount <= 0 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return 'Choose the bank, the invoice, the date, and an amount.';
        }
        $due = round((float)$invoice->total - (float)$invoice->paid_amount, 2);
        if ($amount - $due > 0.001) {
            return 'Amount is above the outstanding ' . $this->money($due) . '.';
        }
        $locked = ErpBooks::lockMessage($date);
        if ($locked) {
            return $locked;
        }
        $table = $this->fetchTable('ErpPayments');
        $payment = $table->newEntity([
            'payment_no' => $this->nextDocNo('ErpPayments', 'payment_no', $direction === 'in' ? 'RCPT' : 'PAY'),
            'direction' => $direction,
            'bank_account_id' => $bank->id,
            'partner_id' => $invoice->partner_id,
            'invoice_kind' => $kind,
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'paid_on' => $date,
            'reference' => trim((string)$this->request->getData('reference')) ?: null,
            'reconciled' => 0,
            'created_by' => $this->adminId(),
            'created' => date('Y-m-d H:i:s'),
        ]);
        $table->save($payment);
        $invoice->paid_amount = round((float)$invoice->paid_amount + $amount, 2);
        $invoice->modified = date('Y-m-d H:i:s');
        $this->fetchTable($alias)->save($invoice);
        $error = ErpBooks::postPayment($payment, (int)$bank->account_id);
        if ($error) {
            return $error;
        }
        $this->audit('create', 'ErpPayments', (int)$payment->id, $payment->payment_no);
        $this->Flash->success($payment->payment_no . ' posted.');

        return null;
    }

    /**
     * @return array<string, string>
     */
    private function openInvoices(string $alias, string $number, string $who): array
    {
        $out = [];
        foreach ($this->fetchTable($alias)->find()->where(['status' => 'open'])->orderBy(['id' => 'DESC'])->limit(200) as $row) {
            $due = round((float)$row->total - (float)$row->paid_amount, 2);
            if ($due <= 0) {
                continue;
            }
            $out[(string)$row->id] = $row->{$number} . ' — ' . $this->partnerName((int)$row->partner_id) . ' — ' . $this->money($due) . ' due';
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    private function bankOptions(): array
    {
        $out = [];
        foreach ($this->fetchTable('ErpBankAccounts')->find()->where(['status' => 'active'])->orderBy(['name' => 'ASC']) as $row) {
            $out[(string)$row->id] = $row->name;
        }

        return $out;
    }

    private function report(string $title, string $kind): void
    {
        $from = trim((string)$this->request->getQuery('from'));
        $to = trim((string)$this->request->getQuery('to'));
        $rows = ErpBooks::trialBalance($from !== '' ? $from : null, $to !== '' ? $to : null);
        $this->set('pageTitle', $title);
        $this->set(compact('title', 'kind', 'from', 'to', 'rows'));
        $this->render('report');
    }

    /**
     * @return array{taxable: float, gst: float}
     */
    private function gstTotal(string $table, string $dateColumn, string $from, string $to): array
    {
        $sql = "SELECT COALESCE(SUM(taxable),0) taxable, COALESCE(SUM(gst),0) gst FROM {$table} WHERE status = 'open'";
        $params = [];
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
            $sql .= " AND {$dateColumn} >= :from";
            $params['from'] = $from;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $sql .= " AND {$dateColumn} <= :to";
            $params['to'] = $to;
        }
        $row = $this->fetchTable('ErpAccounts')->getConnection()->execute($sql, $params)->fetch('assoc');

        return ['taxable' => (float)$row['taxable'], 'gst' => (float)$row['gst']];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function gstLines(string $table, string $number, string $dateColumn, string $from, string $to): array
    {
        $sql = "SELECT i.{$number} doc_no, i.{$dateColumn} doc_date, i.taxable, i.gst, i.total, p.name partner_name, p.gstin
                FROM {$table} i LEFT JOIN erp_partners p ON p.id = i.partner_id WHERE i.status = 'open'";
        $params = [];
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
            $sql .= " AND i.{$dateColumn} >= :from";
            $params['from'] = $from;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $sql .= " AND i.{$dateColumn} <= :to";
            $params['to'] = $to;
        }
        $sql .= ' ORDER BY i.id DESC LIMIT 300';

        return $this->fetchTable('ErpAccounts')->getConnection()->execute($sql, $params)->fetchAll('assoc');
    }
}
