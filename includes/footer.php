    </main>
    
    <!-- Footer -->
    <footer class="hidden md:block bg-white border-t border-gray-200 pb-20 md:pb-8 pt-12 mt-auto">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="flex items-center gap-2 mb-4 group">
                        <i class="fa-solid fa-tractor text-accent text-xl  transition-transform"></i>
                        <span class="font-bold text-lg text-primary tracking-tight"><?php echo htmlspecialchars(getSetting('store_name')); ?></span>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-sm mb-4">
                        Premium quality agriculture and industrial machines designed for durability and performance. Experience modern farming.
                    </p>
                    <div class="flex gap-3">
                        <?php 
                        $socialLinksJson = getSetting('social_links');
                        $socialLinks = json_decode($socialLinksJson, true) ?: [];
                        if(!empty($socialLinks['facebook'])): 
                        ?>
                        <a href="<?php echo htmlspecialchars($socialLinks['facebook']); ?>" target="_blank" class="w-8 h-8 rounded-sm bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition">
                            <i class="fa-brands fa-facebook-f text-sm"></i>
                        </a>
                        <?php endif; if(!empty($socialLinks['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($socialLinks['instagram']); ?>" target="_blank" class="w-8 h-8 rounded-sm bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition">
                            <i class="fa-brands fa-instagram text-sm"></i>
                        </a>
                        <?php endif; if(!empty($socialLinks['twitter'])): ?>
                        <a href="<?php echo htmlspecialchars($socialLinks['twitter']); ?>" target="_blank" class="w-8 h-8 rounded-sm bg-blue-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition">
                            <i class="fa-brands fa-twitter text-sm"></i>
                        </a>
                        <?php endif; ?>
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
                    $topFooterLocs = array_slice(array_keys($footerLocations), 0, 40);
                    $totalLocs = count($topFooterLocs);
                    foreach ($topFooterLocs as $index => $locName): 
                        $locSlug = strtolower(str_replace(' ', '-', $locName));
                    ?>
                        <a href="/location/<?php echo urlencode($locSlug); ?>" class="text-gray-500 hover:text-primary hover:underline transition font-medium"><?php echo htmlspecialchars($locName); ?></a>
                        <?php if($index < $totalLocs - 1): ?>
                            <span class="text-gray-300">|</span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <a href="/search.php" class="text-gray-500 hover:text-primary hover:underline transition font-medium">View All Regions...</a>
                </div>
            </div>
            
            <div class="border-t border-gray-100 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <p class="text-xs text-gray-400">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(getSetting('store_name')); ?>. All rights reserved.</p>
                    <p class="text-xs text-gray-500 mt-1">An industrial machinery venture by <span class="font-bold text-gray-700 tracking-wide">SRI UDYAMI DEVELOPERS</span></p>
                </div>
                <div class="flex gap-4 text-xs text-gray-400">
                    <a href="/privacy.php" class="hover:text-primary transition">Privacy Policy</a>
                    <a href="/terms.php" class="hover:text-primary transition">Terms of Service</a>
                    <a href="/admin_login.php" class="hover:text-primary transition font-semibold"><i class="fa-solid fa-lock text-[10px] mr-1"></i>Admin</a>
                </div>
            </div>
        </div>
    </footer>

    <?php include __DIR__ . '/bottom-nav.php'; ?>

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
    <div class="fixed bottom-24 md:bottom-6 right-4 md:right-6 z-50 flex flex-col gap-3">
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
