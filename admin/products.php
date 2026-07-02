<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Delete images
    $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
    $stmt->execute([$id]);
    $images = $stmt->fetchAll();
    foreach ($images as $img) {
        if (file_exists(__DIR__ . '/../' . $img['image_path'])) {
            unlink(__DIR__ . '/../' . $img['image_path']);
        }
    }
    // Delete from DB (CASCADE handles product_images)
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: /admin/products.php?msg=deleted");
    exit;
}

$products = $pdo->query("
    SELECT p.id, p.name, p.price, p.status, p.featured, c.name as category_name
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    ORDER BY p.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex h-screen overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white flex flex-col hidden md:flex">
        <!-- ... Sidebar Content ... -->
        <div class="h-16 flex items-center px-6 border-b border-blue-800">
            <span class="text-xl font-bold tracking-tight">Store Admin</span>
        </div>
        <nav class="flex-grow py-4 px-3 space-y-1">
            <a href="/admin/index.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-gauge mr-3 w-5 text-center text-blue-400"></i> Dashboard
            </a>
            <a href="/admin/categories.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center text-blue-400"></i> Categories
            </a>
            <a href="/admin/products.php" class="bg-blue-800 text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md">
                <i class="fa-solid fa-box-open mr-3 w-5 text-center text-blue-300"></i> Products
            </a>
            <a href="/admin/leads.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-address-book mr-3 w-5 text-center text-blue-400"></i> Leads
            </a>
            <a href="/admin/settings.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-gear mr-3 w-5 text-center text-blue-400"></i> Settings
            </a>
        </nav>
        <div class="p-4 border-t border-blue-800">
            <a href="/admin_logout.php" class="text-blue-200 hover:text-white flex items-center text-sm font-medium transition">
                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-gray-50 flex flex-col">
        <!-- Mobile Header & Nav -->
        <div class="md:hidden bg-blue-900 text-white h-14 flex items-center justify-between px-4">
            <span class="font-bold">Store Admin</span>
            <a href="/admin_logout.php" class="text-sm"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </div>
        <div class="md:hidden bg-white shadow-sm flex overflow-x-auto p-2 gap-2 text-sm">
            <a href="/admin/index.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Dashboard</a>
            <a href="/admin/categories.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Categories</a>
            <a href="/admin/products.php" class="px-3 py-1 bg-blue-100 text-blue-800 rounded">Products</a>
            <a href="/admin/leads.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Leads</a>
            <a href="/admin/settings.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Settings</a>
        </div>

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <a href="/admin/product_edit.php" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add Product
                </a>
            </div>
            
            <?php if (isset($_GET['msg'])): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
                    Action completed successfully.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                <th class="px-6 py-3 font-medium">ID</th>
                                <th class="px-6 py-3 font-medium">Name</th>
                                <th class="px-6 py-3 font-medium">Category</th>
                                <th class="px-6 py-3 font-medium">Price</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No products found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $p): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-500">#<?php echo $p['id']; ?></td>
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <?php echo htmlspecialchars($p['name']); ?>
                                            <?php if($p['featured']): ?>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Featured</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($p['category_name']); ?></td>
                                        <td class="px-6 py-4 text-gray-600">₹ <?php echo number_format($p['price'], 2); ?></td>
                                        <td class="px-6 py-4">
                                            <?php if($p['status'] === 'active'): ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="/admin/product_edit.php?id=<?php echo $p['id']; ?>" class="text-blue-500 hover:text-blue-700 p-2 mr-2">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <a href="?delete=<?php echo $p['id']; ?>" class="text-red-500 hover:text-red-700 p-2" onclick="return confirm('Delete this product?');">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
