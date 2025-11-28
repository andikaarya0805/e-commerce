<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6 text-center">Produk Kami</h1>

    <!-- Grid Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center">
            <img src="<?php echo e(asset('storage/'.$product->image)); ?>" alt="<?php echo e($product->name); ?>" class="h-40 w-auto mb-3 object-contain">
            <h2 class="text-lg font-semibold"><?php echo e($product->name); ?></h2>
            <p class="text-sm text-gray-500 mb-2">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></p>

            <?php if(auth()->guard()->check()): ?>
                <form action="<?php echo e(route('cart.add', $product->id)); ?>" method="POST" class="w-full">
                    <?php echo csrf_field(); ?>
                    
                </form>

                <button 
                    class="bg-blue-600 text-white px-4 py-2 rounded w-full mt-2 add-to-cart"
                    data-product-id="<?php echo e($product->id); ?>">
                    Tambah ke Keranjang
                </button>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded w-full mt-auto text-center">
                    Login untuk Membeli
                </a>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // AJAX Add to Cart
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            addToCartAjax(productId);
        });
    });

    function addToCartAjax(productId) {
    const url = '<?php echo e(url("/cart/add")); ?>/' + productId;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded', // ⬅️ FIX
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({ product_id: productId }) // ⬅️ kirim seperti form
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text); });
        }
        return response.json();
    })
    .then(data => {
        showToast(data.message || 'Produk berhasil ditambahkan!');
        updateCartCount();
    })
    .catch(error => {
        showToast('Gagal menambahkan produk: ' + error.message);
    });
}


    function updateCartCount() {
        fetch('<?php echo e(route("cart.count")); ?>', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            const el = document.getElementById('cart-count');
            if (el) el.textContent = data.count || 0;
            loadCartItems();
        });
    }

    function loadCartItems() {
    fetch('<?php echo e(route("cart.items")); ?>', {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        const cartItems = document.getElementById('cart-items');
        if (!cartItems) return;

        if (data.items && data.items.length > 0) {
            cartItems.innerHTML = '';
            data.items.forEach(item => {
                const div = document.createElement('div');
                div.classList.add('flex','items-center','justify-between','mb-2','p-2','border-b');

                div.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <img src="/storage/${item.image}" class="w-12 h-12 object-contain rounded" alt="${item.name}">
                        <div>
                            <p class="font-semibold">${item.name}</p>
                            <div class="flex items-center space-x-2">
                                <button data-id="${item.id}" class="decrease px-2 py-1 bg-gray-200 rounded">-</button>
                                <span>${item.qty}</span>
                                <button data-id="${item.id}" class="increase px-2 py-1 bg-gray-200 rounded">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right text-sm">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</div>
                `;
                cartItems.appendChild(div);
            });

            // Tambahkan event listener untuk tombol + dan -
            cartItems.querySelectorAll('.increase').forEach(btn => {
                btn.addEventListener('click', () => updateQty(btn.dataset.id, 'increase'));
            });
            cartItems.querySelectorAll('.decrease').forEach(btn => {
                btn.addEventListener('click', () => updateQty(btn.dataset.id, 'decrease'));
            });
        } else {
            cartItems.innerHTML = `<p class="text-gray-500">Keranjang kosong</p>`;
        }
    });
}

function updateQty(id, action) {
    const url = '<?php echo e(url("/cart/update")); ?>/' + id;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ quantity: action === 'increase' ? 9999 : 1 }) // nanti bisa disesuaikan real value
    })
    .then(() => {
        loadCartItems();
        updateCartCount();
    });
}


    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => { toast.classList.add('hidden'); }, 3000);
    }

    // expose untuk global
    window.updateCartCount = updateCartCount;
    updateCartCount();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dika\tugas\ecommerce_vs\SonVape\resources\views/home.blade.php ENDPATH**/ ?>