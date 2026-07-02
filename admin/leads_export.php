<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=leads_' . date('Y-m-d') . '.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Date', 'Customer Name', 'Phone', 'Product ID', 'Product Name']);

$leads = $pdo->query("
    SELECT l.id, l.created_at, l.customer_name, l.phone, l.product_id, p.name as product_name 
    FROM leads l 
    LEFT JOIN products p ON l.product_id = p.id 
    ORDER BY l.id DESC
")->fetchAll();

foreach ($leads as $lead) {
    fputcsv($output, [
        $lead['id'],
        $lead['created_at'],
        $lead['customer_name'],
        $lead['phone'],
        $lead['product_id'],
        $lead['product_name']
    ]);
}

fclose($output);
exit;
