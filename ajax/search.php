<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
$categoryId = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? '';

// Location awareness
$locations = require __DIR__ . '/../includes/locations.php';
$detectedLocation = '';
$cleanQuery = trim($query);

if ($cleanQuery !== '') {
    $pattern = '/\s+(in|near|at)\s+(' . implode('|', array_map('preg_quote', array_keys($locations))) . ')$/i';
    if (preg_match($pattern, $cleanQuery, $matches)) {
        $detectedLocation = $matches[2];
        $cleanQuery = trim(preg_replace($pattern, '', $cleanQuery));
    }
}

$sql = "
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
";
$params = [];

if ($cleanQuery !== '') {
    $sql .= " AND (p.name LIKE ? OR p.short_description LIKE ? OR c.name LIKE ?)";
    $params[] = "%$cleanQuery%";
    $params[] = "%$cleanQuery%";
    $params[] = "%$cleanQuery%";
}

if ($categoryId !== '') {
    $sql .= " AND c.slug = ?";
    $params[] = $categoryId;
}

if ($sort === 'price_asc') {
    $sql .= " ORDER BY p.price ASC LIMIT 20";
} else if ($sort === 'price_desc') {
    $sql .= " ORDER BY p.price DESC LIMIT 20";
} else {
    $sql .= " ORDER BY p.id DESC LIMIT 20";
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();
    
    // Add WhatsApp links and format prices for the JSON response
    foreach ($products as &$product) {
        $product['whatsapp_link'] = getWhatsappLink($product['name']);
        if ($product['price_visibility'] === 'public') {
            $product['formatted_price'] = formatPrice($product['price']);
        } else {
            $product['formatted_price'] = null;
        }
    }
    
    echo json_encode(['success' => true, 'data' => $products]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
