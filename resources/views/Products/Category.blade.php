@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Produk</h2>

    @foreach($categories as $category)
        <h4 class="mt-4">{{ $category->name }}</h4>
        <div class="row">
            @forelse($category->products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        @if($product->image)
                            <img 
    src="{{ $product->image 
            ? asset('storage/' . ltrim($product->image, '/')) 
            : asset('images/no-image.png') }}" 
    class="card-img-top" 
    alt="{{ $product->name }}">

                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-primary fw-bold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Belum ada produk di kategori ini.</p>
            @endforelse
        </div>
    @endforeach
</div>
@endsection
