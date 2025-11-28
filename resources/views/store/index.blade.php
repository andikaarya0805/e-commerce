@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Daftar Produk</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="object-cover h-full w-full">
                    @else
                        <img src="{{ asset('images/default.png') }}" 
                             alt="No Image" 
                             class="object-cover h-full w-full">
                    @endif
                </div>
                <div class="p-4">
                    <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    <p class="text-blue-600 font-bold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500">Belum ada produk.</p>
        @endforelse
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Produk</h2>

    @foreach ($categories as $category)
        <h3 class="mt-4">{{ $category->name }}</h3>
        <div class="row">
            @forelse ($category->products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if ($product->image && file_exists(public_path('storage/' . $product->image)))
    <img 
        src="{{ asset('storage/' . $product->image) }}" 
        class="card-img-top" 
        alt="{{ $product->name }}">
@else
    <img 
        src="{{ asset('images/no-image.png') }}" 
        class="card-img-top" 
        alt="No Image">
@endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">
                                {{ $product->description ?? 'Tidak ada deskripsi.' }}
                            </p>
                            <p class="card-text fw-bold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted">Belum ada produk di kategori ini.</p>
                </div>
            @endforelse
        </div>
    @endforeach
</div>
@endsection
