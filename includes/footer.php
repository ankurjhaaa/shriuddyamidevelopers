    </main>
    
    <!-- Footer -->
    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <!-- Premium Ultra-Compact Dark Theme Footer -->
    <footer class="bg-slate-900 text-slate-300 border-t-4 border-primary pt-8 pb-6 md:pb-8 mt-auto w-full relative z-10">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Main Grid: Brand Info + Links (3-Column Layout) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <!-- Brand Info (Col 1) -->
                <div class="space-y-2.5">
                    <a href="/" class="inline-flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-primary/20 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition">
                            <i class="fa-solid fa-tractor text-base"></i>
                        </div>
                        <span class="font-black text-xl text-white tracking-tight"><?php echo htmlspecialchars(getSetting('store_name')); ?></span>
                    </a>
                    <p class="text-slate-400 text-xs leading-relaxed max-w-sm">
                        Premium quality agriculture & industrial machinery designed for maximum performance across Bihar.
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <?php 
                        $socialLinksJson = getSetting('social_links');
                        $socialLinks = json_decode($socialLinksJson, true) ?: [];
                        if(!empty($socialLinks['facebook'])): 
                        ?>
                        <a href="<?php echo htmlspecialchars($socialLinks['facebook']); ?>" target="_blank" class="w-8 h-8 rounded-md bg-slate-800 text-slate-300 flex items-center justify-center hover:bg-primary hover:text-white transition-all border border-slate-700/50">
                            <i class="fa-brands fa-facebook-f text-xs"></i>
                        </a>
                        <?php endif; if(!empty($socialLinks['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($socialLinks['instagram']); ?>" target="_blank" class="w-8 h-8 rounded-md bg-slate-800 text-slate-300 flex items-center justify-center hover:bg-primary hover:text-white transition-all border border-slate-700/50">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>
                        <?php endif; if(!empty($socialLinks['twitter'])): ?>
                        <a href="<?php echo htmlspecialchars($socialLinks['twitter']); ?>" target="_blank" class="w-8 h-8 rounded-md bg-slate-800 text-slate-300 flex items-center justify-center hover:bg-primary hover:text-white transition-all border border-slate-700/50">
                            <i class="fa-brands fa-x-twitter text-xs"></i>
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo getWhatsappLink('Hi, I need machinery details'); ?>" target="_blank" class="w-8 h-8 rounded-md bg-slate-800 text-slate-300 flex items-center justify-center hover:bg-[#25D366] hover:text-white transition-all border border-slate-700/50">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links & Top Categories (Cols 2 & 3 Shared Row) -->
                <div class="grid grid-cols-2 gap-4 col-span-1 md:col-span-2">
                    <!-- Quick Navigation -->
                    <div class="min-w-0">
                        <h4 class="font-bold text-white text-sm mb-2.5 tracking-wide border-l-2 border-primary pl-2.5">Quick Links</h4>
                        <ul class="space-y-1.5 text-xs">
                            <li class="truncate"><a href="/" class="text-slate-400 hover:text-primary transition-colors truncate block">Home</a></li>
                            <li class="truncate"><a href="/search.php" class="text-slate-400 hover:text-primary transition-colors truncate block">Shop Machinery</a></li>
                            <li class="truncate"><a href="/categories.php" class="text-slate-400 hover:text-primary transition-colors truncate block">Categories</a></li>
                            <li class="truncate"><a href="/gallery.php" class="text-slate-400 hover:text-primary transition-colors truncate block">Gallery</a></li>
                            <li class="truncate"><a href="/about.php" class="text-slate-400 hover:text-primary transition-colors truncate block">About Us</a></li>
                            <li class="truncate"><a href="/contact.php" class="text-slate-400 hover:text-primary transition-colors truncate block">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Product Categories -->
                    <div class="min-w-0">
                        <h4 class="font-bold text-white text-sm mb-2.5 tracking-wide border-l-2 border-primary pl-2.5">Categories</h4>
                        <ul class="space-y-1.5 text-xs text-slate-400">
                            <li class="truncate"><a href="/search.php" class="hover:text-primary transition-colors truncate block">Agri Machinery</a></li>
                            <li class="truncate"><a href="/search.php" class="hover:text-primary transition-colors truncate block">Industrial Plant</a></li>
                            <li class="truncate"><a href="/search.php" class="hover:text-primary transition-colors truncate block">Paper & Notebook</a></li>
                            <li class="truncate"><a href="/search.php" class="hover:text-primary transition-colors truncate block">Tractors</a></li>
                            <li class="truncate"><a href="/search.php" class="hover:text-primary transition-colors truncate block">Cultivators</a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Full-Width Horizontal Contact Support Bar -->
            <div class="border-t border-slate-800/80 pt-3.5 pb-3.5 mb-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-300 bg-slate-800/40 px-4 py-3 rounded-xl border border-slate-700/40">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-primary"></i>
                    <span>Purnea, Bihar, India</span>
                </div>
                <div class="flex items-center gap-5">
                    <a href="tel:<?php echo htmlspecialchars(getSetting('phone') ?? ''); ?>" class="flex items-center gap-2 hover:text-white transition font-medium">
                        <i class="fa-solid fa-phone text-primary"></i> <?php echo htmlspecialchars(getSetting('phone') ?? '+91 93049 39879'); ?>
                    </a>
                    <a href="<?php echo getWhatsappLink('Hi, I need assistance'); ?>" target="_blank" class="flex items-center gap-2 text-green-400 hover:text-green-300 transition font-medium">
                        <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp Support
                    </a>
                </div>
            </div>

            <!-- Regional Areas Served (Single Scroll Container with 2 Synchronized Rows) -->
            <div class="border-t border-slate-800/80 pt-3.5 pb-3.5 mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-bold text-[11px] uppercase tracking-wider text-slate-400">Areas We Serve Across Bihar</h4>
                    <span class="text-[10px] text-slate-500">Scroll &rarr;</span>
                </div>
                <?php
                $footerLocations = require __DIR__ . '/locations.php';
                $allLocs = array_keys($footerLocations);
                $half = ceil(count($allLocs) / 2);
                $row1 = array_slice($allLocs, 0, $half);
                $row2 = array_slice($allLocs, $half, 30);
                ?>
                <div class="overflow-x-auto hide-scrollbar pb-1">
                    <div class="inline-flex flex-col gap-2 min-w-max">
                        <div class="flex items-center gap-2 text-xs">
                            <?php foreach ($row1 as $locName): $locSlug = strtolower(str_replace(' ', '-', $locName)); ?>
                                <a href="/location/<?php echo urlencode($locSlug); ?>" class="inline-block px-2.5 py-0.5 rounded-full bg-slate-800/80 border border-slate-700/50 text-slate-300 hover:text-white hover:bg-primary hover:border-primary transition-all text-[11px] flex-shrink-0"><?php echo htmlspecialchars($locName); ?></a>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex items-center gap-2 text-xs">
                            <?php foreach ($row2 as $locName): $locSlug = strtolower(str_replace(' ', '-', $locName)); ?>
                                <a href="/location/<?php echo urlencode($locSlug); ?>" class="inline-block px-2.5 py-0.5 rounded-full bg-slate-800/80 border border-slate-700/50 text-slate-300 hover:text-white hover:bg-primary hover:border-primary transition-all text-[11px] flex-shrink-0"><?php echo htmlspecialchars($locName); ?></a>
                            <?php endforeach; ?>
                            <a href="/search.php" class="inline-block px-2.5 py-0.5 rounded-full bg-primary text-white font-bold text-[11px] flex-shrink-0">View All...</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copyright & Legal -->
            <div class="border-t border-slate-800/80 pt-3 flex flex-col md:flex-row justify-between items-center gap-2.5 text-xs text-slate-400">
                <div class="text-center md:text-left">
                    <p>&copy; <?php echo date('Y'); ?> <span class="text-white font-bold"><?php echo htmlspecialchars(getSetting('store_name')); ?></span>. All rights reserved.</p>
                    <p class="mt-0.5 text-slate-500 text-[11px]">An industrial machinery venture by <span class="font-semibold text-slate-300">SRI UDYAMI DEVELOPERS</span></p>
                </div>
                <div class="flex items-center gap-3 text-[11px]">
                    <a href="/privacy.php" class="hover:text-primary transition-colors">Privacy Policy</a>
                    <span>•</span>
                    <a href="/terms.php" class="hover:text-primary transition-colors">Terms of Service</a>
                    <span>•</span>
                    <a href="/admin_login.php" class="hover:text-primary transition-colors font-semibold flex items-center gap-1"><i class="fa-solid fa-lock text-[9px]"></i> Admin</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- Price Lock Bottom Sheet Modal / Get Latest Price -->
    <div id="priceLockSheet" class="fixed inset-0 z-[60] bg-black bg-opacity-60 hidden transition-opacity duration-300 opacity-0 flex items-center justify-center p-4">
        <!-- Sheet Content -->
        <div class="bg-white w-full max-w-[400px] rounded-md md:rounded-lg transform scale-95 md:scale-95 md:opacity-0 transition-all duration-300 shadow-2xl overflow-hidden" id="priceLockContent">
            
            <!-- Header -->
            <div class="bg-[#00a699] text-white px-5 py-4 flex justify-between items-center relative">
                <h3 class="text-lg font-bold">Get Latest Price</h3>
                <button type="button" id="closePriceLock" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-6">
                <p class="text-gray-600 text-sm mb-5">Please enter your details to view the latest price and offers for this product.</p>
                
                <form id="priceLockForm">
                    <input type="hidden" id="pl_product_id" name="product_id" value="">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name</label>
                        <input type="text" id="pl_name" name="name" required class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded focus:ring-1 focus:ring-[#00a699] focus:border-[#00a699] outline-none transition text-sm" placeholder="Enter your full name">
                    </div>
                    
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Mobile Number</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border border-r-0 border-gray-300 rounded-l">+91</span>
                            <input type="tel" id="pl_phone" name="phone" pattern="[0-9]{10}" maxlength="10" title="Please enter exactly 10 digits" required class="flex-1 min-w-0 w-full px-3 py-2.5 bg-white border border-gray-300 rounded-r focus:ring-1 focus:ring-[#00a699] focus:border-[#00a699] outline-none transition text-sm" placeholder="10-digit mobile number">
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#ff7b00] hover:bg-[#e66f00] text-white py-3 rounded transition font-bold text-base flex justify-center items-center shadow-sm">
                        Submit & View Price
                    </button>
                    <p class="text-center text-[10px] text-gray-400 mt-3"><i class="fa-solid fa-lock mr-1"></i> Your information is safe with us.</p>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto-Popup Modal -->
    <div id="waAutoPopup" class="fixed inset-0 z-[70] bg-black bg-opacity-60 hidden transition-opacity duration-300 opacity-0 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-[350px] rounded-lg transform scale-95 opacity-0 transition-all duration-300 shadow-2xl relative overflow-hidden" id="waPopupContent">
            <!-- Header Pattern -->
            <div class="bg-primary h-16 w-full absolute top-0 left-0 flex justify-end p-3">
                <button type="button" id="closeWaPopup" class="text-white hover:text-gray-200 transition-colors z-20 w-8 h-8 flex items-center justify-center rounded-sm bg-black bg-opacity-20 hover:bg-opacity-30 cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <div class="pt-8 pb-6 px-6 relative z-10 text-center flex flex-col items-center mt-2">
                <div class="w-20 h-20 bg-white rounded-sm shadow-lg flex items-center justify-center mb-4 border-4 border-white mt-1">
                    <i class="fa-brands fa-whatsapp text-5xl text-[#25D366]"></i>
                </div>
                
                <h3 class="text-lg font-bold text-gray-800 mb-2">Need Expert Help?</h3>
                <p class="text-gray-500 text-sm mb-6 px-2 leading-relaxed">
                    Chat with our experts on WhatsApp for the best price, machine guidance, and technical support.
                </p>
                
                <a href="<?php echo getWhatsappLink('Hi, I am looking for some machinery. Can you help me?'); ?>" target="_blank" class="w-full bg-[#ff7b00] hover:bg-[#e66f00] text-white py-3 rounded transition-colors font-bold text-base flex justify-center items-center shadow-md gap-2" id="waPopupBtn">
                    <i class="fa-brands fa-whatsapp text-xl"></i> Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-4 md:right-6 z-40 flex flex-col gap-3">
        <a href="tel:<?php echo htmlspecialchars(getSetting('phone')); ?>" data-turbo="false"
            class="bg-red-600 hover:bg-red-700 text-white font-bold w-12 h-12 md:w-auto md:h-auto md:px-5 md:py-2.5 rounded-full md:rounded-xl flex items-center justify-center md:justify-start gap-2 shadow-lg transition-all">
            <i class="fa-solid fa-phone transform -scale-x-100 text-xl md:text-base"></i> <span class="hidden md:block text-sm">Call me</span>
        </a>
        <a href="<?php echo getWhatsappLink(); ?>" target="_blank" data-turbo="false"
            class="bg-green-500 hover:bg-green-600 text-white font-bold w-12 h-12 md:w-auto md:h-auto md:px-5 md:py-2.5 rounded-full md:rounded-xl flex items-center justify-center md:justify-start gap-2 shadow-lg transition-all">
            <i class="fa-brands fa-whatsapp text-2xl md:text-lg"></i> <span class="hidden md:block text-sm">Message me</span>
        </a>
    </div>

</body>
</html>
