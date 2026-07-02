<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

// Delete Lead
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: /admin/leads.php?msg=deleted");
    exit;
}

// Fetch all leads
$leads = $pdo->query("
    SELECT l.*, p.name as product_name 
    FROM leads l 
    LEFT JOIN products p ON l.product_id = p.id 
    ORDER BY l.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex h-screen overflow-hidden">
    
    <!-- Sidebar (Same as Dashboard) -->
    <aside class="w-64 bg-blue-900 text-white flex flex-col hidden md:flex">
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
            <a href="/admin/products.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-box-open mr-3 w-5 text-center text-blue-400"></i> Products
            </a>
            <a href="/admin/leads.php" class="bg-blue-800 text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md">
                <i class="fa-solid fa-address-book mr-3 w-5 text-center text-blue-300"></i> Leads
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
            <a href="/admin/products.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Products</a>
            <a href="/admin/leads.php" class="px-3 py-1 bg-blue-100 text-blue-800 rounded">Leads</a>
            <a href="/admin/settings.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Settings</a>
        </div>

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Leads</h1>
                <a href="/admin/leads_export.php" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-file-csv"></i> Export CSV
                </a>
            </div>
            
            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
                <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-200">
                    Lead deleted successfully.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                <th class="px-6 py-3 font-medium">Date</th>
                                <th class="px-6 py-3 font-medium">Customer Name</th>
                                <th class="px-6 py-3 font-medium">Phone Number</th>
                                <th class="px-6 py-3 font-medium">Product of Interest</th>
                                <th class="px-6 py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <?php if (empty($leads)): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fa-solid fa-folder-open text-3xl mb-2 text-gray-300"></i>
                                        <p>No leads found yet.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($leads as $lead): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap"><?php echo date('M j, Y g:i A', strtotime($lead['created_at'])); ?></td>
                                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($lead['customer_name']); ?></td>
                                        <td class="px-6 py-4">
                                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $lead['phone']); ?>" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                                <i class="fa-brands fa-whatsapp text-green-500"></i> <?php echo htmlspecialchars($lead['phone']); ?>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-gray-700"><?php echo htmlspecialchars($lead['product_name']); ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="?delete=<?php echo $lead['id']; ?>" class="text-red-500 hover:text-red-700 p-2" onclick="return confirm('Delete this lead?');">
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
