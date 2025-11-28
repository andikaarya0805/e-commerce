

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6 text-center">Keranjang Belanja</h1>

    <?php if(empty($cart)): ?>
        <p class="text-center text-gray-500">Keranjang Anda masih kosong.</p>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-md p-6">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="p-2">Produk</th>
                        <th class="p-2">Harga</th>
                        <th class="p-2">Jumlah</th>
                        <th class="p-2">Subtotal</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                        <tr class="border-b">
                            <td class="p-2 flex items-center space-x-3">
                                <img src="<?php echo e(asset('storage/'.$item['image'])); ?>" class="w-16 h-16 object-contain" alt="<?php echo e($item['name']); ?>">
                                <span><?php echo e($item['name']); ?></span>
                            </td>
                            <td class="p-2">Rp <?php echo e(number_format($item['price'], 0, ',', '.')); ?></td>
                            <td class="p-2"><?php echo e($item['quantity']); ?></td>
                            <td class="p-2">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></td>
                            <td class="p-2">
                                <form action="<?php echo e(route('cart.remove', $id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <div class="mt-4 text-right">
                <h2 class="text-xl font-bold">Total: Rp <?php echo e(number_format($total, 0, ',', '.')); ?></h2>
                <a href="<?php echo e(route('checkout')); ?>" class="bg-green-600 text-white px-4 py-2 rounded mt-2 inline-block">
                    Lanjut ke Checkout
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dika\tugas\ecommerce_vs\SonVape\resources\views/cart/index.blade.php ENDPATH**/ ?>