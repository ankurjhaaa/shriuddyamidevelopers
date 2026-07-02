<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = ['store_name', 'phone', 'whatsapp', 'address', 'gst'];
    foreach ($keys as $key) {
        if (isset($_POST[$key])) {
            $stmt = $pdo->prepare("UPDATE settings SET value = ? WHERE key = ?");
            $stmt->execute([$_POST[$key], $key]);
        }
    }
    header("Location: /admin/settings.php?success=1");
    exit;
}

$settings = getAllSettings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin</title>
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
            <a href="/admin/index.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-gauge mr-3 w-5 text-center text-blue-400"></i> Dashboard
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
            <a href="/admin/settings.php" class="bg-blue-800 text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md">
                <i class="fa-solid fa-gear mr-3 w-5 text-center text-blue-300"></i> Settings
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
            <a href="/admin_logout.php" class="text-sm"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </div>
        
        <!-- Top Nav Links for Mobile -->
        <div class="md:hidden bg-white shadow-sm flex overflow-x-auto p-2 gap-2 text-sm">
            <a href="/admin/index.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Dashboard</a>
            <a href="/admin/categories.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Categories</a>
            <a href="/admin/products.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Products</a>
            <a href="/admin/leads.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Leads</a>
            <a href="/admin/settings.php" class="px-3 py-1 bg-blue-100 text-blue-800 rounded">Settings</a>
        </div>

        <div class="p-6 max-w-4xl">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Settings</h1>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
                    Settings updated successfully.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form method="POST" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Store Name</label>
                            <input type="text" name="store_name" value="<?php echo htmlspecialchars($settings['store_name'] ?? ''); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">GST Number</label>
                            <input type="text" name="gst" value="<?php echo htmlspecialchars($settings['gst'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number (For Calls)</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                            <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            <p class="text-xs text-gray-500 mt-1">Include country code (e.g., +91)</p>
                        </div>
                        <div class="mb-4 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition font-medium">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
