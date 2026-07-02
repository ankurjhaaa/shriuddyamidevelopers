<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Admin Dashboard' : 'Admin Dashboard'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SPA Router (Turbo) -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 antialiased overflow-hidden flex h-screen">

    <?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/50 z-40 hidden md:hidden transition-opacity opacity-0"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0f172a] text-white border-r border-gray-800 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-800">
            <a href="/admin/index.php" class="flex items-center gap-2 text-white">
                <i class="fa-solid fa-tractor text-xl text-blue-500"></i>
                <span class="text-xl font-bold tracking-tight text-white">AdminPanel</span>
            </a>
            <button id="closeSidebar" class="md:hidden text-gray-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <nav class="flex-grow py-6 px-4 space-y-1 overflow-y-auto no-scrollbar">
            <p class="px-2 text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-3">Main Menu</p>
            
            <a href="/admin/index.php" class="<?php echo $currentPage == 'index.php' ? 'bg-blue-600/20 text-blue-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-chart-pie mr-3 w-5 text-center <?php echo $currentPage == 'index.php' ? 'text-blue-400' : 'text-gray-400 group-hover:text-gray-300'; ?>"></i> Dashboard
            </a>
            
            <a href="/admin/categories.php" class="<?php echo $currentPage == 'categories.php' ? 'bg-blue-600/20 text-blue-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-layer-group mr-3 w-5 text-center <?php echo $currentPage == 'categories.php' ? 'text-blue-400' : 'text-gray-400 group-hover:text-gray-300'; ?>"></i> Categories
            </a>
            
            <a href="/admin/products.php" class="<?php echo in_array($currentPage, ['products.php', 'product_edit.php']) ? 'bg-blue-600/20 text-blue-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-box-open mr-3 w-5 text-center <?php echo in_array($currentPage, ['products.php', 'product_edit.php']) ? 'text-blue-400' : 'text-gray-400 group-hover:text-gray-300'; ?>"></i> Products
            </a>
            
            <a href="/admin/leads.php" class="<?php echo $currentPage == 'leads.php' ? 'bg-blue-600/20 text-blue-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-address-book mr-3 w-5 text-center <?php echo $currentPage == 'leads.php' ? 'text-blue-400' : 'text-gray-400 group-hover:text-gray-300'; ?>"></i> Leads
            </a>
            
            <a href="/admin/settings.php" class="<?php echo $currentPage == 'settings.php' ? 'bg-blue-600/20 text-blue-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white'; ?> group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-gear mr-3 w-5 text-center <?php echo $currentPage == 'settings.php' ? 'text-blue-400' : 'text-gray-400 group-hover:text-gray-300'; ?>"></i> Settings
            </a>
        </nav>
        <div class="p-4 border-t border-gray-800">
            <a href="/admin_logout.php" data-turbo="false" class="text-red-400 hover:bg-red-500/10 hover:text-red-300 flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition">
                <i class="fa-solid fa-arrow-right-from-bracket mr-3 w-5 text-center"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden bg-gray-50">
        <!-- Top Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-30 shadow-sm sticky top-0">
            <div class="flex items-center">
                <?php 
                $mainPages = ['index.php', 'categories.php', 'products.php', 'leads.php'];
                $isMainPage = in_array($currentPage, $mainPages);
                if (!$isMainPage): 
                ?>
                    <a href="javascript:history.back()" data-turbo="false" class="mr-4 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors">
                        <i class="fa-solid fa-arrow-left text-xl"></i>
                    </a>
                <?php endif; ?>
                <h1 class="text-xl font-bold text-gray-900"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?></h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="/" target="_blank" data-turbo="false" class="hidden sm:flex text-sm text-blue-600 hover:text-blue-800 font-medium items-center gap-1 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition">
                    View Website <i class="fa-solid fa-up-right-from-square text-[10px]"></i>
                </a>
                <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm border-2 border-white ring-2 ring-gray-100">
                    A
                </div>
            </div>
        </header>

        <!-- Main Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 pb-32 md:pb-24">
            <div class="max-w-7xl mx-auto space-y-6">
