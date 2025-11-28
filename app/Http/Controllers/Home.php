<?php
use App\Models\Product;

class Home
{
    public function index()
    {
        $products = Product::all();
        return view('home', compact('products'));
    }
}
