

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Gambar Produk -->
        <div class="flex justify-center">
            <img src="<?php echo e(asset('storage/'.$product->image)); ?>" 
                 alt="<?php echo e($product->name); ?>" 
                 class="w-80 h-80 object-contain rounded-lg shadow">
        </div>

        <!-- Detail Produk -->
        <div>
            <h1 class="text-3xl font-bold mb-3"><?php echo e($product->name); ?></h1>
            
            <!-- Harga -->
            <div class="mb-4">
                <p class="text-2xl font-semibold text-blue-600">
                    Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                </p>
            </div>

            <!-- Opsi Tambahan (contoh: ukuran/level) -->
            <div class="mb-4">
                <label for="option" class="block font-medium mb-1">Pilih Varian</label>
                <select id="option" name="option" class="border rounded px-3 py-2 w-48">
                    <option value="default">Default</option>
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                </select>
            </div>

            <!-- Qty -->
            <div class="flex items-center mb-6">
                <label class="mr-3">Jumlah:</label>
                <input type="number" min="1" value="1" 
                       id="quantity" 
                       class="border rounded px-3 py-2 w-20">
            </div>

            <!-- Tombol Add to Cart -->
            <?php if(auth()->guard()->check()): ?>
            <button 
                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition add-to-cart"
                data-product-id="<?php echo e($product->id); ?>">
                Tambah ke Keranjang
            </button>
            <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                Login untuk Membeli
            </a>
            <?php endif; ?>

            <!-- Deskripsi -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold mb-2">Deskripsi</h2>
                <p class="text-gray-600">
                    <?php echo e($product->description ?? 'Belum ada deskripsi untuk produk ini.'); ?>

                </p>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.querySelector('.add-to-cart');
    if (btn) {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            const quantity = document.getElementById('quantity').value;
            const option = document.getElementById('option').value;

            fetch(`<?php echo e(url('/cart/add')); ?>/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({ 
                    product_id: productId, 
                    quantity: quantity, 
                    option: option 
                })
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.message || 'Produk berhasil ditambahkan!');
                if (window.updateCartCount) window.updateCartCount();
            })
            .catch(() => showToast('Gagal menambahkan produk.'));
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dika\tugas\ecommerce_vs\SonVape\resources\views/products/show.blade.php ENDPATH**/ ?>