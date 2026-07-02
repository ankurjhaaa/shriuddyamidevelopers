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
<body class="bg-gray-50 font-sans text-gray-800 antialiased overflow-hidden flex h-screen" x-data="{ modalOpen: false, modalMode: 'add', currentId: '', currentName: '' }">

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
            <a href="/admin/categories.php" class="bg-blue-50 text-primary group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center text-primary"></i> Categories
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
                <h1 class="text-xl font-semibold text-gray-800">Categories</h1>
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
                    <p class="text-gray-500 text-sm">Manage your product categories here.</p>
                    <button @click="modalOpen = true; modalMode = 'add'; currentName = ''; currentId = '';" class="bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-blue-800 transition text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-plus"></i> Add Category
                    </button>
                </div>
                
                <?php if (isset($_GET['msg'])): ?>
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3 animate-fade-in">
                        <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
                        <span class="font-medium">Action completed successfully.</span>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 font-medium">ID</th>
                                    <th class="px-6 py-4 font-medium">Name</th>
                                    <th class="px-6 py-4 font-medium">Slug</th>
                                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-gray-50">
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No categories found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr class="hover:bg-gray-50/50 transition duration-150 group">
                                            <td class="px-6 py-4 text-gray-500">#<?php echo $cat['id']; ?></td>
                                            <td class="px-6 py-4 font-medium text-gray-900"><?php echo htmlspecialchars($cat['name']); ?></td>
                                            <td class="px-6 py-4 text-gray-500 font-mono text-xs"><?php echo htmlspecialchars($cat['slug']); ?></td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button @click="modalOpen = true; modalMode = 'edit'; currentId = '<?php echo $cat['id']; ?>'; currentName = '<?php echo addslashes($cat['name']); ?>';" class="w-8 h-8 rounded-lg bg-blue-50 text-primary hover:bg-blue-100 flex items-center justify-center transition">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                    <a href="?delete=<?php echo $cat['id']; ?>" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition" onclick="return confirm('Delete this category?');">
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

    <!-- Modal Form -->
    <div x-show="modalOpen" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="modalOpen" x-transition class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <form method="POST" action="">
                    <input type="hidden" name="action" x-bind:value="modalMode">
                    <input type="hidden" name="id" x-bind:value="currentId">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6" id="modal-title" x-text="modalMode === 'add' ? 'Add Category' : 'Edit Category'"></h3>
                                <div class="mt-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                                    <input type="text" name="name" x-model="currentName" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition duration-200">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50/50 px-4 py-4 sm:px-6 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-5 py-2.5 bg-primary text-sm font-semibold text-white hover:bg-blue-800 focus:outline-none transition sm:w-auto">
                            Save
                        </button>
                        <button type="button" @click="modalOpen = false" class="w-full inline-flex justify-center rounded-lg border border-gray-200 px-5 py-2.5 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none transition sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
