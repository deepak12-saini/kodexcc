<?php
declare(strict_types=1);

/**
 * Seed `product` rows from files in webroot/productimg.
 *
 * Usage:
 *   php bin/seed_products_from_productimg.php --apply
 *   php bin/seed_products_from_productimg.php --dry-run
 */

$root = dirname(__DIR__);
$db = require $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'mysqli_from_app.php';

$host = (string)($db['host'] ?? 'localhost');
$port = (int)($db['port'] ?? 3306);
$user = (string)($db['username'] ?? 'root');
$pass = (string)($db['password'] ?? '');
$name = (string)($db['database'] ?? '');

$apply = in_array('--apply', $argv, true);
$dryRun = in_array('--dry-run', $argv, true) || !$apply;

$mysqli = @new mysqli($host, $user, $pass, $name, $port);
if ($mysqli->connect_errno) {
    fwrite(STDERR, "DB connect failed: " . $mysqli->connect_error . PHP_EOL);
    exit(1);
}
$mysqli->set_charset('utf8mb4');

$table = 'product';
$productimgDir = $root . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'productimg';
if (!is_dir($productimgDir)) {
    fwrite(STDERR, "Folder not found: {$productimgDir}" . PHP_EOL);
    exit(1);
}

$columns = [];
$resCols = $mysqli->query("SHOW COLUMNS FROM `{$table}`");
while ($resCols && ($r = $resCols->fetch_assoc())) {
    $columns[] = (string)$r['Field'];
}
if ($resCols) {
    $resCols->close();
}

$has = static fn(string $col): bool => in_array($col, $columns, true);

$defaultCategoryId = null;
if ($has('category_id')) {
    $rCat = $mysqli->query("SELECT id FROM `category` WHERE COALESCE(status,1)=1 ORDER BY id ASC LIMIT 1");
    if ($rCat && ($cat = $rCat->fetch_assoc())) {
        $defaultCategoryId = isset($cat['id']) ? (int)$cat['id'] : null;
    }
    if ($rCat) {
        $rCat->close();
    }
}

$imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$docExts = ['pdf', 'doc', 'docx'];

$allFiles = scandir($productimgDir) ?: [];
$images = [];
$docsByBase = [];
foreach ($allFiles as $f) {
    if ($f === '.' || $f === '..') {
        continue;
    }
    $full = $productimgDir . DIRECTORY_SEPARATOR . $f;
    if (!is_file($full)) {
        continue;
    }
    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    $base = strtolower(pathinfo($f, PATHINFO_FILENAME));
    if (in_array($ext, $imageExts, true)) {
        $images[] = $f;
    } elseif (in_array($ext, $docExts, true)) {
        $docsByBase[$base] = $f;
    }
}

sort($images, SORT_NATURAL | SORT_FLAG_CASE);

$countScanned = 0;
$countPrepared = 0;
$countInserted = 0;

foreach ($images as $img) {
    $countScanned++;

    // Skip obvious generated thumbs/placeholders
    $imgLower = strtolower($img);
    if (str_contains($imgLower, 'thumnail_') || $imgLower === 'no-image.png' || $imgLower === 'no-image.jpeg') {
        continue;
    }

    $base = pathinfo($img, PATHINFO_FILENAME);
    $title = preg_replace('/\s+/', ' ', str_replace(['_', '-'], ' ', $base));
    $title = trim((string)$title);
    if ($title === '') {
        continue;
    }
    $slug = strtolower($title);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim((string)$slug, '-');

    $baseLower = strtolower($base);
    $tds = $docsByBase[$baseLower] ?? null;

    // Skip duplicate image rows
    $imgEsc = $mysqli->real_escape_string($img);
    $dup = $mysqli->query("SELECT id FROM `{$table}` WHERE `image`='{$imgEsc}' LIMIT 1");
    if ($dup && $dup->num_rows > 0) {
        $dup->close();
        continue;
    }
    if ($dup) {
        $dup->close();
    }

    $data = [];
    if ($has('title')) {
        $data['title'] = $title;
    }
    if ($has('name')) {
        $data['name'] = $title;
    }
    if ($has('slug')) {
        $data['slug'] = $slug;
    }
    if ($has('image')) {
        $data['image'] = $img;
    }
    if ($has('category_id') && $defaultCategoryId !== null) {
        $data['category_id'] = $defaultCategoryId;
    }
    if ($has('status')) {
        $data['status'] = 1;
    }
    if ($has('product_type')) {
        $data['product_type'] = 1;
    }
    if ($has('created')) {
        $data['created'] = date('Y-m-d H:i:s');
    }
    if ($has('modified')) {
        $data['modified'] = date('Y-m-d H:i:s');
    }
    if ($has('datasheet') && $tds !== null) {
        $data['datasheet'] = $tds;
    }

    if ($data === []) {
        continue;
    }
    $countPrepared++;

    if ($dryRun) {
        continue;
    }

    $cols = [];
    $vals = [];
    foreach ($data as $k => $v) {
        $cols[] = "`" . $k . "`";
        if ($v === null) {
            $vals[] = "NULL";
        } elseif (is_int($v) || is_float($v)) {
            $vals[] = (string)$v;
        } else {
            $vals[] = "'" . $mysqli->real_escape_string((string)$v) . "'";
        }
    }
    $sql = "INSERT INTO `{$table}` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ")";
    if ($mysqli->query($sql)) {
        $countInserted++;
    } else {
        fwrite(STDERR, "Insert failed for {$img}: {$mysqli->error}" . PHP_EOL);
    }
}

echo "Scanned images: {$countScanned}" . PHP_EOL;
echo "Prepared rows: {$countPrepared}" . PHP_EOL;
if ($dryRun) {
    echo "Mode: DRY RUN (no insert). Use --apply to insert." . PHP_EOL;
} else {
    echo "Inserted rows: {$countInserted}" . PHP_EOL;
}

$mysqli->close();

