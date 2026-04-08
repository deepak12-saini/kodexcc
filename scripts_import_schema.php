<?php
$dsn = 'mysql:host=localhost;charset=utf8mb4';
$user = 'root';
$pass = '';
$sql = file_get_contents(__DIR__ . '/config/schema/init_schema.sql');
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec($sql);
    echo "Schema imported successfully\n";
} catch (Throwable $e) {
    echo "Import failed: " . $e->getMessage() . "\n";
    exit(1);
}