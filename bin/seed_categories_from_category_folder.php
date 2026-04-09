<?php
declare(strict_types=1);

/**
 * Seed `category` rows from files in webroot/category.
 *
 * Usage:
 *   php bin/seed_categories_from_category_folder.php --apply
 *   php bin/seed_categories_from_category_folder.php --dry-run
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

$table = 'category';
$dir = $root . DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'category';
if (!is_dir($dir)) {
    fwrite(STDERR, "Folder not found: {$dir}" . PHP_EOL);
    exit(1);
}

$cols = [];
$resCols = $mysqli->query("SHOW COLUMNS FROM `{$table}`");
while ($resCols && ($r = $resCols->fetch_assoc())) {
    $cols[] = (string)$r['Field'];
}
if ($resCols) {
    $resCols->close();
}
$has = static fn(string $c): bool => in_array($c, $cols, true);

$files = scandir($dir) ?: [];
$imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

$countScanned = 0;
$countPrepared = 0;
$countInserted = 0;

foreach ($files as $f) {
    if ($f === '.' || $f === '..') {
        continue;
    }
    $full = $dir . DIRECTORY_SEPARATOR . $f;
    if (!is_file($full)) {
        continue;
    }
    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    if (!in_array($ext, $imageExts, true)) {
        continue;
    }
    $countScanned++;

    $base = (string)pathinfo($f, PATHINFO_FILENAME);
    $nameRaw = trim((string)preg_replace('/\s+/', ' ', str_replace(['_', '-'], ' ', $base)));
    if ($nameRaw === '') {
        continue;
    }
    $slug = strtolower($nameRaw);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim((string)$slug, '-');

    // Skip duplicates by slug or category_name.
    $slugEsc = $mysqli->real_escape_string($slug);
    $nameEsc = $mysqli->real_escape_string($nameRaw);
    $dup = $mysqli->query("SELECT id FROM `{$table}` WHERE `slug`='{$slugEsc}' OR `category_name`='{$nameEsc}' LIMIT 1");
    if ($dup && $dup->num_rows > 0) {
        $dup->close();
        continue;
    }
    if ($dup) {
        $dup->close();
    }

    $data = [];
    if ($has('category_name')) {
        $data['category_name'] = $nameRaw;
    }
    if ($has('name')) {
        $data['name'] = $nameRaw;
    }
    if ($has('title')) {
        $data['title'] = $nameRaw;
    }
    if ($has('slug')) {
        $data['slug'] = $slug;
    }
    if ($has('image')) {
        $data['image'] = $f;
    }
    if ($has('status')) {
        $data['status'] = 1;
    }
    if ($has('created')) {
        $data['created'] = date('Y-m-d H:i:s');
    }
    if ($has('modified')) {
        $data['modified'] = date('Y-m-d H:i:s');
    }

    if ($data === []) {
        continue;
    }
    $countPrepared++;
    if ($dryRun) {
        continue;
    }

    $insertCols = [];
    $insertVals = [];
    foreach ($data as $k => $v) {
        $insertCols[] = "`{$k}`";
        if ($v === null) {
            $insertVals[] = 'NULL';
        } elseif (is_int($v) || is_float($v)) {
            $insertVals[] = (string)$v;
        } else {
            $insertVals[] = "'" . $mysqli->real_escape_string((string)$v) . "'";
        }
    }
    $sql = "INSERT INTO `{$table}` (" . implode(', ', $insertCols) . ") VALUES (" . implode(', ', $insertVals) . ")";
    if ($mysqli->query($sql)) {
        $countInserted++;
    } else {
        fwrite(STDERR, "Insert failed for {$f}: {$mysqli->error}" . PHP_EOL);
    }
}

echo "Scanned category files: {$countScanned}" . PHP_EOL;
echo "Prepared categories: {$countPrepared}" . PHP_EOL;
if ($dryRun) {
    echo "Mode: DRY RUN (no insert). Use --apply to insert." . PHP_EOL;
} else {
    echo "Inserted categories: {$countInserted}" . PHP_EOL;
}

$mysqli->close();

