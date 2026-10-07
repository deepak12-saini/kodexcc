<?php
declare(strict_types=1);

$root = dirname(__DIR__, 3);
require $root . '/vendor/autoload.php';
require $root . '/config/bootstrap.php';

use Cake\Datasource\ConnectionManager;

$c = ConnectionManager::get('default');
$sql = (string)file_get_contents(__DIR__ . '/create_it_module.sql');
$sql = preg_replace('/--.*$/m', '', $sql) ?? $sql;
$parts = array_filter(array_map('trim', explode(';', $sql)));
foreach ($parts as $stmt) {
    $c->execute($stmt);
}
echo "IT module tables ready\n";
