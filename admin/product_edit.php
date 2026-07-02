<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
checkAdminAuth();

$id = $_GET['id'] ?? null;
$product = [
    'category_id' => '', 'name' => '', 'short_description' => '', 
    'description' => '', 'specifications' => '', 'applications' => '', 
    'price' => '0.00', 'price_visibility' => 'public', 'featured' => 0, 'status' => 'active'
];
$images = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        header("Location: /admin/products.php");
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC");
    $stmt->execute([$id]);
    $images = $stmt->fetchAll();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['category_id'];
    $name = $_POST['name'];
    $slug = generateSlug($name);
    
    // Ensure slug is unique
    $slugQuery = "SELECT id FROM products WHERE slug = ?";
    $slugParams = [$slug];
    if ($id) {
        $slugQuery .= " AND id != ?";
        $slugParams[] = $id;
    }
    $stmt = $pdo->prepare($slugQuery);
    $stmt->execute($slugParams);
    if ($stmt->fetch()) {
        $slug = $slug . '-' . time();
    }
    
    $shortDesc = $_POST['short_description'];
    $desc = $_POST['description'];
    $specs = $_POST['specifications'];
    $apps = $_POST['applications'];
    $price = $_POST['price'];
    $visibility = $_POST['price_visibility'];
    $featured = isset($_POST['featured']) ? 1 : 0;
    $status = $_POST['status'];

    if ($id) {
        $stmt = $pdo->prepare("
            UPDATE products SET 
            category_id=?, name=?, slug=?, short_description=?, description=?, specifications=?, applications=?, price=?, price_visibility=?, featured=?, status=?
            WHERE id=?
        ");
        $stmt->execute([$categoryId, $name, $slug, $shortDesc, $desc, $specs, $apps, $price, $visibility, $featured, $status, $id]);
        $productId = $id;
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO products (category_id, name, slug, short_description, description, specifications, applications, price, price_visibility, featured, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$categoryId, $name, $slug, $shortDesc, $desc, $specs, $apps, $price, $visibility, $featured, $status]);
        $productId = $pdo->lastInsertId();
    }

    // Handle File Uploads
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $filename;
                
                if (move_uploaded_file($tmpName, $targetPath)) {
                    // Check if primary
                    $isPrimary = (count($images) === 0 && $key === 0) ? 1 : 0;
                    if ($id) {
                        $stmt = $pdo->prepare("SELECT count(*) FROM product_images WHERE product_id = ? AND is_primary = 1");
                        $stmt->execute([$id]);
                        if ($stmt->fetchColumn() == 0) $isPrimary = 1;
                    }
                    
                    $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, is_primary) VALUES (?, ?, ?)");
                    $stmt->execute([$productId, 'uploads/' . $filename, $isPrimary]);
                }
            }
        }
    }

    header("Location: /admin/products.php?msg=saved");
    exit;
}

// Handle image delete
if (isset($_GET['delete_img']) && $id) {
    $imgId = $_GET['delete_img'];
    $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE id = ? AND product_id = ?");
    $stmt->execute([$imgId, $id]);
    $img = $stmt->fetch();
    if ($img) {
        if (file_exists(__DIR__ . '/../' . $img['image_path'])) {
            unlink(__DIR__ . '/../' . $img['image_path']);
        }
        $pdo->prepare("DELETE FROM product_images WHERE id = ?")->execute([$imgId]);
    }
    header("Location: /admin/product_edit.php?id=" . $id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $id ? 'Edit' : 'Add'; ?> Product - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex h-screen overflow-hidden">
    
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
            <a href="/admin/categories.php" class="text-blue-100 hover:bg-blue-800 hover:text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center text-blue-400"></i> Categories
            </a>
            <a href="/admin/products.php" class="bg-blue-800 text-white group flex items-center px-3 py-2.5 text-sm font-medium rounded-md">
                <i class="fa-solid fa-box-open mr-3 w-5 text-center text-blue-300"></i> Products
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

        <div class="p-6 max-w-5xl mx-auto w-full">
            <div class="flex items-center mb-6">
                <a href="/admin/products.php" class="text-gray-500 hover:text-gray-700 mr-4">
                    <i class="fa-solid fa-arrow-left text-xl"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900"><?php echo $id ? 'Edit' : 'Add'; ?> Product</h1>
            </div>

            <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                
                <!-- Main Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                            <select name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                            <textarea name="short_description" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?php echo htmlspecialchars($product['short_description']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Price & Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Pricing & Status</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price (₹) *</label>
                            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price Visibility</label>
                            <select name="price_visibility" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                                <option value="public" <?php echo $product['price_visibility'] == 'public' ? 'selected' : ''; ?>>Public (Show Normal)</option>
                                <option value="locked" <?php echo $product['price_visibility'] == 'locked' ? 'selected' : ''; ?>>Locked (Require Lead)</option>
                                <option value="hidden" <?php echo $product['price_visibility'] == 'hidden' ? 'selected' : ''; ?>>Hidden (Ask on WhatsApp)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                                <option value="active" <?php echo $product['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $product['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="featured" name="featured" value="1" <?php echo $product['featured'] ? 'checked' : ''; ?> class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="featured" class="ml-2 block text-sm text-gray-900">
                                Mark as Featured Product
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Detailed Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Detailed Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                            <textarea name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Specifications</label>
                            <textarea name="specifications" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?php echo htmlspecialchars($product['specifications']); ?></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Applications</label>
                            <textarea name="applications" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition"><?php echo htmlspecialchars($product['applications']); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Images</h2>
                    
                    <?php if ($id && !empty($images)): ?>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <?php foreach ($images as $img): ?>
                                <div class="relative bg-gray-50 border border-gray-200 rounded-md p-1 group">
                                    <img src="/<?php echo htmlspecialchars($img['image_path']); ?>" class="w-full h-32 object-cover rounded">
                                    <?php if($img['is_primary']): ?>
                                        <span class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">Primary</span>
                                    <?php endif; ?>
                                    <a href="?id=<?php echo $id; ?>&delete_img=<?php echo $img['id']; ?>" onclick="return confirm('Delete image?')" class="absolute top-2 right-2 bg-red-500 text-white w-6 h-6 flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition shadow">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload New Images</label>
                        <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer border border-gray-200 rounded-md p-2">
                        <p class="text-xs text-gray-500 mt-1">You can select multiple images. First uploaded image will be primary.</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pb-10">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-bold text-lg shadow-sm">
                        Save Product
                    </button>
                </div>
                
            </form>
        </div>
    </main>
</body>
</html>
