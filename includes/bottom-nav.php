<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!-- Sticky Bottom Navigation -->
<nav class="fixed bottom-0 w-full bg-white border-t border-gray-200 z-50 md:hidden">
    <div class="max-w-md mx-auto flex justify-between items-center h-16 px-4">
        <a href="/" class="flex flex-col items-center justify-center w-full h-full text-[11px] <?php echo ($currentPage === 'index.php' || $currentPage === '') ? 'text-primary font-bold' : 'text-gray-500'; ?>">
            <i class="<?php echo ($currentPage === 'index.php' || $currentPage === '') ? 'fa-solid' : 'fa-solid'; ?> fa-house mb-1 text-xl"></i>
            <span>Home</span>
        </a>
        <a href="/search.php" class="flex flex-col items-center justify-center w-full h-full text-[11px] <?php echo ($currentPage === 'search.php') ? 'text-primary font-bold' : 'text-gray-500'; ?>">
            <i class="<?php echo ($currentPage === 'search.php') ? 'fa-solid' : 'fa-solid'; ?> fa-magnifying-glass mb-1 text-xl"></i>
            <span>Shop</span>
        </a>
        <a href="/favorites.php" class="flex flex-col items-center justify-center w-full h-full text-[11px] <?php echo ($currentPage === 'favorites.php') ? 'text-primary font-bold' : 'text-gray-500'; ?>">
            <i class="<?php echo ($currentPage === 'favorites.php') ? 'fa-solid' : 'fa-regular'; ?> fa-heart mb-1 text-xl"></i>
            <span>Favorites</span>
        </a>
        <a href="/contact.php" class="flex flex-col items-center justify-center w-full h-full text-[11px] <?php echo ($currentPage === 'contact.php') ? 'text-primary font-bold' : 'text-gray-500'; ?>">
            <i class="<?php echo ($currentPage === 'contact.php') ? 'fa-solid' : 'fa-regular'; ?> fa-envelope mb-1 text-xl"></i>
            <span>Contact</span>
        </a>
    </div>
</nav>
