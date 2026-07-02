<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

// Stats
$productsCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$categoriesCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$leadsCount = $pdo->query("SELECT COUNT(*) FROM leads")->fetchColumn();

// Recent Leads
$recentLeads = $pdo->query("
    SELECT l.*, p.name as product_name 
    FROM leads l 
    LEFT JOIN products p ON l.product_id = p.id 
    ORDER BY l.id DESC LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex h-screen overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white flex flex-col hidden md:flex">
        <div class="h-16 flex items-center px-6 border-b border-blue-800">
            <span class="text-xl font-bold tracking-tight">Store Admin</span>
        </div>
        <nav class="flex-grow py-4 px-3 space-y-1">
            <a href="/admin/index.php" class="bg-blue-800 text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md">
                <i class="fa-solid fa-gauge mr-3 w-5 text-center text-blue-300"></i> Dashboard
            </a>
            <a href="/admin/categories.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center text-blue-400"></i> Categories
            </a>
            <a href="/admin/products.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-box-open mr-3 w-5 text-center text-blue-400"></i> Products
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
        <!-- Mobile Header -->
        <div class="md:hidden bg-blue-900 text-white h-14 flex items-center justify-between px-4">
            <span class="font-bold">Store Admin</span>
            <!-- Mobile Menu Toggle (Simplified) -->
            <a href="/admin_logout.php" class="text-sm"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </div>
        
        <!-- Top Nav Links for Mobile -->
        <div class="md:hidden bg-white shadow-sm flex overflow-x-auto p-2 gap-2 text-sm">
            <a href="/admin/index.php" class="px-3 py-1 bg-blue-100 text-blue-800 rounded">Dashboard</a>
            <a href="/admin/categories.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Categories</a>
            <a href="/admin/products.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Products</a>
            <a href="/admin/leads.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Leads</a>
            <a href="/admin/settings.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Settings</a>
        </div>

        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Stat Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-4">
                        <i class="fa-solid fa-box-open text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $productsCount; ?></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 mr-4">
                        <i class="fa-solid fa-layer-group text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Categories</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $categoriesCount; ?></p>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 mr-4">
                        <i class="fa-solid fa-address-book text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Leads</p>
                        <p class="text-2xl font-bold text-gray-900"><?php echo $leadsCount; ?></p>
                    </div>
                </div>
            </div>

            <!-- Recent Leads -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Recent Leads</h2>
                    <a href="/admin/leads.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                <th class="px-6 py-3 font-medium">Name</th>
                                <th class="px-6 py-3 font-medium">Phone</th>
                                <th class="px-6 py-3 font-medium">Product</th>
                                <th class="px-6 py-3 font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <?php if (empty($recentLeads)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No leads found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentLeads as $lead): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3 font-medium text-gray-800"><?php echo htmlspecialchars($lead['customer_name']); ?></td>
                                        <td class="px-6 py-3 text-gray-600"><?php echo htmlspecialchars($lead['phone']); ?></td>
                                        <td class="px-6 py-3 text-gray-600"><?php echo htmlspecialchars($lead['product_name']); ?></td>
                                        <td class="px-6 py-3 text-gray-500"><?php echo date('M j, Y g:i A', strtotime($lead['created_at'])); ?></td>
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
