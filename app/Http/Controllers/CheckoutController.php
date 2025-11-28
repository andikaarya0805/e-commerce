<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Simpan order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending'
        ]);

        // Simpan item order
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'attributes' => $item['attributes'] ?? null,
            ]);
        }

        // Hapus cart
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    public function success($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('checkout.success', compact('order'));
    }
}
