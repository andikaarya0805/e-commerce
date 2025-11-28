@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6 text-center">Produk Kami</h1>

    <!-- Grid Produk -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
        <div class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center">

            <!-- 🔹 Gambar langsung bisa diklik -->
            <a href="{{ route('products.show', $product->id) }}">
                <img src="{{ asset('storage/'.$product->image) }}" 
                     alt="{{ $product->name }}" 
                     class="h-40 w-auto mb-3 object-contain hover:scale-105 transition-transform duration-200">
            </a>

            <!-- 🔹 Nama produk juga bisa diklik -->
            <a href="{{ route('products.show', $product->id) }}" 
               class="text-lg font-semibold hover:text-blue-600 text-center block">
                {{ $product->name }}
            </a>

            <p class="text-sm text-gray-500 mb-2">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>

            @auth
                <button 
                    class="bg-blue-600 text-white px-4 py-2 rounded w-full mt-2 add-to-cart"
                    data-product-id="{{ $product->id }}">
                    Tambah ke Keranjang
                </button>
            @else
                <a href="{{ route('login') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded w-full mt-2 text-center">
                    Login untuk Membeli
                </a>            @endauth
        </div>
        @endforeach
    </div>
</div>

<!-- 🔹 Dropdown Cart -->
<div id="cart-dropdown" class="absolute right-5 top-16 w-80 bg-white shadow-lg rounded-lg p-4 hidden">
    <h2 class="text-lg font-semibold mb-2">Keranjang</h2>
    <div id="cart-items" class="max-h-64 overflow-y-auto"></div>
    <div id="cart-footer" class="mt-3 border-t pt-2 hidden">
        <div class="flex justify-between font-semibold mb-2">
            <span>Total:</span>
            <span id="cart-total">Rp 0</span>
        </div>
        <a href="{{ route('cart.index') }}" class="block bg-green-600 text-white text-center py-2 rounded">
            Checkout
        </a>
    </div>
</div>

<!-- 🔹 Toast -->
<div id="toast" class="fixed bottom-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Tambah produk ke cart
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const productId = btn.dataset.productId;
            addToCartAjax(productId);
        });
    });

    // Toggle dropdown cart (bisa dipanggil dari icon keranjang di navbar)
    document.getElementById('cart-icon')?.addEventListener('click', () => {
        const dropdown = document.getElementById('cart-dropdown');
        dropdown.classList.toggle('hidden');
        loadCartItems();
    });

    function addToCartAjax(productId) {
        const url = '{{ url("/cart/add") }}/' + productId;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            showToast(data.message || 'Produk berhasil ditambahkan!');
            updateCartCount();
        })
        .catch(err => {
            showToast('Gagal menambahkan: ' + err.message);
        });
    }

    function updateCartCount() {
        fetch('{{ route("cart.count") }}', { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            const el = document.getElementById('cart-count');
            if (el) el.textContent = data.count || 0;
            loadCartItems();
        });
    }

    function loadCartItems() {
        fetch('{{ route("cart.items") }}', { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            const cartItems = document.getElementById('cart-items');
            const cartFooter = document.getElementById('cart-footer');
            const cartTotal = document.getElementById('cart-total');

            if (!cartItems) return;
            cartItems.innerHTML = '';

            if (data.items && data.items.length > 0) {
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
                                    <button data-id="${item.id}" class="delete px-2 py-1 bg-red-500 text-white rounded">x</button>
                                </div>
                            </div>
                        </div>
                        <div class="text-right text-sm">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</div>
                    `;
                    cartItems.appendChild(div);
                });

                // Event listener tombol
                cartItems.querySelectorAll('.increase').forEach(btn => {
                    btn.addEventListener('click', () => updateQty(btn.dataset.id, 'increase'));
                });
                cartItems.querySelectorAll('.decrease').forEach(btn => {
                    btn.addEventListener('click', () => updateQty(btn.dataset.id, 'decrease'));
                });
                cartItems.querySelectorAll('.delete').forEach(btn => {
                    btn.addEventListener('click', () => deleteItem(btn.dataset.id));
                });

                cartFooter.classList.remove('hidden');
                cartTotal.textContent = "Rp " + new Intl.NumberFormat('id-ID').format(data.total);
            } else {
                cartItems.innerHTML = `<p class="text-gray-500">Keranjang kosong</p>`;
                cartFooter.classList.add('hidden');
            }
        });
    }

    function updateQty(id, action) {
        const url = '{{ url("/cart/update") }}/' + id;
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ action: action })
        })
        .then(res => res.json())
        .then(() => {
            loadCartItems();
            updateCartCount();
        });
    }

    function deleteItem(id) {
        const url = '{{ url("/cart/remove") }}/' + id;
        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(() => {
            loadCartItems();
            updateCartCount();
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // init
    window.updateCartCount = updateCartCount;
    updateCartCount();
});
</script>
@endsection
