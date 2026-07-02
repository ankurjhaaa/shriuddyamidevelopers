            </div>
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 flex items-center justify-around z-40 pb-safe">
            <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
            <a href="/admin/index.php" class="flex flex-col items-center py-3 px-2 flex-1 <?php echo $currentPage == 'index.php' ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900'; ?>">
                <i class="fa-solid fa-chart-pie text-lg mb-1"></i>
                <span class="text-[10px] font-medium">Dashboard</span>
            </a>
            <a href="/admin/categories.php" class="flex flex-col items-center py-3 px-2 flex-1 <?php echo $currentPage == 'categories.php' ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900'; ?>">
                <i class="fa-solid fa-layer-group text-lg mb-1"></i>
                <span class="text-[10px] font-medium">Categories</span>
            </a>
            <a href="/admin/products.php" class="flex flex-col items-center py-3 px-2 flex-1 <?php echo in_array($currentPage, ['products.php', 'product_edit.php']) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900'; ?>">
                <i class="fa-solid fa-box-open text-lg mb-1"></i>
                <span class="text-[10px] font-medium">Products</span>
            </a>
            <a href="/admin/leads.php" class="flex flex-col items-center py-3 px-2 flex-1 <?php echo $currentPage == 'leads.php' ? 'text-blue-600' : 'text-gray-500 hover:text-gray-900'; ?>">
                <i class="fa-solid fa-address-book text-lg mb-1"></i>
                <span class="text-[10px] font-medium">Leads</span>
            </a>
            <button id="openSidebar" class="flex flex-col items-center py-3 px-2 flex-1 text-gray-500 hover:text-gray-900 focus:outline-none">
                <i class="fa-solid fa-bars text-lg mb-1"></i>
                <span class="text-[10px] font-medium">More</span>
            </button>
        </nav>
    </div>
    
    <script>
        // Use IIFE or turbo:load for scripts so they run properly on Turbo navigations
        document.addEventListener('turbo:load', () => {
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('openSidebar');
            const closeBtn = document.getElementById('closeSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            if(openBtn && closeBtn && sidebar && backdrop) {
                // Remove old listeners to prevent duplicates
                const newOpenBtn = openBtn.cloneNode(true);
                openBtn.parentNode.replaceChild(newOpenBtn, openBtn);
                
                const newCloseBtn = closeBtn.cloneNode(true);
                closeBtn.parentNode.replaceChild(newCloseBtn, closeBtn);
                
                const newBackdrop = backdrop.cloneNode(true);
                backdrop.parentNode.replaceChild(newBackdrop, backdrop);

                function toggleSidebar() {
                    sidebar.classList.toggle('-translate-x-full');
                    newBackdrop.classList.toggle('hidden');
                    setTimeout(() => {
                        newBackdrop.classList.toggle('opacity-0');
                    }, 10);
                }

                newOpenBtn.addEventListener('click', toggleSidebar);
                newCloseBtn.addEventListener('click', toggleSidebar);
                newBackdrop.addEventListener('click', toggleSidebar);
            }
        });

        // Close sidebar before caching so it doesn't stay open when navigating back
        document.addEventListener('turbo:before-cache', () => {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if(sidebar && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
            }
            if(backdrop && !backdrop.classList.contains('hidden')) {
                backdrop.classList.add('hidden');
                backdrop.classList.add('opacity-0');
            }
        });
    </script>
</body>
</html>
