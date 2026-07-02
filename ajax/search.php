<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
$categoryId = $_GET['category'] ?? '';

$sql = "
    SELECT p.*, c.name as category_name, 
           (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.status = 'active'
";
$params = [];

if ($query !== '') {
    $sql .= " AND (p.name LIKE ? OR p.short_description LIKE ? OR c.name LIKE ?)";
    $params[] = "%$query%";
    $params[] = "%$query%";
    $params[] = "%$query%";
}

if ($categoryId !== '') {
    $sql .= " AND p.category_id = ?";
    $params[] = $categoryId;
}

$sql .= " ORDER BY p.id DESC LIMIT 20";

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
