

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2>Checkout</h2>
    <form action="<?php echo e(route('order.place')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="product_id" value="1"> <!-- contoh -->
        <div class="mb-3">
            <label for="quantity">Jumlah:</label>
            <input type="number" name="quantity" id="quantity" value="1" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Pesan Sekarang</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\dika\tugas\ecommerce_vs\SonVape\resources\views/checkout/index.blade.php ENDPATH**/ ?>