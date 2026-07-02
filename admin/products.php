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
<body class="bg-gray-50 font-sans text-gray-800 antialiased overflow-hidden flex h-screen">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/50 z-40 hidden md:hidden transition-opacity opacity-0"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
            <a href="/" class="flex items-center gap-2 text-primary">
                <i class="fa-solid fa-tractor text-xl"></i>
                <span class="text-xl font-bold tracking-tight text-gray-900">AdminPanel</span>
            </a>
            <button id="closeSidebar" class="md:hidden text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <nav class="flex-grow py-6 px-4 space-y-1 overflow-y-auto no-scrollbar">
            <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Main Menu</p>
            <a href="/admin/index.php" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-chart-pie mr-3 w-5 text-center text-gray-400 group-hover:text-gray-600"></i> Dashboard
            </a>
            <a href="/admin/categories.php" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center text-gray-400 group-hover:text-gray-600"></i> Categories
            </a>
            <a href="/admin/products.php" class="bg-blue-50 text-primary group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg">
                <i class="fa-solid fa-box-open mr-3 w-5 text-center text-primary"></i> Products
            </a>
            <a href="/admin/leads.php" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-address-book mr-3 w-5 text-center text-gray-400 group-hover:text-gray-600"></i> Leads
            </a>
            <a href="/admin/settings.php" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-gear mr-3 w-5 text-center text-gray-400 group-hover:text-gray-600"></i> Settings
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <a href="/admin_logout.php" class="text-red-600 hover:bg-red-50 hover:text-red-700 flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-arrow-right-from-bracket mr-3 w-5 text-center"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50/50">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-30 shadow-[0_4px_24px_rgba(0,0,0,0.02)] sticky top-0">
            <div class="flex items-center">
                <button id="openSidebar" class="md:hidden mr-4 text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">Products</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm">
                    A
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 animate-fade-in">
                    <p class="text-gray-500 text-sm">Manage your product catalog.</p>
                    <a href="/admin/product_edit.php" class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-blue-800 transition text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-plus"></i> Add Product
                    </a>
                </div>
                
                <?php if (isset($_GET['msg'])): ?>
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3 animate-fade-in">
                        <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
                        <span class="font-medium">Action completed successfully.</span>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-max">
                            <thead>
                                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 font-medium">ID</th>
                                    <th class="px-6 py-4 font-medium">Name</th>
                                    <th class="px-6 py-4 font-medium">Category</th>
                                    <th class="px-6 py-4 font-medium">Price</th>
                                    <th class="px-6 py-4 font-medium">Status</th>
                                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No products found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $p): ?>
                                        <tr class="hover:bg-gray-50/50 transition duration-150 group">
                                            <td class="px-6 py-4 text-gray-500">#<?php echo $p['id']; ?></td>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                <?php echo htmlspecialchars($p['name']); ?>
                                                <?php if($p['featured']): ?>
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">Featured</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    <?php echo htmlspecialchars($p['category_name']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900">₹ <?php echo number_format($p['price'], 2); ?></td>
                                            <td class="px-6 py-4">
                                                <?php if($p['status'] === 'active'): ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-100">Active</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="/admin/product_edit.php?id=<?php echo $p['id']; ?>" class="w-8 h-8 rounded-lg bg-blue-50 text-primary hover:bg-blue-100 flex items-center justify-center transition">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $p['id']; ?>" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" onclick="return confirm('Delete this product?');">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </a>
                                                </div>
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
    </div>
    
    <script>
        // Simple sidebar toggle
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
            setTimeout(() => {
                backdrop.classList.toggle('opacity-0');
            }, 10);
        }

        openBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
