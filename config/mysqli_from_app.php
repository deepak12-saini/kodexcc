<?php
declare(strict_types=1);

/**
 * MySQL connection settings from the same config CakePHP uses (app.php + app_local.php).
 * Used by legacy webroot scripts (e.g. PDF generators) that cannot boot the full framework.
 *
 * @return array{host: string, port: int, username: string, password: string, database: string}
 */
$root = dirname(__DIR__);
require $root . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'paths.php';

$app = require $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php';
$localFile = $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app_local.php';
if (is_file($localFile)) {
    $local = require $localFile;
    $app = array_replace_recursive($app, $local);
}

$ds = $app['Datasources']['default'] ?? [];
$url = $ds['url'] ?? null;
if (is_string($url) && $url !== '') {
    $parts = parse_url($url);
    if ($parts !== false && !empty($parts['scheme']) && str_starts_with($parts['scheme'], 'mysql')) {
        return [
            'host' => $parts['host'] ?? 'localhost',
            'port' => isset($parts['port']) ? (int)$parts['port'] : 3306,
            'username' => $parts['user'] ?? 'root',
            'password' => $parts['pass'] ?? '',
            'database' => isset($parts['path']) ? ltrim((string)$parts['path'], '/') : '',
        ];
    }
}

return [
    'host' => (string)($ds['host'] ?? 'localhost'),
    'port' => isset($ds['port']) ? (int)$ds['port'] : 3306,
    'username' => (string)($ds['username'] ?? 'root'),
    'password' => (string)($ds['password'] ?? ''),
    'database' => (string)($ds['database'] ?? ''),
];
