    </main>
    
    <?php include __DIR__ . '/bottom-nav.php'; ?>

    <!-- Price Lock Bottom Sheet Modal -->
    <div id="priceLockSheet" class="fixed inset-0 z-[60] bg-black bg-opacity-50 hidden transition-opacity duration-300 opacity-0 flex items-end justify-center">
        <!-- Sheet Content -->
        <div class="bg-white w-full max-w-md rounded-t-2xl transform translate-y-full transition-transform duration-300 shadow-xl" id="priceLockContent">
            <div class="p-6 relative">
                <!-- Drag Handle -->
                <div class="w-12 h-1.5 bg-gray-300 rounded-full mx-auto mb-6"></div>
                
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
                        <input type="tel" id="pl_phone" name="phone" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary outline-none transition" placeholder="10-digit Mobile Number">
                    </div>
                    
                    <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-lg hover:bg-blue-800 transition font-semibold text-lg flex justify-center items-center gap-2 shadow-md">
                        <span>Unlock Now</span>
                        <i class="fa-solid fa-unlock-keyhole"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/bottom-sheet.js"></script>
</body>
</html>
