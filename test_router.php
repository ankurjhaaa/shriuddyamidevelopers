<?php
$_SERVER['REQUEST_URI'] = '/products/lemken-reversible-plough';
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if (preg_match('#^/products/([^/]+)/?$#', $uri, $matches)) {
    echo "Matched products regex!\n";
    $slug = $matches[1];
    echo "Slug: $slug\n";
    if (file_exists(__DIR__ . '/' . $slug . '.php')) {
        echo "File exists!\n";
    } else {
        echo "File does not exist, requiring product.php\n";
    }
} else {
    echo "Did not match regex!\n";
}
