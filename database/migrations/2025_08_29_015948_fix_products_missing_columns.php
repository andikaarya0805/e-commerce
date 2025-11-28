<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'quantity')) {
                $table->integer('quantity')->default(1)->after('stock');
            }
            if (!Schema::hasColumn('products', 'category_id')) {
                // kalau tabel categories sudah ada dan kamu mau FK:
                if (Schema::hasTable('categories')) {
                    $table->foreignId('category_id')->nullable()->after('quantity')
                        ->constrained('categories')->nullOnDelete();
                } else {
                    // fallback tanpa FK dulu
                    $table->unsignedBigInteger('category_id')->nullable()->after('quantity');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'category_id')) {
                // drop FK jika ada
                try { $table->dropConstrainedForeignId('category_id'); } catch (\Throwable $e) { $table->dropColumn('category_id'); }
            }
            if (Schema::hasColumn('products', 'quantity')) {
                $table->dropColumn('quantity');
            }
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
            if (Schema::hasColumn('products', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
