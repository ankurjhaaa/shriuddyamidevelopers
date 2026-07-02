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
            <a href="/admin/index.php" class="bg-blue-50 text-primary group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg">
                <i class="fa-solid fa-chart-pie mr-3 w-5 text-center text-primary"></i> Dashboard
            </a>
            <a href="/admin/categories.php" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center text-gray-400 group-hover:text-gray-600"></i> Categories
            </a>
            <a href="/admin/products.php" class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-box-open mr-3 w-5 text-center text-gray-400 group-hover:text-gray-600"></i> Products
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
                <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
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
                <!-- Dashboard Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 animate-fade-in">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-primary flex items-center justify-center">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $productsCount; ?></p>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Categories</h3>
                            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $categoriesCount; ?></p>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Leads</h3>
                            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i class="fa-solid fa-users"></i>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900"><?php echo $leadsCount; ?></p>
                    </div>
                </div>

                <!-- Recent Leads Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h2 class="text-base font-semibold text-gray-900">Recent Leads</h2>
                        <a href="/admin/leads.php" class="text-primary hover:text-blue-800 text-sm font-medium transition">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 font-medium">Customer</th>
                                    <th class="px-6 py-4 font-medium">Phone</th>
                                    <th class="px-6 py-4 font-medium">Product Interested</th>
                                    <th class="px-6 py-4 font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                <?php if (empty($recentLeads)): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No leads found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentLeads as $lead): ?>
                                        <tr class="hover:bg-gray-50/50 transition duration-150">
                                            <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($lead['customer_name']); ?></td>
                                            <td class="px-6 py-4 text-gray-600"><?php echo htmlspecialchars($lead['phone']); ?></td>
                                            <td class="px-6 py-4 text-gray-600">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-primary">
                                                    <?php echo htmlspecialchars($lead['product_name']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500 text-xs"><?php echo date('M j, Y g:i A', strtotime($lead['created_at'])); ?></td>
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
