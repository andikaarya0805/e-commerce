@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Checkout</h2>
    <form action="{{ route('order.place') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="1"> <!-- contoh -->
        <div class="mb-3">
            <label for="quantity">Jumlah:</label>
            <input type="number" name="quantity" id="quantity" value="1" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Pesan Sekarang</button>
    </form>
</div>
@endsection
