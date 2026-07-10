<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

// Receive JSON payload
$data = json_decode(file_get_contents('php://input'), true);

$productId = $data['product_id'] ?? null;
$name = $data['name'] ?? '';
$phone = $data['phone'] ?? '';

if (!$productId || empty($name) || empty($phone)) {
    echo json_encode(['success' => false, 'error' => 'Missing fields']);
    exit;
}

try {
    // Insert Lead
    $stmt = $pdo->prepare("INSERT INTO leads (product_id, customer_name, phone) VALUES (?, ?, ?)");
    $stmt->execute([$productId, $name, $phone]);
    
    // Fetch real price
    $stmt = $pdo->prepare("SELECT price, price_visibility FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $productRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($productRow !== false) {
        $formattedPrice = formatPrice($productRow['price']);
        
        echo json_encode([
            'success' => true, 
            'formatted_price' => $formattedPrice,
            'raw_price' => $productRow['price']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Product not found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
