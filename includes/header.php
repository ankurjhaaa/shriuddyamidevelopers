<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>
        <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?><?php echo htmlspecialchars(getSetting('store_name')); ?>
    </title>
    
    <!-- SEO Meta Tags -->
    <?php 
    $seoTitle = isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' . htmlspecialchars(getSetting('store_name')) : htmlspecialchars(getSetting('store_name'));
    $seoDescription = isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Welcome to ' . htmlspecialchars(getSetting('store_name')) . ' - Premium quality agriculture and industrial machines designed for durability and performance in Purnea and beyond.';
    $seoKeywords = isset($pageKeywords) ? htmlspecialchars($pageKeywords) : 'agriculture machines, industrial machines, farming equipment, Purnea Machine Baazar, tractors, cultivators, machinery store';
    ?>
    <meta name="description" content="<?php echo $seoDescription; ?>">
    <meta name="keywords" content="<?php echo $seoKeywords; ?>">
    <meta name="author" content="<?php echo htmlspecialchars(getSetting('store_name')); ?>">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="<?php echo $seoTitle; ?>">
    <meta property="og:description" content="<?php echo $seoDescription; ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?php echo htmlspecialchars(getSetting('store_name')); ?>">
    <meta property="og:image" content="/assets/images/desktop_banner.png">
    
    <!-- Canonical URL (Basic Implementation) -->
    <link rel="canonical" href="<?php echo 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>">

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
    <?php if (isset($customSchema)) echo $customSchema; ?>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                        primary: '#00a699', // IndiaMART Teal
                        secondary: '#008378', // Darker Teal
                        accent: '#ff5722', // Action Orange
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delayed': 'float 6s ease-in-out 3s infinite',
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
    <script src="/assets/js/app.js" defer></script>
    <script src="/assets/js/bottom-sheet.js" defer></script>
</head>

<body class="bg-white text-gray-800 antialiased font-sans pb-16 md:pb-0">

    <!-- Top App Bar (IndiaMART Style) -->
    <header class="bg-primary text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-2 sm:px-4 h-14 flex items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <a href="/" class="bg-white p-1 rounded-sm">
                    <img src="/assets/images/logo.png" alt="<?php echo htmlspecialchars(getSetting('store_name')); ?> Logo" class="h-8 md:h-10 w-auto object-contain">
                </a>
            </div>

            <!-- Search Bar in Header -->
            <div class="flex-grow max-w-2xl hidden md:block">
                <form action="/search.php" method="GET" class="flex w-full bg-white rounded-sm overflow-hidden border border-transparent focus-within:border-accent">
                    <input type="text" name="q" placeholder="Search machinery, products..." class="w-full px-3 py-1.5 text-sm text-gray-800 outline-none" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit" class="bg-secondary text-white px-4 hover:bg-accent transition"><i class="fa-solid fa-search"></i></button>
                </form>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex gap-6 items-center text-xs font-medium">
                <a href="/" class="hover:text-accent transition flex flex-col items-center leading-tight"><i class="fa-solid fa-house mb-0.5 text-sm"></i>Home</a>
                <a href="/favorites.php" class="hover:text-accent transition flex flex-col items-center leading-tight"><i class="fa-solid fa-heart mb-0.5 text-sm"></i>Favorites</a>
                <a href="/contact.php" class="hover:text-accent transition flex flex-col items-center leading-tight"><i class="fa-solid fa-envelope mb-0.5 text-sm"></i>Contact</a>
            </nav>

            <!-- Mobile Actions -->
            <div class="flex md:hidden gap-3 items-center">
                <a href="/favorites.php" class="text-white hover:text-accent transition p-2">
                    <i class="fa-solid fa-heart text-lg"></i>
                </a>
            </div>
        </div>
        
        <!-- Mobile Search Bar (Visible only on mobile) -->
        <div class="md:hidden px-2 pb-2">
            <form action="/search.php" method="GET" class="flex w-full bg-white rounded-sm overflow-hidden shadow-sm">
                <input type="text" name="q" placeholder="Search..." class="w-full px-2 py-1.5 text-sm text-gray-800 outline-none" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                <button type="submit" class="bg-secondary text-white px-3"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="w-full bg-white min-h-screen relative">