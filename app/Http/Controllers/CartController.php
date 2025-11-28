<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'quantity' => 1,
                'attributes' => $request->input('attributes', [])
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang!',
            'cart_count' => $this->getTotalCartItems(),
        ]);
    }

    public function remove($id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($cart) {
            $cart->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk dihapus dari keranjang.',
            'cart_count' => $this->getTotalCartItems()
        ]);
    }

    public function count()
    {
        return response()->json([
            'count' => $this->getTotalCartItems()
        ]);
    }

    private function getTotalCartItems()
    {
        return Cart::where('user_id', Auth::id())->sum('quantity');
    }

    public function items()
    {
        $cart = Cart::with('product')->where('user_id', Auth::id())->get();

        $items = $cart->map(function ($item) {
            return [
                'id'       => $item->id,
                'name'     => $item->product->name,
                'price'    => $item->product->price,
                'qty'      => $item->quantity,
                'image'    => $item->product->image,
                'subtotal' => $item->product->price * $item->quantity,
            ];
        });

        return response()->json([
            'items' => $items,
            'count' => $this->getTotalCartItems(),
            'total' => $items->sum('subtotal'),
        ]);
    }

    public function updateQuantity(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($cart) {
            $action = $request->input('action');
            if ($action === 'increase') {
                $cart->quantity++;
            } elseif ($action === 'decrease' && $cart->quantity > 1) {
                $cart->quantity--;
            }
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk diperbarui.',
                'count'   => $this->getTotalCartItems(),
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
    }

    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['message' => 'Cart cleared']);
    }
}
