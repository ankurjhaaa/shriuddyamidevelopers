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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
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
                        primary: '#1e3a8a', // Deep Blue
                        secondary: '#172554', // Darker Blue
                        accent: '#ea580c', // Action Orange
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
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

<body class="bg-white text-gray-800 antialiased font-sans pb-16 md:pb-0">

    <!-- Top App Bar (Flat White Theme) -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <!-- Desktop Header -->
        <div class="hidden md:flex max-w-[1440px] mx-auto px-4 h-[70px] items-center justify-between gap-8">
            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <img src="/assets/images/logo.png" alt="<?php echo htmlspecialchars(getSetting('store_name')); ?> Logo"
                    class="h-12 w-auto object-contain">
            </a>

            <!-- Sleek Central Search Bar -->
            <div
                class="flex-grow max-w-2xl bg-white rounded-md border border-gray-300 focus-within:border-primary transition-colors overflow-hidden h-10 shadow-sm">
                <form action="/search.php" method="GET" class="flex w-full h-full">
                    <input type="text" name="q" placeholder="Search for products, categories or brands..."
                        class="flex-grow px-4 text-sm text-gray-800 outline-none w-full bg-transparent h-full"
                        value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit"
                        class="bg-gray-100 text-primary px-5 h-full hover:bg-gray-200 transition font-medium text-base flex items-center justify-center">
                        <i class="fa-solid fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-6 flex-shrink-0">
                <nav class="flex gap-6 items-center text-gray-600">
                    <a href="/favorites.php" class="hover:text-primary transition flex flex-col items-center">
                        <i class="fa-regular fa-heart text-xl"></i>
                    </a>
                    <a href="/contact.php" class="hover:text-primary transition flex flex-col items-center">
                        <i class="fa-regular fa-comment-dots text-xl"></i>
                    </a>
                    <a href="#" class="hover:text-primary transition flex flex-col items-center">
                        <i class="fa-regular fa-user text-xl"></i>
                    </a>
                </nav>
                <a href="<?php echo getWhatsappLink('I have a requirement'); ?>" target="_blank"
                    class="hidden lg:flex items-center gap-2 text-sm font-semibold text-white bg-primary px-4 py-2 rounded-md hover:bg-secondary transition shadow-sm">
                    Post Requirement
                </a>
            </div>
        </div>

        <!-- Mobile Header -->
        <div class="md:hidden relative">
            <!-- Top Row: Logo, Actions -->
            <div class="flex items-center justify-between px-4 h-16 border-b border-gray-200 bg-white relative z-50">
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