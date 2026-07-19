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

<body class="bg-slate-50 text-gray-800 antialiased font-sans pb-16 md:pb-0">

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
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm transition-all duration-300">
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
                <button onclick="document.getElementById('desktop-search').classList.toggle('hidden')" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-primary hover:text-white transition-colors text-gray-600">
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
                    <button onclick="document.getElementById('mobile-search').classList.toggle('hidden')"
                        class="text-gray-700 hover:text-primary transition">
                        <i class="fa-solid fa-search text-[19px]"></i>
                    </button>
                    <a href="/favorites.php" class="text-gray-700 hover:text-primary transition"><i
                            class="fa-regular fa-heart text-[19px]"></i></a>
                    <a href="#" class="text-gray-700 hover:text-primary transition"><i
                            class="fa-regular fa-user text-[19px]"></i></a>
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
    </header>

    <!-- Main Content Area -->
    <main class="w-full bg-white min-h-screen relative">