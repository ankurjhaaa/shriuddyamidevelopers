<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: /admin_login.php", true, 303);
    exit;
}

$privateKey = 'private_aGDWVTd7vtQ9aOw49w4q9khU1Cc=';
$authHeader = 'Authorization: Basic ' . base64_encode($privateKey . ':');

function uploadToImageKit($tmpFile, $fileName, $authHeader) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://upload.imagekit.io/api/v1/files/upload");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    $cFile = curl_file_create($tmpFile);
    $postData = array(
        'file' => $cFile,
        'fileName' => $fileName,
        'useUniqueFileName' => 'true',
        'folder' => '/purneamachinebazaar/carousel'
    );
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader));
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($result, true);
    return $response['url'] ?? null;
}

function deleteFromImageKit($url, $authHeader) {
    if (!$url) return;
    
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '';
    $fileName = basename($path);
    
    if (!$fileName) return;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.imagekit.io/v1/files?searchQuery=" . urlencode("name=\"$fileName\""));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader));
    $result = curl_exec($ch);
    curl_close($ch);
    
    $response = json_decode($result, true);
    if (!empty($response) && isset($response[0]['fileId'])) {
        $fileId = $response[0]['fileId'];
        
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, "https://api.imagekit.io/v1/files/" . $fileId);
        curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array($authHeader));
        curl_exec($ch2);
        curl_close($ch2);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $orderIndex = (int)$_POST['order_index'];
        
        $deskUrl = '';
        $mobileUrl = '';
        
        if (isset($_FILES['desktop_image']) && $_FILES['desktop_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['desktop_image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['err'] = "Desktop image error. The file might be too large (must be under " . ini_get('upload_max_filesize') . ").";
                header("Location: carousel.php", true, 303);
                exit;
            }
            $deskUrl = uploadToImageKit($_FILES['desktop_image']['tmp_name'], $_FILES['desktop_image']['name'], $authHeader);
        }
        
        if (isset($_FILES['mobile_image']) && $_FILES['mobile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['mobile_image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['err'] = "Mobile image error. The file might be too large (must be under " . ini_get('upload_max_filesize') . ").";
                header("Location: carousel.php", true, 303);
                exit;
            }
            $mobileUrl = uploadToImageKit($_FILES['mobile_image']['tmp_name'], $_FILES['mobile_image']['name'], $authHeader);
        }
        
        if ($deskUrl && $mobileUrl) {
            $stmt = $pdo->prepare("INSERT INTO carousel_banners (desktop_image_url, mobile_image_url, order_index) VALUES (?, ?, ?)");
            $stmt->execute([$deskUrl, $mobileUrl, $orderIndex]);
            $_SESSION['msg'] = "Banner added successfully!";
        } else {
            $_SESSION['err'] = "Failed to upload images to ImageKit.";
        }
        header("Location: carousel.php", true, 303);
        exit;
    }
    if ($_POST['action'] === 'edit') {
        $id = (int)$_POST['id'];
        $orderIndex = (int)$_POST['order_index'];
        
        $stmt = $pdo->prepare("SELECT desktop_image_url, mobile_image_url FROM carousel_banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch();
        
        if (!$banner) {
            header("Location: carousel.php", true, 303);
            exit;
        }

        $deskUrl = $banner['desktop_image_url'];
        $mobileUrl = $banner['mobile_image_url'];
        
        if (isset($_FILES['desktop_image']) && $_FILES['desktop_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['desktop_image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['err'] = "Desktop image error. The file might be too large (must be under " . ini_get('upload_max_filesize') . ").";
                header("Location: carousel.php", true, 303);
                exit;
            }
            deleteFromImageKit($deskUrl, $authHeader);
            $deskUrl = uploadToImageKit($_FILES['desktop_image']['tmp_name'], $_FILES['desktop_image']['name'], $authHeader);
        }
        
        if (isset($_FILES['mobile_image']) && $_FILES['mobile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['mobile_image']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['err'] = "Mobile image error. The file might be too large (must be under " . ini_get('upload_max_filesize') . ").";
                header("Location: carousel.php", true, 303);
                exit;
            }
            deleteFromImageKit($mobileUrl, $authHeader);
            $mobileUrl = uploadToImageKit($_FILES['mobile_image']['tmp_name'], $_FILES['mobile_image']['name'], $authHeader);
        }
        
        if ($deskUrl && $mobileUrl) {
            $stmt = $pdo->prepare("UPDATE carousel_banners SET desktop_image_url = ?, mobile_image_url = ?, order_index = ? WHERE id = ?");
            $stmt->execute([$deskUrl, $mobileUrl, $orderIndex, $id]);
            $_SESSION['msg'] = "Banner updated successfully!";
        } else {
            $_SESSION['err'] = "Failed to upload new images.";
        }
        
        header("Location: carousel.php", true, 303);
        exit;
    }

    if ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        
        $stmt = $pdo->prepare("SELECT desktop_image_url, mobile_image_url FROM carousel_banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch();
        
        if ($banner) {
            deleteFromImageKit($banner['desktop_image_url'], $authHeader);
            deleteFromImageKit($banner['mobile_image_url'], $authHeader);
            
            $stmt = $pdo->prepare("DELETE FROM carousel_banners WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['msg'] = "Banner deleted successfully!";
        }
        
        header("Location: carousel.php", true, 303);
        exit;
    }
    
    if ($_POST['action'] === 'update_order') {
        $id = (int)$_POST['id'];
        $orderIndex = (int)$_POST['order_index'];
        $stmt = $pdo->prepare("UPDATE carousel_banners SET order_index = ? WHERE id = ?");
        $stmt->execute([$orderIndex, $id]);
        $_SESSION['msg'] = "Order updated!";
        header("Location: carousel.php", true, 303);
        exit;
    }
}

$pageTitle = 'Manage Banners';
$currentPage = 'carousel.php';
include __DIR__ . '/includes/header.php';

$stmt = $pdo->query("SELECT * FROM carousel_banners ORDER BY order_index ASC");
$banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
        <h2 class="text-lg font-bold text-gray-800">Hero Banners</h2>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center">
            <i class="fa-solid fa-plus mr-2"></i> Add New Banner
        </button>
    </div>
    
    <?php if (isset($_SESSION['msg'])): ?>
    <div class="p-4 bg-green-50 text-green-700 border-b border-green-100 font-medium text-sm">
        <i class="fa-solid fa-check-circle mr-2"></i> <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
    </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['err'])): ?>
    <div class="p-4 bg-red-50 text-red-700 border-b border-red-100 font-medium text-sm">
        <i class="fa-solid fa-triangle-exclamation mr-2"></i> <?php echo $_SESSION['err']; unset($_SESSION['err']); ?>
    </div>
    <?php endif; ?>

    <div class="p-6 overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 font-semibold">Preview (Desktop)</th>
                    <th class="px-6 py-3 font-semibold">Preview (Mobile)</th>
                    <th class="px-6 py-3 font-semibold">Order</th>
                    <th class="px-6 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banners as $banner): ?>
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <img src="<?php echo htmlspecialchars($banner['desktop_image_url']); ?>" alt="Desktop Banner" class="h-16 w-32 object-cover rounded shadow-sm border">
                    </td>
                    <td class="px-6 py-4">
                        <img src="<?php echo htmlspecialchars($banner['mobile_image_url']); ?>" alt="Mobile Banner" class="h-16 w-16 object-cover rounded shadow-sm border">
                    </td>
                    <td class="px-6 py-4">
                        <form method="POST" class="flex items-center gap-2">
                            <input type="hidden" name="action" value="update_order">
                            <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
                            <input type="number" name="order_index" value="<?php echo $banner['order_index']; ?>" class="w-16 px-2 py-1 border rounded text-sm text-center">
                            <button type="submit" class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-save"></i></button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button type="button" onclick="openEditModal(<?php echo $banner['id']; ?>, '<?php echo htmlspecialchars(addslashes($banner['desktop_image_url'])); ?>', '<?php echo htmlspecialchars(addslashes($banner['mobile_image_url'])); ?>', <?php echo $banner['order_index']; ?>)" class="text-blue-500 hover:text-blue-700 transition mr-3" title="Edit">
                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                        </button>
                        <form method="POST" onsubmit="return confirm('Delete this banner?');" class="inline-block">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Delete">
                                <i class="fa-solid fa-trash-can text-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($banners)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">No banners found. Add one above.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Banner Modal -->
