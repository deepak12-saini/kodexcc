<?php
declare(strict_types=1);

$root = dirname(__DIR__, 3);
require $root . '/vendor/autoload.php';
require $root . '/config/bootstrap.php';

use Cake\Datasource\ConnectionManager;

$c = ConnectionManager::get('default');
$cols = $c->execute('SHOW COLUMNS FROM hr_leave_requests')->fetchAll('assoc');
$names = array_column($cols, 'Field');
$add = [];
if (!in_array('duration_type', $names, true)) {
    $add[] = "ADD COLUMN duration_type VARCHAR(20) NOT NULL DEFAULT 'full_day' AFTER days";
}
if (!in_array('half_day_session', $names, true)) {
    $add[] = 'ADD COLUMN half_day_session VARCHAR(20) NULL AFTER duration_type';
}
if (!in_array('start_time', $names, true)) {
    $add[] = 'ADD COLUMN start_time TIME NULL AFTER half_day_session';
}
if (!in_array('end_time', $names, true)) {
    $add[] = 'ADD COLUMN end_time TIME NULL AFTER start_time';
}
if ($add) {
    $c->execute('ALTER TABLE hr_leave_requests ' . implode(', ', $add));
    echo "altered\n";
} else {
    echo "already_ok\n";
}
