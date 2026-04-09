<?php
declare(strict_types=1);

/**
 * Backfill product.category_id to first active category for rows where missing.
 *
 * Usage:
 *   php bin/backfill_product_category.php --apply
 *   php bin/backfill_product_category.php --dry-run
 */

$db = require __DIR__ . '/../config/mysqli_from_app.php';
$apply = in_array('--apply', $argv, true);
$dryRun = in_array('--dry-run', $argv, true) || !$apply;

$m = new mysqli($db['host'], $db['username'], $db['password'], $db['database'], (int)$db['port']);
if ($m->connect_errno) {
    fwrite(STDERR, $m->connect_error . PHP_EOL);
    exit(1);
}

$r = $m->query("SELECT id FROM category WHERE COALESCE(status,1)=1 ORDER BY id ASC LIMIT 1");
if (!$r || $r->num_rows === 0) {
    fwrite(STDERR, "No active category found. Import categories first." . PHP_EOL);
    exit(1);
}
$row = $r->fetch_assoc();
$categoryId = (int)$row['id'];
$r->close();

$countRes = $m->query("SELECT COUNT(*) c FROM product WHERE category_id IS NULL OR category_id=0");
$count = 0;
if ($countRes) {
    $countRow = $countRes->fetch_assoc();
    $count = (int)($countRow['c'] ?? 0);
    $countRes->close();
}

echo "Default category_id: {$categoryId}" . PHP_EOL;
echo "Rows needing backfill: {$count}" . PHP_EOL;

if ($dryRun) {
    echo "Mode: DRY RUN (no update). Use --apply to execute." . PHP_EOL;
    $m->close();
    exit(0);
}

if (!$m->query("UPDATE product SET category_id={$categoryId} WHERE category_id IS NULL OR category_id=0")) {
    fwrite(STDERR, "Update failed: " . $m->error . PHP_EOL);
    exit(1);
}

echo "Updated rows: " . $m->affected_rows . PHP_EOL;
$m->close();

