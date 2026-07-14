<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "Clearing old data...\n";
$pdo->exec("PRAGMA foreign_keys = OFF");
$pdo->exec("DELETE FROM product_images");
$pdo->exec("DELETE FROM products");
$pdo->exec("DELETE FROM categories");
$pdo->exec("PRAGMA foreign_keys = ON");

echo "Seeding database...\n";

$imagesDir = __DIR__ . '/../assets/images/products';

if (is_dir($imagesDir)) {
    // Get all category folders
    $categories = array_diff(scandir($imagesDir), array('.', '..'));
    
    foreach ($categories as $categoryFolder) {
        $categoryPath = $imagesDir . '/' . $categoryFolder;
        
        if (is_dir($categoryPath)) {
            // Create Category in DB
            $categoryName = $categoryFolder;
            $categorySlug = strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9\- ]/', '', $categoryName)));
            
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$categoryName, $categorySlug]);
            $categoryId = $pdo->lastInsertId();
            echo "Inserted category: {$categoryName}\n";
            
            // Get all products in this category folder
            $files = array_diff(scandir($categoryPath), array('.', '..'));
            
            foreach ($files as $file) {
                if (is_file($categoryPath . '/' . $file)) {
                    $nameInfo = pathinfo($file);
                    $productName = $nameInfo['filename'];
                    // Clean up name if it has double extensions like .jpg.jpeg
                    $productName = preg_replace('/\.(jpg|png|jpeg|webp)$/i', '', $productName);
                    $productName = trim(str_replace(['-', '_'], ' ', $productName));
                    $productName = ucwords($productName);

                    $slug = strtolower(str_replace(' ', '-', preg_replace('/[^A-Za-z0-9\- ]/', '', $productName)));
                    
                    // Ensure slug is not empty and unique
                    if (empty($slug)) {
                        $slug = 'product-' . uniqid();
                    } else {
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
                        $stmt->execute([$slug]);
                        if ($stmt->fetchColumn() > 0) {
                            $slug = $slug . '-' . uniqid();
                        }
                    }

                    $shortDesc = "Premium quality {$productName} for industrial and agricultural use.";
                    $desc = "The {$productName} is designed for high performance, reliability, and efficiency. It is highly suitable for commercial applications in the {$categoryName} industry.";
                    $price = rand(10000, 500000);

                    $stmt = $pdo->prepare("
                        INSERT INTO products (category_id, name, slug, short_description, description, price, price_visibility, featured, status)
                        VALUES (?, ?, ?, ?, ?, ?, 'public', 1, 'active')
                    ");
                    $stmt->execute([
                        $categoryId,
                        $productName,
                        $slug,
                        $shortDesc,
                        $desc,
                        $price
                    ]);
                    
                    $productId = $pdo->lastInsertId();

                    $imgPath = 'assets/images/products/' . $categoryFolder . '/' . $file;
                    
                    $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, 1)");
                    $stmt->execute([$productId, $imgPath]);
                    
                    echo "  - Inserted product: {$productName}\n";
                }
            }
        }
    }
} else {
    echo "Directory not found: $imagesDir\n";
}

echo "Seeding completed successfully.\n";
