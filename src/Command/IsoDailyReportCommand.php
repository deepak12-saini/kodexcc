<?php
declare(strict_types=1);

namespace App\Command;

use App\Utility\IsoDailyReport;
use App\Utility\SmtpMail;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Sends the director's morning non-conformance email.
 * Run once a day: php bin/cake.php iso_daily_report
 */
class IsoDailyReportCommand extends Command
{
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Email the director the daily non-conformance report.');
        $parser->addOption('dry-run', [
            'boolean' => true,
            'help' => 'Build the report and do not send it.',
        ]);
        $parser->addOption('force', [
            'boolean' => true,
            'help' => 'Send again even if a report was already sent today.',
        ]);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io): int
    {
        $stamp = TMP . 'iso_daily_sent.txt';
        $today = date('Y-m-d');
        if (!$args->getOption('dry-run') && !$args->getOption('force') && is_file($stamp) && trim((string)file_get_contents($stamp)) === $today) {
            $io->out('Already sent today.');
            $this->logRun('already sent today');

            return static::CODE_SUCCESS;
        }

        $to = IsoDailyReport::recipient();
        $report = IsoDailyReport::build();
        $summary = 'Open ' . $report['open'] . ', closed yesterday ' . $report['closed'];
        $io->out('To ' . $to);
        $io->out($report['subject']);
        $io->out($summary);
        if ($args->getOption('dry-run')) {
            $this->logRun('dry-run, not sent to ' . $to . ' | ' . $summary);

            return static::CODE_SUCCESS;
        }

        try {
            SmtpMail::send($to, $report['subject'], $report['html']);
        } catch (\Throwable $e) {
            $io->err('Could not send the daily report. ' . $e->getMessage());
            $this->logRun('failed to ' . $to . ' | ' . $e->getMessage());

            return static::CODE_ERROR;
        }
        file_put_contents($stamp, $today);
        $io->out('Sent to ' . $to);
        $this->logRun('sent to ' . $to . ' | ' . $summary);

        return static::CODE_SUCCESS;
    }

    private function logRun(string $line): void
    {
        $dir = LOGS;
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents(
            $dir . 'iso_daily.log',
            date('Y-m-d H:i:s') . ' ' . $line . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}
