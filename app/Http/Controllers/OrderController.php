<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Store a new order
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:bank_transfer,cod',
            'shipping_method' => 'required|in:free'
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong!'
                ]);
            }
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = 0;
            $orderItems = [];

            foreach ($cart as $cartKey => $item) {
                $product = Product::find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception("Produk tidak ditemukan.");
                }

                // Check stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}");
                }

                $price = $this->calculatePrice($product);
                $subtotal = $price * $item['quantity'];
                $total += $subtotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'attributes' => $item['attributes'] ?? []
                ];
            }

            // Create order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'customer_city' => $request->city,
                'customer_address' => $request->address,
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                'subtotal' => $total,
                'shipping_cost' => 0,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            // Create order items and update stock
            foreach ($orderItems as $orderItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $orderItem['product']->id,
                    'product_name' => $orderItem['product']->name,
                    'quantity' => $orderItem['quantity'],
                    'price' => $orderItem['price'],
                    'subtotal' => $orderItem['subtotal'],
                    'attributes' => json_encode($orderItem['attributes'])
                ]);

                // Update product stock
                $orderItem['product']->decrement('stock', $orderItem['quantity']);
            }

            // Clear cart
            Session::forget('cart');

            DB::commit();

            // Send order confirmation email (optional)
            // Mail::to($request->email)->send(new OrderConfirmation($order));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil diproses!',
                    'order_number' => $order->order_number,
                    'redirect_url' => route('orders.success', $order->order_number)
                ]);
            }

            return redirect()->route('orders.success', $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show order success page
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('orderItems.product')->firstOrFail();
        
        return view('orders.success', compact('order'));
    }

    /**
     * Show order details
     */
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->with('orderItems.product')->firstOrFail();
        
        return view('orders.show', compact('order'));
    }

    /**
     * List user orders
     */
    public function index()
    {
        // If user is authenticated, show their orders
        // For guest orders, you might want to implement a different approach
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Calculate product price with discount
     */
    private function calculatePrice($product)
    {
        $price = $product->price;
        
        if ($product->discount > 0) {
            $price = $price - ($price * $product->discount / 100);
        }
        
        return $price;
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = date('Ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $orderNumber = $prefix . $date . $random;
        
        // Ensure uniqueness
        while (Order::where('order_number', $orderNumber)->exists()) {
            $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $orderNumber = $prefix . $date . $random;
        }
        
        return $orderNumber;
    }
}