<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Pod System', 'Liquid', 'Coil', 'Accessories'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat]);
        }
    }
}
