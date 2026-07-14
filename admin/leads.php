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

$pageTitle = 'Leads';
include __DIR__ . '/includes/header.php';
?>

<div class="mb-6 animate-fade-in">
    <p class="text-gray-500 text-sm">Manage customer inquiries and locked product requests.</p>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200 flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check text-xl text-red-500"></i>
        <span class="font-medium">Lead deleted successfully.</span>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold">Date</th>
                    <th class="px-6 py-4 font-semibold">Customer Name</th>
                    <th class="px-6 py-4 font-semibold">Contact</th>
                    <th class="px-6 py-4 font-semibold">Product of Interest</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-200"></i>
                            <p class="font-medium text-gray-600">No leads found yet.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $lead): ?>
                        <tr class="hover:bg-gray-50/80 transition duration-150">
                            <td class="px-6 py-4 text-gray-500 text-xs whitespace-nowrap font-medium"><?php echo date('M j, Y g:i A', strtotime($lead['created_at'])); ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900"><?php echo htmlspecialchars($lead['customer_name']); ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $lead['phone']); ?>" target="_blank" data-turbo="false" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-green-50 text-green-700 hover:bg-green-100 transition font-bold gap-2 text-sm border border-green-200 shadow-sm">
                                        <i class="fa-brands fa-whatsapp text-lg"></i> WA
                                    </a>
                                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $lead['phone']); ?>" data-turbo="false" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition font-bold gap-2 text-sm border border-blue-200 shadow-sm">
                                        <i class="fa-solid fa-phone"></i> Call
                                    </a>
                                </div>
                                <div class="mt-1 text-xs text-gray-500 font-medium ml-1">
                                    <?php echo htmlspecialchars($lead['phone']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    <?php echo htmlspecialchars($lead['product_name']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end">
                                    <a href="?delete=<?php echo $lead['id']; ?>" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition shadow-sm border border-red-100" onclick="return confirm('Are you sure you want to delete this lead?');">
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

<!-- Floating Action Button -->
<a href="/admin/leads_export.php" data-turbo="false" class="fixed bottom-24 md:bottom-10 right-6 bg-white border border-gray-200 text-gray-700 w-14 h-14 rounded-sm flex items-center justify-center hover:bg-gray-50 transition shadow-lg z-30 group hover:scale-105" title="Export CSV">
    <i class="fa-solid fa-file-csv text-green-600 text-xl transition-transform"></i>
</a>

<?php include __DIR__ . '/includes/footer.php'; ?>
