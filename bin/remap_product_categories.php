<?php
declare(strict_types=1);

/**
 * Remap product.category_id using keyword matching against category slug/name.
 *
 * Usage:
 *   php bin/remap_product_categories.php --dry-run
 *   php bin/remap_product_categories.php --apply
 */

$db = require __DIR__ . '/../config/mysqli_from_app.php';
$apply = in_array('--apply', $argv, true);
$dryRun = in_array('--dry-run', $argv, true) || !$apply;

$m = new mysqli($db['host'], $db['username'], $db['password'], $db['database'], (int)$db['port']);
if ($m->connect_errno) {
    fwrite(STDERR, "DB connect failed: {$m->connect_error}" . PHP_EOL);
    exit(1);
}
$m->set_charset('utf8mb4');

// Load active categories.
$categories = [];
$rCat = $m->query("SELECT id, category_name, slug FROM category WHERE COALESCE(status,1)=1 ORDER BY id ASC");
if (!$rCat) {
    fwrite(STDERR, "Category query failed: {$m->error}" . PHP_EOL);
    exit(1);
}
while ($c = $rCat->fetch_assoc()) {
    $name = (string)($c['category_name'] ?? '');
    $slug = (string)($c['slug'] ?? '');
    $tokens = [];
    foreach ([$name, $slug] as $src) {
        $norm = strtolower($src);
        $norm = preg_replace('/[^a-z0-9]+/', ' ', $norm);
        $parts = preg_split('/\s+/', trim((string)$norm)) ?: [];
        foreach ($parts as $p) {
            if ($p !== '' && strlen($p) >= 3) {
                $tokens[$p] = true;
            }
        }
    }
    $categories[] = [
        'id' => (int)$c['id'],
        'name' => $name,
        'slug' => $slug,
        'tokens' => array_keys($tokens),
    ];
}
$rCat->close();

if ($categories === []) {
    fwrite(STDERR, "No active categories found." . PHP_EOL);
    exit(1);
}

// Load products.
$products = [];
$rProd = $m->query("SELECT id, title, image, slug, category_id FROM product ORDER BY id ASC");
if (!$rProd) {
    fwrite(STDERR, "Product query failed: {$m->error}" . PHP_EOL);
    exit(1);
}
while ($p = $rProd->fetch_assoc()) {
    $products[] = $p;
}
$rProd->close();

$updates = [];

foreach ($products as $p) {
    $id = (int)$p['id'];
    $currentCategoryId = (int)($p['category_id'] ?? 0);

    // Build searchable text from title/slug/image.
    $text = strtolower(
        trim(
            (string)($p['title'] ?? '') . ' ' .
            (string)($p['slug'] ?? '') . ' ' .
            (string)pathinfo((string)($p['image'] ?? ''), PATHINFO_FILENAME)
        )
    );
    $text = preg_replace('/[^a-z0-9]+/', ' ', $text);
    $text = trim((string)$text);
    if ($text === '') {
        continue;
    }

    $bestCategoryId = 0;
    $bestScore = 0;
    foreach ($categories as $cat) {
        $score = 0;
        foreach ($cat['tokens'] as $tok) {
            if ($tok !== '' && str_contains($text, $tok)) {
                $score++;
            }
        }
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestCategoryId = (int)$cat['id'];
        }
    }

    // Only remap when we found meaningful match.
    if ($bestCategoryId > 0 && $bestScore > 0 && $bestCategoryId !== $currentCategoryId) {
        $updates[] = [
            'id' => $id,
            'from' => $currentCategoryId,
            'to' => $bestCategoryId,
            'score' => $bestScore,
            'title' => (string)($p['title'] ?? ''),
            'image' => (string)($p['image'] ?? ''),
        ];
    }
}

echo "Total products: " . count($products) . PHP_EOL;
echo "Proposed category updates: " . count($updates) . PHP_EOL;

if ($updates !== []) {
    echo PHP_EOL . "Sample updates:" . PHP_EOL;
    $sample = array_slice($updates, 0, 20);
    foreach ($sample as $u) {
        echo "#{$u['id']} [{$u['from']} -> {$u['to']}] score={$u['score']} | {$u['title']} | {$u['image']}" . PHP_EOL;
    }
}

if ($dryRun) {
    echo PHP_EOL . "Mode: DRY RUN. Use --apply to execute updates." . PHP_EOL;
    $m->close();
    exit(0);
}

$applied = 0;
foreach ($updates as $u) {
    $id = (int)$u['id'];
    $to = (int)$u['to'];
    if ($m->query("UPDATE product SET category_id={$to} WHERE id={$id}")) {
        $applied++;
    }
}

echo PHP_EOL . "Applied updates: {$applied}" . PHP_EOL;
$m->close();

