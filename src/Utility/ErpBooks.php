<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Datasource\ConnectionManager;

/**
 * Journals posted from purchase bills, sales invoices, and bank payments.
 */
class ErpBooks
{
    public static function lockMessage(string $date): ?string
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return null;
        }
        $period = substr($date, 0, 7);
        $locked = self::db()->execute('SELECT locked FROM erp_periods WHERE period = :period', ['period' => $period])->fetchColumn(0);

        return (int)$locked === 1 ? 'Period ' . $period . ' is locked. Ask the CA before posting into it.' : null;
    }

    public static function postPurchaseBill(object $bill): ?string
    {
        return self::replace('ap_invoice', (int)$bill->id, (string)$bill->bill_date, 'Purchase bill ' . $bill->bill_no, [
            '5000' => [(float)$bill->taxable, 0],
            '1300' => [(float)$bill->gst, 0],
            '2000' => [0, (float)$bill->total],
        ]);
    }

    public static function postSalesInvoice(object $invoice): ?string
    {
        return self::replace('ar_invoice', (int)$invoice->id, (string)$invoice->invoice_date, 'Sales invoice ' . $invoice->invoice_no, [
            '1100' => [(float)$invoice->total, 0],
            '4000' => [0, (float)$invoice->taxable],
            '2100' => [0, (float)$invoice->gst],
        ]);
    }

    public static function postPayment(object $payment, int $ledgerAccountId): ?string
    {
        $bank = self::accountCode($ledgerAccountId);
        if ($bank === null) {
            return 'This bank is not linked to a ledger account.';
        }
        $amount = (float)$payment->amount;
        if ($payment->direction === 'in') {
            $lines = [$bank => [$amount, 0.0], '1100' => [0.0, $amount]];
            $narration = 'Receipt ' . $payment->payment_no;
        } else {
            $lines = ['2000' => [$amount, 0.0], $bank => [0.0, $amount]];
            $narration = 'Payment ' . $payment->payment_no;
        }

        return self::replace('payment', (int)$payment->id, (string)$payment->paid_on, $narration, $lines);
    }

    public static function postOpening(int $accountId, string $date, float $debit, float $credit): ?string
    {
        $code = self::accountCode($accountId);
        if ($code === null) {
            return 'Choose an account.';
        }

        return self::replace('opening', $accountId, $date, 'Opening balance ' . $code, [
            $code => [$debit, $credit],
            '3000' => [$credit, $debit],
        ]);
    }

    /**
     * @param array<string, array{0: float, 1: float}> $lines code => [debit, credit]
     */
    public static function replace(string $sourceType, int $sourceId, string $date, string $narration, array $lines): ?string
    {
        $locked = self::lockMessage($date);
        if ($locked) {
            return $locked;
        }
        $debit = 0.0;
        $credit = 0.0;
        $ready = [];
        foreach ($lines as $code => $pair) {
            if (abs($pair[0]) < 0.001 && abs($pair[1]) < 0.001) {
                continue;
            }
            $accountId = self::db()->execute('SELECT id FROM erp_accounts WHERE code = :code', ['code' => $code])->fetchColumn(0);
            if (!$accountId) {
                return 'Ledger account ' . $code . ' is missing.';
            }
            $ready[] = [(int)$accountId, round($pair[0], 2), round($pair[1], 2)];
            $debit += $pair[0];
            $credit += $pair[1];
        }
        if (abs($debit - $credit) > 0.05) {
            return 'Journal does not balance.';
        }
        $db = self::db();
        $current = $db->execute(
            'SELECT id FROM erp_journals WHERE source_type = :type AND source_id = :id',
            ['type' => $sourceType, 'id' => $sourceId]
        )->fetch('assoc');
        if ($current) {
            $db->execute('DELETE FROM erp_journal_lines WHERE journal_id = :id', ['id' => $current['id']]);
            $journalId = (int)$current['id'];
            $db->execute(
                'UPDATE erp_journals SET entry_date = :date, narration = :narration WHERE id = :id',
                ['date' => $date, 'narration' => mb_substr($narration, 0, 255), 'id' => $journalId]
            );
        } else {
            $statement = $db->execute(
                'INSERT INTO erp_journals (entry_no, entry_date, source_type, source_id, narration, created) VALUES (:no, :date, :type, :id, :narration, :created)',
                [
                    'no' => self::nextNo(),
                    'date' => $date,
                    'type' => $sourceType,
                    'id' => $sourceId,
                    'narration' => mb_substr($narration, 0, 255),
                    'created' => date('Y-m-d H:i:s'),
                ]
            );
            $journalId = (int)$statement->lastInsertId();
        }
        foreach ($ready as $line) {
            $db->execute(
                'INSERT INTO erp_journal_lines (journal_id, account_id, debit, credit) VALUES (:journal, :account, :debit, :credit)',
                ['journal' => $journalId, 'account' => $line[0], 'debit' => $line[1], 'credit' => $line[2]]
            );
        }

        return null;
    }

    /**
     * @return list<array{code: string, name: string, account_type: string, debit: float, credit: float}>
     */
    public static function trialBalance(?string $from, ?string $to): array
    {
        $sql = 'SELECT a.code, a.name, a.account_type, COALESCE(SUM(l.debit),0) debit, COALESCE(SUM(l.credit),0) credit
                FROM erp_accounts a
                LEFT JOIN erp_journal_lines l ON l.account_id = a.id
                LEFT JOIN erp_journals j ON j.id = l.journal_id';
        $where = [];
        $params = [];
        if ($from && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
            $where[] = '(j.entry_date IS NULL OR j.entry_date >= :from)';
            $params['from'] = $from;
        }
        if ($to && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
            $where[] = '(j.entry_date IS NULL OR j.entry_date <= :to)';
            $params['to'] = $to;
        }
        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' GROUP BY a.id ORDER BY a.code';
        $rows = [];
        foreach (self::db()->execute($sql, $params)->fetchAll('assoc') as $row) {
            if ((float)$row['debit'] == 0.0 && (float)$row['credit'] == 0.0) {
                continue;
            }
            $row['debit'] = (float)$row['debit'];
            $row['credit'] = (float)$row['credit'];
            $rows[] = $row;
        }

        return $rows;
    }

    private static function accountCode(int $accountId): ?string
    {
        $code = self::db()->execute('SELECT code FROM erp_accounts WHERE id = :id', ['id' => $accountId])->fetchColumn(0);

        return $code ? (string)$code : null;
    }

    private static function nextNo(): string
    {
        $like = 'JE-' . date('Y') . '-%';
        $last = self::db()->execute('SELECT entry_no FROM erp_journals WHERE entry_no LIKE :like ORDER BY id DESC LIMIT 1', ['like' => $like])->fetchColumn(0);
        $n = 1;
        if ($last && preg_match('/(\d+)$/', (string)$last, $m)) {
            $n = (int)$m[1] + 1;
        }

        return sprintf('JE-%s-%04d', date('Y'), $n);
    }

    private static function db(): \Cake\Database\Connection
    {
        return ConnectionManager::get('default');
    }
}
