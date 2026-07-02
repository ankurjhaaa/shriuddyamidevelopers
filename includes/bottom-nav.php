<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Sticky Bottom Navigation -->
<nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 z-50">
    <div class="max-w-md mx-auto flex justify-between items-center h-16 px-6">
        <a href="/" class="flex flex-col items-center justify-center w-full h-full text-xs <?php echo ($currentPage === 'index.php' || $currentPage === '') ? 'text-primary font-semibold' : 'text-gray-500 hover:text-primary'; ?>">
            <i class="fa-solid fa-house mb-1 text-lg"></i>
            <span>Home</span>
        </a>
        <a href="/categories.php" class="flex flex-col items-center justify-center w-full h-full text-xs <?php echo ($currentPage === 'categories.php') ? 'text-primary font-semibold' : 'text-gray-500 hover:text-primary'; ?>">
            <i class="fa-solid fa-layer-group mb-1 text-lg"></i>
            <span>Categories</span>
        </a>
        <a href="/search.php" class="flex flex-col items-center justify-center w-full h-full text-xs <?php echo ($currentPage === 'search.php') ? 'text-primary font-semibold' : 'text-gray-500 hover:text-primary'; ?>">
            <i class="fa-solid fa-magnifying-glass mb-1 text-lg"></i>
            <span>Search</span>
        </a>
        <a href="/favorites.php" class="flex flex-col items-center justify-center w-full h-full text-xs <?php echo ($currentPage === 'favorites.php') ? 'text-primary font-semibold' : 'text-gray-500 hover:text-primary'; ?>">
            <i class="fa-regular fa-heart mb-1 text-lg"></i>
            <span>Favorites</span>
        </a>
        <a href="/contact.php" class="flex flex-col items-center justify-center w-full h-full text-xs <?php echo ($currentPage === 'contact.php') ? 'text-primary font-semibold' : 'text-gray-500 hover:text-primary'; ?>">
            <i class="fa-solid fa-envelope mb-1 text-lg"></i>
            <span>Contact</span>
        </a>
    </div>
</nav>
