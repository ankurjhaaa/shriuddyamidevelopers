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
        <div class="hidden md:flex max-w-[1440px] mx-auto px-4 h-[60px] items-center justify-between gap-6">
            <!-- Logo -->
            <a href="/" class="flex-shrink-0">
                <img src="/assets/images/logo.png" alt="<?php echo htmlspecialchars(getSetting('store_name')); ?> Logo"
                    class="h-10 w-auto object-contain">
            </a>

            <!-- Massive Central Search Bar -->
            <div
                class="flex-grow max-w-4xl flex items-center bg-white rounded-md h-10 border border-gray-300 focus-within:border-primary transition-colors overflow-hidden">
                <form action="/search.php" method="GET" class="flex w-full h-full">
                    <select name="category"
                        class="hidden lg:block w-36 truncate bg-white hover:bg-gray-50 text-gray-700 text-sm px-3 h-full border-r border-gray-300 outline-none cursor-pointer">
                        <option value="">All Categories</option>
                        <?php
                        if (isset($pdo)) {
                            $navCats = $pdo->query("SELECT slug, name FROM categories ORDER BY name ASC")->fetchAll();
                            foreach ($navCats as $nc) {
                                $selected = (isset($_GET['category']) && $_GET['category'] === $nc['slug']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($nc['slug']) . '" ' . $selected . '>' . htmlspecialchars($nc['name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <input type="text" name="q" placeholder="Enter product / service to search"
                        class="flex-grow px-4 text-sm text-gray-800 outline-none"
                        value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit"
                        class="bg-secondary text-white px-6 h-full hover:bg-accent transition font-semibold text-sm flex items-center gap-2">
                        <i class="fa-solid fa-search"></i> Search
                    </button>
                </form>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-6 flex-shrink-0">
                <a href="<?php echo getWhatsappLink('I have a requirement'); ?>" target="_blank"
                    class="hidden lg:flex items-center gap-2 text-xs font-bold text-white bg-primary px-3 py-1.5 rounded-md hover:bg-secondary transition border border-primary">
                    <i class="fa-solid fa-paper-plane text-accent"></i> Post Requirement
                </a>
                <nav class="flex gap-5 items-center text-[11px] font-semibold tracking-wide text-gray-600">
                    <a href="/favorites.php" class="hover:text-primary transition flex flex-col items-center">
                        <i class="fa-solid fa-heart text-xl mb-1"></i> Favorites
                    </a>
                    <a href="/contact.php" class="hover:text-primary transition flex flex-col items-center">
                        <i class="fa-solid fa-comment-dots text-xl mb-1"></i> Messages
                    </a>
                    <a href="#" class="hover:text-primary transition flex flex-col items-center">
                        <i class="fa-solid fa-circle-user text-xl mb-1"></i> Sign In
                    </a>
                </nav>
            </div>
        </div>

        <!-- Mobile Header -->
        <div class="md:hidden">
            <!-- Top Row: Hamburger, Logo, User -->
            <div class="flex items-center justify-between px-3 h-14 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <a href="/" class="flex items-center">
                        <img src="/assets/images/logo.png" alt="Logo" class="h-6 w-auto object-contain">
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?php echo getWhatsappLink('I have a requirement'); ?>" target="_blank"
                        class="text-gray-600 hover:text-primary"><i class="fa-solid fa-plus-circle text-lg"></i></a>
                    <a href="/favorites.php" class="text-gray-600 hover:text-primary"><i class="fa-solid fa-heart text-lg"></i></a>
                    <a href="#" class="text-gray-600 hover:text-primary"><i class="fa-solid fa-user text-lg"></i></a>
                </div>
            </div>

            <!-- Bottom Row: Search Bar -->
            <div class="p-3 bg-white border-b border-gray-200">
                <form action="/search.php" method="GET"
                    class="flex w-full bg-white border border-gray-300 rounded-md h-10 overflow-hidden focus-within:border-primary">
                    <input type="text" name="q" placeholder="Search products & suppliers"
                        class="w-full px-3 text-sm text-gray-800 outline-none bg-transparent"
                        value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                    <button type="submit" class="bg-primary text-white px-4 flex items-center justify-center hover:bg-secondary"><i
                            class="fa-solid fa-search"></i></button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="w-full bg-white min-h-screen relative">