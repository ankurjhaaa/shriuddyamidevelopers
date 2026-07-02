<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>
        
        <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : ''; ?><?php echo htmlspecialchars(getSetting('store_name')); ?>
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
                        primary: '#1E3A8A', // Deep Blue
                        secondary: '#38BDF8', // Sky Blue
                        accent: '#F59E0B', // Amber
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
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
</head>

<body class="bg-white text-gray-800 antialiased font-sans pb-16 md:pb-0">

    <!-- Top App Bar -->
    <header class="bg-primary text-white sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <i class="fa-solid fa-tractor text-accent text-2xl group-hover:scale-110 transition-transform"></i>
                <span
                    class="font-bold text-xl tracking-tight truncate"><?php echo htmlspecialchars(getSetting('store_name')); ?></span>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex gap-8 items-center font-medium">
                <a href="/" class="hover:text-accent transition">Home</a>
                <a href="/search.php" class="hover:text-accent transition">Shop</a>
                <a href="/about.php" class="hover:text-accent transition">About</a>
                <a href="/contact.php" class="hover:text-accent transition">Contact</a>
                <a href="/search.php" class="text-white hover:text-accent transition ml-2">
                    <i class="fa-solid fa-search text-xl"></i>
                </a>
            </nav>

            <!-- Mobile Actions -->
            <div class="flex md:hidden gap-4">
                <a href="/search.php" class="text-white hover:text-accent transition">
                    <i class="fa-solid fa-search text-xl"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto bg-white min-h-screen relative sm:rounded-b-lg">