<div id="addModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 text-lg">Add New Banner</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="add">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Desktop Image (Wide)</label>
                <input type="file" name="desktop_image" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 outline-none border border-gray-200 rounded p-1">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Image (Square/Vertical)</label>
                <input type="file" name="mobile_image" accept="image/*" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 outline-none border border-gray-200 rounded p-1">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Order Index</label>
                <input type="number" name="order_index" value="0" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none">
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 font-medium transition">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium transition shadow-sm">Upload & Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Banner Modal -->
<div id="editModal" class="fixed inset-0 bg-black/60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-md shadow-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 text-lg">Edit Banner</h3>
            <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="edit_id">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Desktop Image (Optional to keep existing)</label>
                <input type="file" name="desktop_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 outline-none border border-gray-200 rounded p-1">
                <div class="mt-2 text-xs text-gray-500 truncate" id="edit_desktop_preview"></div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Image (Optional to keep existing)</label>
                <input type="file" name="mobile_image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 outline-none border border-gray-200 rounded p-1">
                <div class="mt-2 text-xs text-gray-500 truncate" id="edit_mobile_preview"></div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Order Index</label>
                <input type="number" name="order_index" id="edit_order_index" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none">
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded text-gray-600 hover:bg-gray-50 font-medium transition">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium transition shadow-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, deskUrl, mobileUrl, orderIndex) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_order_index').value = orderIndex;
    
    document.getElementById('edit_desktop_preview').innerHTML = 'Current: <a href="'+deskUrl+'" target="_blank" class="text-blue-500 underline">View Image</a>';
    document.getElementById('edit_mobile_preview').innerHTML = 'Current: <a href="'+mobileUrl+'" target="_blank" class="text-blue-500 underline">View Image</a>';
    
    document.getElementById('editModal').classList.remove('hidden');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
