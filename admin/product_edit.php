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

$pageTitle = $id ? 'Edit Product' : 'Add Product';
include __DIR__ . '/includes/header.php';
?>

<div class="mb-6">
    <p class="text-gray-500 text-sm">Please fill in the product details.</p>
</div>

<form method="POST" action="" enctype="multipart/form-data" class="space-y-6 animate-slide-up" data-turbo="false">
    
    <!-- Main Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Basic Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 appearance-none">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200"><?php echo htmlspecialchars($product['short_description']); ?></textarea>
                <p class="text-xs text-gray-500 mt-2">A brief summary shown on product cards.</p>
            </div>
        </div>
    </div>

    <!-- Price & Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Pricing & Status</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Price (₹) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-gray-500">₹</span>
                    </div>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required class="w-full pl-8 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Price Visibility</label>
                <select name="price_visibility" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 appearance-none">
                    <option value="public" <?php echo $product['price_visibility'] == 'public' ? 'selected' : ''; ?>>Public (Show Normal)</option>
                    <option value="locked" <?php echo $product['price_visibility'] == 'locked' ? 'selected' : ''; ?>>Locked (Require Lead)</option>
                    <option value="hidden" <?php echo $product['price_visibility'] == 'hidden' ? 'selected' : ''; ?>>Hidden (Ask on WhatsApp)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 appearance-none">
                    <option value="active" <?php echo $product['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $product['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            
            <div class="md:col-span-3 flex items-center bg-blue-50/50 border border-blue-100 p-4 rounded-lg">
                <input type="checkbox" id="featured" name="featured" value="1" <?php echo $product['featured'] ? 'checked' : ''; ?> class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                <label for="featured" class="ml-3 block text-sm font-medium text-blue-900 cursor-pointer">
                    Mark as Featured Product (Highlights it on the homepage)
                </label>
            </div>
        </div>
    </div>

    <!-- Detailed Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Detailed Information</h2>
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Specifications</label>
                <textarea name="specifications" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200" placeholder="- Power: 1000W&#10;- Capacity: 5L"><?php echo htmlspecialchars($product['specifications']); ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Applications</label>
                <textarea name="applications" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200"><?php echo htmlspecialchars($product['applications']); ?></textarea>
            </div>
        </div>
    </div>

    <!-- Images -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-3">Images</h2>
        
        <?php if ($id && !empty($images)): ?>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-6">
                <?php foreach ($images as $img): ?>
                    <div class="relative bg-gray-50 border border-gray-200 rounded-lg p-2 group shadow-sm">
                        <img src="/<?php echo htmlspecialchars($img['image_path']); ?>" class="w-full h-32 object-cover rounded-md">
                        <?php if($img['is_primary']): ?>
                            <span class="absolute top-3 left-3 bg-blue-600 text-white text-[10px] uppercase tracking-wider font-bold px-2 py-1 rounded shadow-sm">Primary</span>
                        <?php endif; ?>
                        <a href="?id=<?php echo $id; ?>&delete_img=<?php echo $img['id']; ?>" data-turbo="false" onclick="return confirm('Delete this image?')" class="absolute top-3 right-3 bg-red-500 text-white w-7 h-7 flex items-center justify-center rounded-sm opacity-100 transition shadow hover:bg-red-600">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Upload New Images</label>
            <div class="relative w-full">
                <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition cursor-pointer border border-dashed border-gray-300 rounded-xl p-4 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>
            <p class="text-xs text-gray-500 mt-3 flex items-center gap-1"><i class="fa-solid fa-circle-info"></i> You can select multiple images. The first uploaded image will be marked as primary.</p>
        </div>
    </div>

    <!-- Submit -->
    <div class="flex flex-col sm:flex-row justify-end gap-4 pb-4">
        <a href="/admin/products.php" class="bg-white border border-gray-200 text-gray-700 px-8 py-3.5 rounded-lg hover:bg-gray-50 transition font-semibold shadow-sm text-center">
            Cancel
        </a>
        <button type="submit" class="bg-blue-600 text-white px-8 py-3.5 rounded-lg hover:bg-blue-700 transition font-semibold shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-save"></i> Save Product
        </button>
    </div>
    
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
