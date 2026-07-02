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
$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<!-- Dashboard Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 animate-fade-in">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Products</h3>
            <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-box-open"></i>
            </div>
        </div>
        <p class="text-4xl font-black text-gray-900"><?php echo $productsCount; ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Categories</h3>
            <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <p class="text-4xl font-black text-gray-900"><?php echo $categoriesCount; ?></p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Leads</h3>
            <div class="w-12 h-12 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <p class="text-4xl font-black text-gray-900"><?php echo $leadsCount; ?></p>
    </div>
</div>

<!-- Recent Leads Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h2 class="text-base font-bold text-gray-900">Recent Leads</h2>
        <a href="/admin/leads.php" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center gap-1 transition">
            View All <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-bold">Customer</th>
                    <th class="px-6 py-4 font-bold">Phone</th>
                    <th class="px-6 py-4 font-bold">Product</th>
                    <th class="px-6 py-4 font-bold">Date</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php if (empty($recentLeads)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No leads found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentLeads as $lead): ?>
                        <tr class="hover:bg-gray-50/80 transition duration-150">
                            <td class="px-6 py-4 font-bold text-gray-900"><?php echo htmlspecialchars($lead['customer_name']); ?></td>
                            <td class="px-6 py-4 text-gray-600 font-medium"><?php echo htmlspecialchars($lead['phone']); ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    <?php echo htmlspecialchars($lead['product_name']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-xs font-medium"><?php echo date('M j, Y g:i A', strtotime($lead['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
