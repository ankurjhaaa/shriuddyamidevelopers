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
$pageTitle = 'Settings';
include __DIR__ . '/includes/header.php';
?>

<?php if (isset($_GET['success'])): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
        <span class="font-medium">Settings updated successfully.</span>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8 animate-slide-up">
    <form method="POST" action="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Store Name</label>
                <input type="text" name="store_name" value="<?php echo htmlspecialchars($settings['store_name'] ?? ''); ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">GST Number</label>
                <input type="text" name="gst" value="<?php echo htmlspecialchars($settings['gst'] ?? ''); ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number (For Calls)</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">WhatsApp Number</label>
                <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200">
                <p class="text-xs text-gray-500 mt-2"><i class="fa-solid fa-circle-info mr-1"></i>Include country code (e.g., +91)</p>
            </div>
            <div class="mb-4 md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                <textarea name="address" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
            </div>
        </div>
        <div class="mt-8 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Save Settings
            </button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
