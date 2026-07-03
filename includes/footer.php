    </main>
    
    <!-- Footer -->
    <footer class="hidden md:block bg-white border-t border-gray-100 mt-12 pb-20 md:pb-8 pt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-4 group">
                        <i class="fa-solid fa-tractor text-accent text-xl group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold text-lg text-primary tracking-tight"><?php echo htmlspecialchars(getSetting('store_name')); ?></span>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-sm mb-4">
                        Premium quality agriculture and industrial machines designed for durability and performance. Experience modern farming.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition">
                            <i class="fa-brands fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition">
                            <i class="fa-brands fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition">
                            <i class="fa-brands fa-twitter text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/about.php" class="text-gray-500 hover:text-primary transition">About Us</a></li>
                        <li><a href="/search.php" class="text-gray-500 hover:text-primary transition">Shop</a></li>
                        <li><a href="/faq.php" class="text-gray-500 hover:text-primary transition">FAQs</a></li>
                        <li><a href="/contact.php" class="text-gray-500 hover:text-primary transition">Contact Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-gray-900 mb-4">Contact</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-phone text-accent mt-0.5"></i>
                            <span class="text-gray-500"><?php echo htmlspecialchars(getSetting('whatsapp')); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-8 pb-2 mb-4">
                <h4 class="font-semibold text-gray-900 mb-4">Areas We Serve</h4>
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <?php 
                    $footerLocations = require __DIR__ . '/locations.php';
                    $totalLocs = count($footerLocations);
                    foreach ($footerLocations as $index => $loc): 
                        $locSlug = strtolower(str_replace(' ', '-', $loc));
                    ?>
                        <a href="/location.php?place=<?php echo urlencode($locSlug); ?>" class="text-gray-500 hover:text-primary hover:underline transition font-medium"><?php echo htmlspecialchars($loc); ?></a>
                        <?php if($index < $totalLocs - 1): ?>
                            <span class="text-gray-300">|</span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-400">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(getSetting('store_name')); ?>. All rights reserved.</p>
                <div class="flex gap-4 text-xs text-gray-400">
                    <a href="/privacy.php" class="hover:text-primary transition">Privacy Policy</a>
                    <a href="/terms.php" class="hover:text-primary transition">Terms of Service</a>
                    <a href="/admin_login.php" class="hover:text-primary transition font-semibold"><i class="fa-solid fa-lock text-[10px] mr-1"></i>Admin</a>
                </div>
            </div>
        </div>
    </footer>

    <?php include __DIR__ . '/bottom-nav.php'; ?>

    <!-- Price Lock Bottom Sheet Modal -->
    <div id="priceLockSheet" class="fixed inset-0 z-[60] bg-black bg-opacity-50 hidden transition-opacity duration-300 opacity-0 flex items-end md:items-center justify-center md:p-4">
        <!-- Sheet Content -->
        <div class="bg-white w-full max-w-md rounded-t-2xl md:rounded-2xl transform translate-y-full md:translate-y-0 md:scale-95 md:opacity-0 transition-all duration-300 shadow-xl" id="priceLockContent">
            <div class="p-6 relative">
                <!-- Drag Handle -->
                <div class="md:hidden w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6"></div>
                
                <button type="button" id="closePriceLock" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
                
                <h3 class="text-xl font-bold text-gray-900 mb-2">Unlock Price</h3>
                <p class="text-gray-500 text-sm mb-6">Please enter your details to view the price for this product.</p>
                
                <form id="priceLockForm">
                    <input type="hidden" id="pl_product_id" name="product_id" value="">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" id="pl_name" name="name" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="Your Name">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp Number</label>
                        <input type="tel" id="pl_phone" name="phone" pattern="[0-9]{10}" maxlength="10" title="Please enter exactly 10 digits" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="10-digit Mobile Number">
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-lg hover:bg-blue-800 transition font-semibold text-lg flex justify-center items-center gap-2 shadow-md">
                        <span>Unlock Now</span>
                        <i class="fa-solid fa-unlock-keyhole"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
