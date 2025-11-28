@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6 text-center">Keranjang Belanja</h1>

    @if (empty($cart))
        <p class="text-center text-gray-500">Keranjang Anda masih kosong.</p>
    @else
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
                    @php $total = 0; @endphp
                    @foreach ($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <tr class="border-b">
                            <td class="p-2 flex items-center space-x-3">
                                <img src="{{ asset('storage/'.$item['image']) }}" class="w-16 h-16 object-contain" alt="{{ $item['name'] }}">
                                <span>{{ $item['name'] }}</span>
                            </td>
                            <td class="p-2">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="p-2">{{ $item['quantity'] }}</td>
                            <td class="p-2">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            <td class="p-2">
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4 text-right">
                <h2 class="text-xl font-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</h2>
                <a href="{{ route('checkout') }}" class="bg-green-600 text-white px-4 py-2 rounded mt-2 inline-block">
                    Lanjut ke Checkout
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
