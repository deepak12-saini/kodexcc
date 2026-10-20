<?php
declare(strict_types=1);

namespace App\Utility;

use Cake\Mailer\TransportFactory;
use RuntimeException;

/**
 * Sends one HTML message through the configured SMTP server.
 * Cake's SMTP transport was returning an empty reply on this host.
 */
class SmtpMail
{
    public static function send(string $to, string $subject, string $html): void
    {
        $cfg = (array)TransportFactory::getConfig('default');
        $host = (string)($cfg['host'] ?? 'smtp.gmail.com');
        $port = (int)($cfg['port'] ?? 587);
        $username = (string)($cfg['username'] ?? '');
        $password = (string)($cfg['password'] ?? '');
        $from = $username !== '' ? $username : 'info@kodexcc.com';
        $tls = array_key_exists('tls', $cfg) ? !empty($cfg['tls']) : $port === 587;

        $errno = 0;
        $err = '';
        $fp = stream_socket_client('tcp://' . $host . ':' . $port, $errno, $err, 20);
        if (!$fp) {
            throw new RuntimeException('Could not connect to the mail server. ' . $err);
        }
        stream_set_timeout($fp, 20);
        try {
            self::expect($fp, '220', 'connect');
            self::command($fp, 'EHLO kodexcc.com', '250');
            if ($tls) {
                self::command($fp, 'STARTTLS', '220');
                $crypto = STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
                if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                    $crypto |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
                }
                if (!stream_socket_enable_crypto($fp, true, $crypto)) {
                    throw new RuntimeException('The mail server did not start a secure connection.');
                }
                self::command($fp, 'EHLO kodexcc.com', '250');
            }
            self::command($fp, 'AUTH LOGIN', '334');
            self::command($fp, base64_encode($username), '334');
            self::command($fp, base64_encode($password), '235');
            self::command($fp, 'MAIL FROM:<' . $from . '>', '250');
            self::command($fp, 'RCPT TO:<' . $to . '>', '250');
            self::command($fp, 'DATA', '354');
            $payload = self::message($from, $to, $subject, $html);
            fwrite($fp, $payload . "\r\n.\r\n");
            self::expect($fp, '250', 'sent');
            self::command($fp, 'QUIT', '221');
        } finally {
            fclose($fp);
        }
    }

    private static function message(string $from, string $to, string $subject, string $html): string
    {
        $encoded = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $body = preg_replace('/^\./m', '..', str_replace(["\r\n", "\r"], "\n", $html));
        $body = str_replace("\n", "\r\n", (string)$body);
        $headers = [
            'Date: ' . date(DATE_RFC2822),
            'From: KodexCC <' . $from . '>',
            'To: <' . $to . '>',
            'Subject: ' . $encoded,
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
        ];

        return implode("\r\n", $headers) . "\r\n\r\n" . $body;
    }

    private static function command($fp, string $command, string $code): void
    {
        fwrite($fp, $command . "\r\n");
        $label = str_starts_with($command, 'AUTH') || preg_match('/^[A-Za-z0-9+\/=]{8,}$/', $command) ? 'auth' : strtok($command, ' ');
        self::expect($fp, $code, (string)$label);
    }

    private static function expect($fp, string $code, string $step): void
    {
        $data = '';
        $started = time();
        while (time() - $started < 20) {
            $chunk = fread($fp, 2048);
            if ($chunk === false) {
                break;
            }
            if ($chunk === '') {
                $meta = stream_get_meta_data($fp);
                if (!empty($meta['timed_out']) || !empty($meta['eof'])) {
                    break;
                }
                usleep(100000);
                continue;
            }
            $data .= $chunk;
            $lines = preg_split("/\r\n|\n/", rtrim($data, "\r\n"));
            $last = (string)end($lines);
            if (preg_match('/^(\d{3}) /', $last, $m)) {
                if ($m[1] !== $code) {
                    $reply = trim(str_replace(["\r", "\n"], ' ', $data));
                    if (str_contains($reply, 'Username and Password not accepted') || str_contains($reply, 'BadCredentials')) {
                        throw new RuntimeException('Gmail rejected this mailbox password. In the Google account, create an App Password and paste that 16-character password into config/app_local.php, then send again.');
                    }
                    throw new RuntimeException($step . ': ' . $reply);
                }

                return;
            }
        }
        throw new RuntimeException($step . ': ' . ($data !== '' ? trim(str_replace(["\r", "\n"], ' ', $data)) : 'The mail server did not answer.'));
    }
}
