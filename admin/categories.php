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

$pageTitle = 'Categories';
include __DIR__ . '/includes/header.php';
?>

<div class="mb-6 animate-fade-in">
    <p class="text-gray-500 text-sm">Manage product categories for your store.</p>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3 animate-fade-in">
        <i class="fa-solid fa-circle-check text-xl text-green-500"></i>
        <span class="font-medium">
            <?php 
                if($_GET['msg'] === 'added') echo "Category added successfully.";
                elseif($_GET['msg'] === 'updated') echo "Category updated successfully.";
                elseif($_GET['msg'] === 'deleted') echo "Category deleted successfully.";
            ?>
        </span>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-max">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">Name</th>
                    <th class="px-6 py-4 font-semibold">Slug</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-200"></i>
                            <p class="font-medium text-gray-600">No categories found.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-gray-50/80 transition duration-150">
                            <td class="px-6 py-4 text-gray-500 font-medium">#<?php echo $cat['id']; ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900"><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td class="px-6 py-4 text-gray-500"><code class="bg-gray-100 px-2 py-1 rounded text-xs"><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="editCategory(<?php echo $cat['id']; ?>, '<?php echo htmlspecialchars(addslashes($cat['name'])); ?>')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 flex items-center justify-center transition shadow-sm border border-blue-100">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>
                                    <a href="?delete=<?php echo $cat['id']; ?>" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 flex items-center justify-center transition shadow-sm border border-red-100" onclick="return confirm('Are you sure you want to delete this category?');">
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

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-sm shadow-xl w-full max-w-md overflow-hidden transform scale-100 transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Add New Category</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="" method="POST" class="p-6">
            <input type="hidden" name="action" value="add">
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-sm shadow-xl w-full max-w-md overflow-hidden transform scale-100 transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Edit Category</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="" method="POST" class="p-6">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                <input type="text" name="name" id="edit_name" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm">Cancel</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition shadow-sm">Update Category</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, name) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_name').value = name;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>

<!-- Floating Action Button -->
<button onclick="document.getElementById('addModal').classList.remove('hidden')" class="fixed bottom-24 md:bottom-10 right-6 bg-blue-600 text-white w-14 h-14 rounded-sm flex items-center justify-center hover:bg-blue-700 transition shadow-lg z-30 group hover:scale-105" title="Add Category">
    <i class="fa-solid fa-plus text-xl transition-transform"></i>
</button>

<?php include __DIR__ . '/includes/footer.php'; ?>
