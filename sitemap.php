<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$baseUrl = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];

function addUrl($url, $lastmod, $changefreq, $priority) {
    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($url) . "</loc>\n";
    if ($lastmod) echo "    <lastmod>" . $lastmod . "</lastmod>\n";
    echo "    <changefreq>" . $changefreq . "</changefreq>\n";
    echo "    <priority>" . $priority . "</priority>\n";
    echo "  </url>\n";
}

// 1. Static Pages
addUrl($baseUrl . '/', date('Y-m-d'), 'daily', '1.0');
addUrl($baseUrl . '/about.php', date('Y-m-d'), 'monthly', '0.6');
addUrl($baseUrl . '/contact.php', date('Y-m-d'), 'monthly', '0.6');
addUrl($baseUrl . '/search.php', date('Y-m-d'), 'daily', '0.9');

// 2. Category Pages
$categories = $pdo->query("SELECT slug FROM categories")->fetchAll();
foreach ($categories as $cat) {
    addUrl($baseUrl . '/category/' . $cat['slug'], date('Y-m-d'), 'weekly', '0.8');
}

// 3. Location Pages (Programmatic SEO)
$locations = require __DIR__ . '/includes/locations.php';
foreach ($locations as $locName => $coords) {
    $locSlug = strtolower(str_replace(' ', '-', $locName));
    addUrl($baseUrl . '/location/' . urlencode($locSlug), date('Y-m-d'), 'weekly', '0.7');
}

// 4. Product Pages
$products = $pdo->query("SELECT slug, created_at FROM products WHERE status = 'active'")->fetchAll();
foreach ($products as $prod) {
    // Format date as YYYY-MM-DD
    $date = date('Y-m-d', strtotime($prod['created_at']));
    addUrl($baseUrl . '/products/' . urlencode($prod['slug']), $date, 'weekly', '0.8');
}

// 5. Smart Search Combos (Product + Top Locations)
$topCities = array_slice(array_keys($locations), 0, 5); // Just top 5 cities so sitemap isn't millions of links
$topProducts = $pdo->query("SELECT name FROM products WHERE status = 'active' LIMIT 50")->fetchAll();

foreach ($topProducts as $prod) {
    foreach ($topCities as $city) {
        $locSlug = urlencode(strtolower(str_replace(' ', '-', $city)));
        $prodSlug = urlencode(strtolower(str_replace(' ', '-', $prod['name'])));
        addUrl($baseUrl . '/location/' . $locSlug . '/' . $prodSlug, date('Y-m-d'), 'weekly', '0.6');
    }
}

echo '</urlset>';
?>
