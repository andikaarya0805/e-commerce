<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SonVape Store</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <!-- ================= HEADER ================= -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center py-3 px-4">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-900">SonVape</a>

            <!-- Search -->
            <form action="{{ route('search') }}" method="GET" class="hidden md:flex flex-1 mx-6">
                <input type="text" name="q" placeholder="Cari produk..." 
                       class="flex-1 border border-gray-300 rounded-l px-3 py-2 focus:outline-none">
                <button type="submit" class="bg-blue-900 text-white px-4 rounded-r">Cari</button>
            </form>

            <!-- Right Menu -->
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="text-blue-900 font-medium hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-700">Daftar</a>
                @endguest

                @auth
                    <span class="text-gray-700">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                @endauth

                <!-- Cart -->
                <div class="relative">
                    <button id="cart-button" class="relative focus:outline-none">
                        🛒
                        <span id="cart-count" 
                              class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 rounded-full">
                              0
                        </span>
                    </button>
                    <!-- Dropdown Cart -->
                    <div id="cart-dropdown" 
                         class="hidden absolute right-0 mt-2 w-72 bg-white border rounded-lg shadow-lg z-50">
                        <div class="p-3 font-bold border-b">Keranjang</div>
                        <div id="cart-items" class="max-h-64 overflow-y-auto p-3 text-sm">
                            <p class="text-gray-500">Keranjang kosong</p>
                        </div>
                        <div class="border-t p-3">
                            <a href="{{ route('checkout') }}" 
                               class="block w-full bg-green-600 text-white text-center py-2 rounded">
                                Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="md:hidden px-4 pb-3">
            <form action="{{ route('search') }}" method="GET" class="flex">
                <input type="text" name="q" placeholder="Cari produk..." 
                       class="flex-1 border border-gray-300 rounded-l px-3 py-2 focus:outline-none">
                <button type="submit" class="bg-blue-900 text-white px-4 rounded-r">Cari</button>
            </form>
        </div>
    </header>
    <!-- =============== END HEADER ================= -->

    <!-- Main Content -->
    <main class="flex-1 container mx-auto py-6 px-4">
        @yield('content')
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-blue-900 text-white mt-10">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 py-10 px-6">
            <!-- About -->
            <div>
                <h3 class="font-bold text-lg mb-3">Tentang SonVape</h3>
                <p class="text-sm text-gray-200">
                    SonVape Store menyediakan berbagai kebutuhan vaping Anda mulai dari liquid, pod, mod, hingga accessories.
                </p>
            </div>

            <!-- Links -->
            <div>
                <h3 class="font-bold text-lg mb-3">Navigasi</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:underline">Beranda</a></li>
                    <li><a href="#" class="hover:underline">Produk</a></li>
                    <li><a href="{{ route('checkout') }}" class="hover:underline">Checkout</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:underline">Kontak Kami</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="font-bold text-lg mb-3">Hubungi Kami</h3>
                <p class="text-sm">📍 Jl. Vape Sejahtera No. 123, Jakarta</p>
                <p class="text-sm">📞 0812-3456-7890</p>
                <p class="text-sm">✉️ support@sonvape.com</p>
                <div class="flex space-x-3 mt-3">
                    <a href="#" class="hover:text-blue-300">🌐</a>
                    <a href="#" class="hover:text-blue-300">📘</a>
                    <a href="#" class="hover:text-blue-300">📸</a>
                </div>
            </div>
        </div>
        <div class="bg-blue-950 text-center py-3 text-sm text-gray-300">
            &copy; {{ date('Y') }} SonVape Store. All rights reserved.
        </div>
    </footer>
    <!-- =============== END FOOTER ================= -->

    <!-- Script untuk Cart Dropdown -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const cartBtn = document.getElementById('cart-button');
        const cartDropdown = document.getElementById('cart-dropdown');

        if (cartBtn) {
            cartBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                cartDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!cartDropdown.contains(e.target) && !cartBtn.contains(e.target)) {
                    cartDropdown.classList.add('hidden');
                }
            });
        }
    });
    </script>
</body>
</html>
