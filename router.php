<?php
// router.php - Used ONLY for local PHP development server
if (php_sapi_name() !== 'cli-server') {
    die('this is only for the php development server');
}

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files as-is
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// 1. Location Rule: /location/kasba -> location.php?place=kasba
if (preg_match('#^/location/([^/]+)/?$#', $uri, $matches)) {
    $_GET['place'] = $matches[1];
    require __DIR__ . '/location.php';
    return true;
}

// 2. Category Rule: /category/1 -> search.php?category=1
if (preg_match('#^/category/([^/]+)/?$#', $uri, $matches)) {
    $_GET['category'] = $matches[1];
    require __DIR__ . '/search.php';
    return true;
}

// 3. Admin routes / admin login (if no extension)
if (strpos($uri, '/admin') === 0) {
    if (file_exists(__DIR__ . $uri . '.php')) {
        require __DIR__ . $uri . '.php';
        return true;
    }
    if ($uri == '/admin' || $uri == '/admin/') {
        require __DIR__ . '/admin/index.php';
        return true;
    }
    return false;
}

// 4. Fallback for product slug: /garud-cultivator -> product.php?slug=garud-cultivator
if (preg_match('#^/products/([^/]+)/?$#', $uri, $matches)) {
    $slug = $matches[1];
    
    // Ignore direct PHP file requests that were somehow not caught (e.g., if file doesn't exist)
    if (pathinfo($slug, PATHINFO_EXTENSION) === 'php') {
        return false; 
    }
    
    // Check if it's a known page without .php (optional for clean URLs on standard pages)
    if (file_exists(__DIR__ . '/' . $slug . '.php')) {
        require __DIR__ . '/' . $slug . '.php';
        return true;
    }
    
    // Otherwise treat as product slug
    $_GET['slug'] = $slug;
    require __DIR__ . '/product.php';
    return true;
}

require __DIR__ . '/index.php';
return true;
