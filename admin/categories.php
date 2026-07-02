<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

// Handle add/edit category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $name = $_POST['name'] ?? '';
    $slug = generateSlug($name);
    
    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        $stmt->execute([$name, $slug]);
        header("Location: /admin/categories.php?msg=added");
        exit;
    } elseif ($_POST['action'] === 'edit') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        $stmt->execute([$name, $slug, $id]);
        header("Location: /admin/categories.php?msg=updated");
        exit;
    }
}

// Handle delete category
if (isset($_GET['delete'])) {
    // Note: In real app, check if products exist first or use CASCADE
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: /admin/categories.php?msg=deleted");
    exit;
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AlpineJS for modal state -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans flex h-screen overflow-hidden" x-data="{ modalOpen: false, modalMode: 'add', currentId: '', currentName: '' }">
    
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
            <a href="/admin/categories.php" class="bg-blue-800 text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center text-blue-300"></i> Categories
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
        <!-- Mobile Header & Nav -->
        <div class="md:hidden bg-blue-900 text-white h-14 flex items-center justify-between px-4">
            <span class="font-bold">Store Admin</span>
            <a href="/admin_logout.php" class="text-sm"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
        </div>
        <div class="md:hidden bg-white shadow-sm flex overflow-x-auto p-2 gap-2 text-sm">
            <a href="/admin/index.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Dashboard</a>
            <a href="/admin/categories.php" class="px-3 py-1 bg-blue-100 text-blue-800 rounded">Categories</a>
            <a href="/admin/products.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Products</a>
            <a href="/admin/leads.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Leads</a>
            <a href="/admin/settings.php" class="px-3 py-1 bg-gray-100 text-gray-700 rounded">Settings</a>
        </div>

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                <button @click="modalOpen = true; modalMode = 'add'; currentName = ''; currentId = '';" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm font-medium flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Add Category
                </button>
            </div>
            
            <?php if (isset($_GET['msg'])): ?>
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
                    Action completed successfully.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-sm border-b border-gray-100">
                                <th class="px-6 py-3 font-medium">ID</th>
                                <th class="px-6 py-3 font-medium">Name</th>
                                <th class="px-6 py-3 font-medium">Slug</th>
                                <th class="px-6 py-3 font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No categories found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-gray-500">#<?php echo $cat['id']; ?></td>
                                        <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($cat['name']); ?></td>
                                        <td class="px-6 py-4 text-gray-500"><?php echo htmlspecialchars($cat['slug']); ?></td>
                                        <td class="px-6 py-4 text-right">
                                            <button @click="modalOpen = true; modalMode = 'edit'; currentId = '<?php echo $cat['id']; ?>'; currentName = '<?php echo addslashes($cat['name']); ?>';" class="text-blue-500 hover:text-blue-700 p-2 mr-2">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <a href="?delete=<?php echo $cat['id']; ?>" class="text-red-500 hover:text-red-700 p-2" onclick="return confirm('Delete this category?');">
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

    <!-- Modal Form -->
    <div x-show="modalOpen" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="modalOpen" x-transition class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST" action="">
                    <input type="hidden" name="action" x-bind:value="modalMode">
                    <input type="hidden" name="id" x-bind:value="currentId">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="modalMode === 'add' ? 'Add Category' : 'Edit Category'"></h3>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                                    <input type="text" name="name" x-model="currentName" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" @click="modalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
