<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - @yield('title')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between">
        <span class="font-bold text-lg">SonVape Store</span>
        <ul class="flex space-x-4">
            <li><a href="/home" class="hover:text-blue-600">Home</a></li>
            <li><a href="/orders" class="hover:text-blue-600">Pesanan Saya</a></li>
            <li><a href="/profile" class="hover:text-blue-600">Profil</a></li>
        </ul>
    </nav>

    <!-- Konten -->
    <main class="p-1">
        @yield('content')
    </main>
</body>
</html>
