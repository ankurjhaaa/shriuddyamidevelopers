document.addEventListener('DOMContentLoaded', () => {
    const sheet = document.getElementById('priceLockSheet');
    const content = document.getElementById('priceLockContent');
    const closeBtn = document.getElementById('closePriceLock');
    const form = document.getElementById('priceLockForm');
    const productIdInput = document.getElementById('pl_product_id');
    let currentPriceContainer = null;

    // Check if user is already unlocked
    const isUnlocked = localStorage.getItem('price_unlocked') === 'true';

    // Function to process all price containers on the page
    const processPriceContainers = () => {
        document.querySelectorAll('.price-container').forEach(container => {
            const visibility = container.dataset.visibility;
            if (visibility === 'locked') {
                if (isUnlocked) {
                    // Show real price immediately
                    const rawPrice = container.dataset.price;
                    container.innerHTML = `<span class="font-bold text-gray-900">₹ ${parseFloat(rawPrice).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>`;
                } else {
                    // Setup unlock button
                    const btn = container.querySelector('.btn-unlock-price');
                    if (btn) {
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            openSheet(container.dataset.productId, container);
                        });
                    }
                }
            }
        });
    };

    // Run on load
    processPriceContainers();

    // Export function to window so it can be called after ajax search load
    window.processPriceContainers = processPriceContainers;

    function openSheet(productId, container) {
        productIdInput.value = productId;
        currentPriceContainer = container;
        sheet.classList.remove('hidden');
        // trigger reflow
        void sheet.offsetWidth;
        sheet.classList.remove('opacity-0');
        content.classList.remove('translate-y-full');
    }

    function closeSheet() {
        sheet.classList.add('opacity-0');
        content.classList.add('translate-y-full');
        setTimeout(() => {
            sheet.classList.add('hidden');
        }, 300);
    }

    if(closeBtn) {
        closeBtn.addEventListener('click', closeSheet);
    }

    if(sheet) {
        sheet.addEventListener('click', (e) => {
            if (e.target === sheet) {
                closeSheet();
            }
        });
    }

    if(form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Unlocking...';
            submitBtn.disabled = true;

            const payload = {
                product_id: productIdInput.value,
                name: document.getElementById('pl_name').value,
                phone: document.getElementById('pl_phone').value
            };

            try {
                const response = await fetch('/ajax/unlock_price.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await response.json();

                if (result.success) {
                    // Store in local storage
                    localStorage.setItem('price_unlocked', 'true');
                    localStorage.setItem('lead_name', payload.name);
                    localStorage.setItem('lead_phone', payload.phone);
                    
                    // Update current container
                    if (currentPriceContainer) {
                        currentPriceContainer.innerHTML = `<span class="font-bold text-gray-900">${result.formatted_price}</span>`;
                    }
                    
                    // Update all others silently
                    window.location.reload(); // Quickest way to refresh all prices
                } else {
                    alert('Error: ' + result.error);
                }
            } catch (err) {
                console.error(err);
                alert('Connection error. Please try again.');
            } finally {
                submitBtn.innerHTML = originalBtnHtml;
                submitBtn.disabled = false;
            }
        });
    }
});
