<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil id kategori berdasarkan nama
        $podSystem   = Category::where('name', 'Pod System')->first()->id;
        $liquid      = Category::where('name', 'Liquid')->first()->id;
        $coil        = Category::where('name', 'Coil')->first()->id;
        $accessories = Category::where('name', 'Accessories')->first()->id;

        $products = [
            [
                'name' => 'Voopoo Drag X',
                'price' => 450000,
                'category_id' => $podSystem,
                'image' => null,
            ],
            [
                'name' => 'Ruthless Grape 60ml',
                'price' => 120000,
                'category_id' => $liquid,
                'image' => null,
            ],
            [
                'name' => 'Caliburn Coil 1.0 Ohm',
                'price' => 60000,
                'category_id' => $coil,
                'image' => null,
            ],
            [
                'name' => 'Vape Pouch Premium',
                'price' => 75000,
                'category_id' => $accessories,
                'image' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
