<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>
        <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?><?php echo htmlspecialchars(getSetting('store_name')); ?>
    </title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">

    <!-- SEO Meta Tags -->
    <?php
    $seoTitle = isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' . htmlspecialchars(getSetting('store_name')) : htmlspecialchars(getSetting('store_name'));
    $seoDescription = isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Welcome to ' . htmlspecialchars(getSetting('store_name')) . ' - Premium quality agriculture and industrial machines available near you across Bihar. Find the best dealers in Purnea and nearby.';
    $seoKeywords = isset($pageKeywords) ? htmlspecialchars($pageKeywords) : 'agriculture machines near me, industrial machines bihar, farming equipment near me, Purnea Machine Bazaar, tractors in bihar, cultivators near me, machinery store bihar';

    // Fetch all active products for the Mega Menu
    $megaMenuProducts = [];
    try {
        if (isset($pdo)) {
            $stmtMenu = $pdo->query("
                SELECT id, name, slug, 
                       (SELECT image_path FROM product_images WHERE product_id = products.id AND is_primary = 1 LIMIT 1) as image
                FROM products 
                WHERE status = 'active' 
                ORDER BY name ASC
            ");
            $megaMenuProducts = $stmtMenu->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Exception $e) {}
    ?>
    <meta name="description" content="<?php echo $seoDescription; ?>">
    <meta name="keywords" content="<?php echo $seoKeywords; ?>">
    <meta name="author" content="<?php echo htmlspecialchars(getSetting('store_name')); ?>">

    <!-- Open Graph / Social Media Meta Tags -->
    <?php
    $seoImage = isset($ogImage) ? $ogImage : 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/assets/images/desktop_banner.png';
    $seoUrl = isset($canonicalUrl) ? $canonicalUrl : 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
    ?>
    <meta property="og:title" content="<?php echo $seoTitle; ?>">
    <meta property="og:description" content="<?php echo $seoDescription; ?>">
    <meta property="og:type" content="<?php echo isset($ogType) ? $ogType : 'website'; ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars(getSetting('store_name')); ?>">
    <meta property="og:image" content="<?php echo $seoImage; ?>">
    <meta property="og:url" content="<?php echo $seoUrl; ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $seoTitle; ?>">
    <meta name="twitter:description" content="<?php echo $seoDescription; ?>">
    <meta name="twitter:image" content="<?php echo $seoImage; ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo $seoUrl; ?>">

    <!-- JSON-LD Structured Data (Local Business) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "<?php echo htmlspecialchars(getSetting('store_name')); ?>",
      "image": "<?php echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/assets/images/logo.png'; ?>",
      "url": "<?php echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST']; ?>",
      "telephone": "<?php echo htmlspecialchars(getSetting('phone')); ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo htmlspecialchars(getSetting('address')); ?>",
        "addressLocality": "Purnea",
        "addressRegion": "Bihar",
        "addressCountry": "IN"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": 25.7771,
        "longitude": 87.4753
      },
      "areaServed": {
        "@type": "State",
        "name": "Bihar"
      },
      "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": [
          "Monday",
          "Tuesday",
          "Wednesday",
          "Thursday",
          "Friday",
          "Saturday"
        ],
        "opens": "09:00",
        "closes": "18:00"
      },
      "sameAs": [
        "https://www.facebook.com/<?php echo urlencode(getSetting('store_name')); ?>",
        "https://www.instagram.com/<?php echo urlencode(getSetting('store_name')); ?>"
      ]
    }
    </script>

    <!-- Dynamic Page Specific Schema -->
    <?php if (isset($customSchema))
        echo $customSchema; ?>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Kalam:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Icons (FontAwesome via CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Swiper JS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- Tailwind CSS (CDN for rapid prototyping) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#f97316', // Vibrant Orange
                        secondary: '#ea580c', // Darker Orange
                        dark: '#111827', // Deep Gray/Black
                        accent: '#22c55e', // Green for WhatsApp
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        handwriting: ['Kalam', 'cursive'],
                    },
                    animation: {
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' },
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link href="/assets/css/app.css" rel="stylesheet">

    <!-- SPA Router (Turbo) -->
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.4/dist/turbo.es2017-umd.js"></script>

    <!-- Global Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js" defer></script>
    <script src="/assets/js/app.js?v=<?php echo time(); ?>" defer></script>
    <script src="/assets/js/bottom-sheet.js?v=<?php echo time(); ?>" defer></script>
</head>

<body class="bg-slate-50 text-gray-800 antialiased font-sans flex flex-col min-h-screen">

    <!-- Top Contact Bar (Orange) -->
    <div class="bg-primary text-white py-2 px-4 hidden md:block">
        <div class="max-w-[1440px] mx-auto flex justify-between items-center text-sm font-medium">
            <div class="flex items-center gap-6">
                <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" class="flex items-center gap-2 hover:text-white/80 transition-colors">
                    <i class="fa-solid fa-phone-volume"></i> <?php echo htmlspecialchars(getSetting('phone')); ?>
                </a>
                <a href="mailto:<?php echo htmlspecialchars(getSetting('email')); ?>" class="flex items-center gap-2 hover:text-white/80 transition-colors">
                    <i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars(getSetting('email')); ?>
                </a>
            </div>
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 bg-black/20 px-3 py-1 rounded-full text-xs">
                    <i class="fa-regular fa-clock"></i> Mon - Sat: 09:00 - 18:00
                </div>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-7 h-7 bg-black/20 rounded flex items-center justify-center hover:bg-black/40 transition-colors"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" class="w-7 h-7 bg-black/20 rounded flex items-center justify-center hover:bg-black/40 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="w-7 h-7 bg-black/20 rounded flex items-center justify-center hover:bg-black/40 transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar (Solid White) -->
    <header id="main-header" class="bg-white sticky top-0 z-50 transition-all duration-300">
        <!-- Desktop Header -->
        <div class="hidden md:flex max-w-[1440px] mx-auto px-4 h-[70px] items-center justify-between gap-8">
            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <img src="/assets/images/logo.png" alt="<?php echo htmlspecialchars(getSetting('store_name')); ?> Logo"
                    class="h-12 w-auto object-contain">
            </a>

            <!-- Center Navigation Links -->
            <nav class="hidden lg:flex items-center gap-8 font-bold text-[15px] text-gray-800 tracking-wide uppercase">
                <a href="/" class="hover:text-primary transition-colors border-b-2 border-transparent hover:border-primary py-2 <?php echo $_SERVER['REQUEST_URI'] == '/' ? 'text-primary border-primary' : ''; ?>">Home</a>
                <a href="/about.php" class="hover:text-primary transition-colors border-b-2 border-transparent hover:border-primary py-2">About Us</a>
                
                <div class="relative group py-2">
                    <a href="/search.php" class="hover:text-primary transition-colors flex items-center gap-1 border-b-2 border-transparent group-hover:border-primary">Products <i class="fa-solid fa-chevron-down text-[10px]"></i></a>
                    
                    <!-- Mega Menu Dropdown -->
                    <div class="absolute top-[100%] left-1/2 -translate-x-1/2 mt-0 w-[400px] bg-white border border-slate-200 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform translate-y-2 group-hover:translate-y-0">
                        <div class="p-4 border-b border-slate-100 bg-slate-50 rounded-t-xl">
                            <form action="/search.php" method="GET" class="relative">
                                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="mega-menu-search" name="q" placeholder="Search products directly..." class="w-full bg-white border border-slate-200 rounded-lg py-2.5 pl-10 pr-4 text-sm font-normal normal-case focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm">
                            </form>
                        </div>
                        <div class="max-h-[350px] overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-slate-300" id="mega-menu-list">
                            <?php if (!empty($megaMenuProducts)): ?>
                                <?php foreach ($megaMenuProducts as $menuProd): ?>
                                    <a href="/products/<?php echo urlencode($menuProd['slug']); ?>" class="mega-menu-item flex items-center gap-3 p-2 hover:bg-slate-50 rounded-lg transition-colors group/item">
                                        <div class="w-10 h-10 bg-white border border-slate-200 rounded-md flex items-center justify-center overflow-hidden flex-shrink-0">
                                            <?php if ($menuProd['image']): ?>
                                                <img src="/<?php echo htmlspecialchars($menuProd['image']); ?>" class="w-full h-full object-contain mix-blend-multiply" alt="">
                                            <?php else: ?>
                                                <i class="fa-solid fa-image text-slate-200 text-xs"></i>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800 group-hover/item:text-primary transition-colors normal-case tracking-normal"><?php echo htmlspecialchars($menuProd['name']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div id="mega-menu-no-results" class="hidden p-6 text-center text-gray-500 text-sm font-medium normal-case tracking-normal">
                                No products found matching your search.
                            </div>
                        </div>
                        <div class="p-3 border-t border-slate-100 bg-slate-50 rounded-b-xl text-center">
                            <a href="/search.php" class="text-primary text-sm font-bold hover:underline normal-case tracking-normal">View All Products</a>
                        </div>
                    </div>
                </div>
                
                <a href="/gallery.php" class="hover:text-primary transition-colors border-b-2 border-transparent hover:border-primary py-2">Gallery</a>
                <a href="/contact.php" class="hover:text-primary transition-colors border-b-2 border-transparent hover:border-primary py-2">Contact Us</a>
            </nav>

            <!-- Right Actions -->
            <div class="flex items-center gap-4 flex-shrink-0">
                <button onclick="openSidebarWithSearch()" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-primary hover:text-white transition-colors text-gray-600">
                    <i class="fa-solid fa-search"></i>
                </button>
                <a href="/favorites.php" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-primary hover:text-white transition-colors text-gray-600">
                    <i class="fa-regular fa-heart"></i>
                </a>
                <a href="<?php echo getWhatsappLink('I have a requirement'); ?>" target="_blank"
                    class="hidden lg:flex items-center gap-3 text-[15px] font-bold text-white bg-primary pl-6 pr-2 py-1.5 rounded-full hover:bg-secondary transition-all group shadow-md">
                    Enquire Now <span class="bg-dark text-white w-8 h-8 rounded-full flex items-center justify-center group-hover:bg-white group-hover:text-dark transition-colors"><i class="fa-solid fa-arrow-right"></i></span>
                </a>
            </div>
        </div>

        <!-- Mega Menu JS (Turbo Compatible) -->
        <script>
        document.addEventListener('input', function(e) {
            if (e.target && e.target.id === 'mega-menu-search') {
                const term = e.target.value.toLowerCase();
                let count = 0;
                const items = document.querySelectorAll('.mega-menu-item');
                const noResults = document.getElementById('mega-menu-no-results');
                
                if (!items || items.length === 0) return;
                
                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(term)) {
                        item.style.display = 'flex';
                        count++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (noResults) {
                    if (count === 0 && term !== '') {
                        noResults.classList.remove('hidden');
                    } else {
                        noResults.classList.add('hidden');
                    }
                }
            }
        });
        </script>

        <!-- Toggled Desktop Search Bar -->
        <div id="desktop-search" class="hidden absolute top-full left-0 w-full bg-white border-b border-gray-200 shadow-lg z-40 p-4">
            <div class="max-w-2xl mx-auto">
                <form action="/search.php" method="GET" class="flex w-full bg-gray-50 border border-gray-300 rounded-md h-12 overflow-hidden focus-within:border-primary shadow-inner">
                    <input type="text" name="q" placeholder="Search for products..." class="w-full px-4 text-gray-800 outline-none bg-transparent h-full" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit" class="bg-primary text-white px-6 h-full flex items-center justify-center hover:bg-secondary transition text-lg"><i class="fa-solid fa-search"></i></button>
                </form>
            </div>
        </div>

        <!-- Mobile Header -->
        <div class="md:hidden relative">
            <!-- Top Row: Logo, Actions -->
            <div class="flex items-center justify-between px-4 h-16 bg-white relative z-50">
                <div class="flex items-center">
                    <a href="/" class="flex items-center py-2">
                        <!-- Much larger logo for mobile -->
                        <img src="/assets/images/logo.png" alt="Logo" class="h-10 w-auto object-contain">
                    </a>
                </div>
                <div class="flex items-center gap-5">
                    <button onclick="openSidebarWithSearch()"
                        class="text-gray-700 hover:text-primary transition">
                        <i class="fa-solid fa-search text-[19px]"></i>
                    </button>
                    <a href="/favorites.php" class="text-gray-700 hover:text-primary transition"><i
                            class="fa-regular fa-heart text-[19px]"></i></a>

                    <button onclick="toggleSidebar()" class="text-gray-800 hover:text-primary transition ml-1">
                        <i class="fa-solid fa-bars text-[22px]"></i>
                    </button>
                </div>
            </div>

            <!-- Toggled Search Bar (Hidden by default) -->
            <div id="mobile-search"
                class="hidden p-3 bg-gray-50 border-b border-gray-200 absolute w-full left-0 z-40 shadow-lg top-16">
                <form action="/search.php" method="GET"
                    class="flex w-full bg-white border border-gray-300 rounded-md h-11 overflow-hidden focus-within:border-primary shadow-sm">
                    <input type="text" name="q" placeholder="Search products..."
                        class="w-full px-4 text-sm text-gray-800 outline-none bg-transparent h-full"
                        value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit"
                        class="bg-transparent text-primary px-5 h-full flex items-center justify-center hover:bg-gray-50 transition text-lg"><i
                            class="fa-solid fa-search"></i></button>
                </form>
            </div>
        </div>
        <!-- Premium Full-Screen Mobile Menu Sidebar Overlay -->
        <div id="full-screen-sidebar" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] opacity-0 transition-opacity duration-300 ease-out flex justify-end" onclick="if(event.target === this) toggleSidebar();">
            <div id="sidebar-panel" class="w-full md:w-[450px] lg:w-[480px] h-full bg-white flex flex-col shadow-2xl overflow-hidden relative transform translate-x-full transition-transform duration-300 ease-out">
                
                <!-- Sidebar Top Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
                    <a href="/" class="flex items-center gap-2">
                        <img src="/assets/images/logo.png" alt="<?php echo htmlspecialchars(getSetting('store_name')); ?>" class="h-9 w-auto object-contain">
                    </a>
                    <button onclick="toggleSidebar()" class="w-10 h-10 rounded-md bg-gray-100 text-slate-600 hover:bg-gray-200 transition flex items-center justify-center">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Scrollable Sidebar Body -->
                <div class="flex-1 overflow-y-auto px-6 py-6 flex flex-col gap-6 hide-scrollbar">
                    
                    <!-- Search Box in Sidebar -->
                    <div class="relative">
                        <form action="/search.php" method="GET" class="relative">
                            <input type="text" id="sidebar-search-input" name="q" placeholder="Search machines, tools..." autocomplete="off" class="w-full bg-gray-50 border border-gray-200 rounded-md py-3 pl-11 pr-4 text-sm text-slate-800 placeholder-gray-400 focus:outline-none focus:border-primary focus:bg-white transition">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </form>
                    </div>

                    <!-- Search Results Area (Hidden by Default, replaces menu when searching) -->
                    <div id="sidebar-search-results" class="hidden flex-1 flex flex-col gap-3"></div>

                    <!-- Main Navigation Links (Visible by default) -->
                    <div id="sidebar-menu-links" class="flex flex-col gap-6">
                        
                        <div class="flex flex-col gap-2">
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-1">Menu</span>
                            
                            <div class="grid grid-cols-1 gap-2">
                                <a href="/" class="flex items-center justify-between p-3.5 rounded-md bg-gray-50 hover:bg-orange-50 border border-gray-100 transition group">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-slate-600 group-hover:text-primary border border-gray-100 transition">
                                            <i class="fa-solid fa-house text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 group-hover:text-primary transition text-sm block">Home</span>
                                            <span class="text-[11px] text-gray-400 block">Main Landing Page</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition"></i>
                                </a>

                                <a href="/search.php" class="flex items-center justify-between p-3.5 rounded-md bg-gray-50 hover:bg-orange-50 border border-gray-100 transition group">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-slate-600 group-hover:text-primary border border-gray-100 transition">
                                            <i class="fa-solid fa-border-all text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 group-hover:text-primary transition text-sm block">Shop Machinery</span>
                                            <span class="text-[11px] text-gray-400 block">Browse All Products</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition"></i>
                                </a>

                                <a href="/categories.php" class="flex items-center justify-between p-3.5 rounded-md bg-gray-50 hover:bg-orange-50 border border-gray-100 transition group">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-slate-600 group-hover:text-primary border border-gray-100 transition">
                                            <i class="fa-solid fa-layer-group text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 group-hover:text-primary transition text-sm block">Categories</span>
                                            <span class="text-[11px] text-gray-400 block">Explore by Industry</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition"></i>
                                </a>

                                <a href="/gallery.php" class="flex items-center justify-between p-3.5 rounded-md bg-gray-50 hover:bg-orange-50 border border-gray-100 transition group">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-slate-600 group-hover:text-primary border border-gray-100 transition">
                                            <i class="fa-solid fa-images text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 group-hover:text-primary transition text-sm block">Gallery</span>
                                            <span class="text-[11px] text-gray-400 block">Photos & Videos</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition"></i>
                                </a>

                                <a href="/favorites.php" class="flex items-center justify-between p-3.5 rounded-md bg-gray-50 hover:bg-orange-50 border border-gray-100 transition group">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-slate-600 group-hover:text-primary border border-gray-100 transition">
                                            <i class="fa-solid fa-heart text-sm text-red-500"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 group-hover:text-primary transition text-sm block">Favorites</span>
                                            <span class="text-[11px] text-gray-400 block">Saved Machines</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition"></i>
                                </a>

                                <a href="/contact.php" class="flex items-center justify-between p-3.5 rounded-md bg-gray-50 hover:bg-orange-50 border border-gray-100 transition group">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-9 h-9 rounded-md bg-white flex items-center justify-center text-slate-600 group-hover:text-primary border border-gray-100 transition">
                                            <i class="fa-solid fa-headset text-sm"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 group-hover:text-primary transition text-sm block">Contact & Support</span>
                                            <span class="text-[11px] text-gray-400 block">Get Instant Quote</span>
                                        </div>
                                    </div>
                                    <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Support Card -->
                        <div class="mt-auto bg-slate-900 text-white rounded-md p-5 relative overflow-hidden">
                            <div class="relative z-10">
                                <span class="text-[10px] font-bold text-primary uppercase tracking-widest block mb-1">Need Quick Help?</span>
                                <h4 class="font-bold text-base mb-3">Speak to Machinery Experts</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone') ?? ''); ?>" class="flex items-center justify-center gap-2 bg-primary hover:bg-[#e66f00] text-white py-2.5 px-3 rounded-md text-xs font-bold transition">
                                        <i class="fa-solid fa-phone"></i> Call Support
                                    </a>
                                    <a href="<?php echo getWhatsappLink('Hi, I need help'); ?>" target="_blank" class="flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white py-2.5 px-3 rounded-md text-xs font-bold transition">
                                        <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <script>
            function toggleSidebar(focusSearch = false) {
                const sidebar = document.getElementById('full-screen-sidebar');
                const panel = document.getElementById('sidebar-panel');
                const searchInput = document.getElementById('sidebar-search-input');

                if (sidebar.classList.contains('hidden')) {
                    sidebar.classList.remove('hidden');
                    setTimeout(() => {
                        sidebar.classList.remove('opacity-0');
                        sidebar.classList.add('opacity-100');
                        panel.classList.remove('translate-x-full');
                        panel.classList.add('translate-x-0');

                        if (focusSearch && searchInput) {
                            setTimeout(() => {
                                searchInput.focus();
                                searchInput.select();
                            }, 150);
                        }
                    }, 10);
                    document.body.style.overflow = 'hidden';
                } else {
                    sidebar.classList.remove('opacity-100');
                    sidebar.classList.add('opacity-0');
                    panel.classList.remove('translate-x-0');
                    panel.classList.add('translate-x-full');

                    setTimeout(() => {
                        sidebar.classList.add('hidden');
                    }, 300);
                    document.body.style.overflow = '';
                }
            }

            function openSidebarWithSearch() {
                toggleSidebar(true);
            }

            function initSidebarSearch() {
                const input = document.getElementById('sidebar-search-input');
                const results = document.getElementById('sidebar-search-results');
                const menuLinks = document.getElementById('sidebar-menu-links');
                let debounceTimer;

                if (input && results && menuLinks) {
                    if (input.dataset.searchInitialized === 'true') return;
                    input.dataset.searchInitialized = 'true';

                    input.addEventListener('input', function() {
                        clearTimeout(debounceTimer);
                        const query = this.value.trim();
                        if (query.length < 1) {
                            results.classList.add('hidden');
                            results.innerHTML = '';
                            menuLinks.classList.remove('hidden');
                            return;
                        }

                        debounceTimer = setTimeout(() => {
                            fetch('/ajax/search.php?q=' + encodeURIComponent(query))
                                .then(res => res.json())
                                .then(data => {
                                    menuLinks.classList.add('hidden');
                                    if (data.success && data.data.length > 0) {
                                        let html = '<div class="flex flex-col gap-2"><span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider px-1">Found ' + data.data.length + ' Machines</span>';
                                        data.data.forEach(product => {
                                            const img = product.primary_image ? '/' + product.primary_image : '/assets/images/placeholder.jpg';
                                            const price = product.formatted_price ? `<span class="text-xs font-bold text-primary block mt-0.5">${product.formatted_price}</span>` : '';
                                            html += `
                                                <a href="/product.php?slug=${encodeURIComponent(product.slug)}" onclick="toggleSidebar()" class="flex items-center gap-3.5 p-3 rounded-md bg-gray-50 hover:bg-orange-50 border border-gray-100 transition group">
                                                    <img src="${img}" class="w-14 h-14 object-cover rounded-md bg-white border border-gray-200 flex-shrink-0" alt="${product.name}">
                                                    <div class="flex-1 min-w-0">
                                                        <h5 class="text-sm font-bold text-slate-800 group-hover:text-primary transition truncate">${product.name}</h5>
                                                        <span class="text-[11px] text-gray-400 block">${product.category_name || ''}</span>
                                                        ${price}
                                                    </div>
                                                    <i class="fa-solid fa-chevron-right text-xs text-gray-300 group-hover:text-primary transition"></i>
                                                </a>
                                            `;
                                        });
                                        html += '</div>';
                                        results.innerHTML = html;
                                        results.classList.remove('hidden');
                                    } else {
                                        results.innerHTML = '<div class="p-8 text-center text-sm text-gray-500 font-medium bg-gray-50 border border-gray-100 rounded-md">No machinery found matching "<span class="font-bold text-slate-800">' + query + '</span>"</div>';
                                        results.classList.remove('hidden');
                                    }
                                })
                                .catch(() => {
                                    results.classList.add('hidden');
                                    menuLinks.classList.remove('hidden');
                                });
                        }, 200);
                    });
                }
            }

            function handleHeaderScroll() {
                const header = document.getElementById('main-header');
                if (!header) return;
                if (window.scrollY > 15) {
                    header.classList.add('shadow-md', 'border-b', 'border-gray-200');
                } else {
                    header.classList.remove('shadow-md', 'border-b', 'border-gray-200');
                }
            }

            window.addEventListener('scroll', handleHeaderScroll);
            document.addEventListener('DOMContentLoaded', function() {
                initSidebarSearch();
                handleHeaderScroll();
            });
            document.addEventListener('turbo:load', function() {
                initSidebarSearch();
                handleHeaderScroll();
            });
        </script>
    </header>

    <!-- Main Content Area -->
    <main class="w-full flex-1 relative bg-slate-50">