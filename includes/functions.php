<?php
require_once __DIR__ . '/db.php';

function getSetting($key) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE key = ?");
    $stmt->execute([$key]);
    return $stmt->fetchColumn() ?: '';
}

function getAllSettings() {
    global $pdo;
    $stmt = $pdo->query("SELECT key, value FROM settings");
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['key']] = $row['value'];
    }
    return $settings;
}

function generateSlug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}

function formatPrice($price) {
    return '₹ ' . number_format($price, 2);
}

function getWhatsappLink($productName = '') {
    $whatsappNumber = getSetting('whatsapp');
    $cleanNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
    $message = "Hello! I am interested in your products.";
    if ($productName) {
        $message = "Hello! I am interested in the product: " . $productName;
    }
    return "https://wa.me/" . $cleanNumber . "?text=" . urlencode($message);
}
