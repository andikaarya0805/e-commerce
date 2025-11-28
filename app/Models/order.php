<?php

// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_city',
        'customer_address',
        'notes',
        'payment_method',
        'shipping_method',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'payment_status'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get order items
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning text-dark',
            'confirmed' => 'bg-info text-white',
            'processing' => 'bg-primary text-white',
            'shipped' => 'bg-secondary text-white',
            'delivered' => 'bg-success text-white',
            'cancelled' => 'bg-danger text-white'
        ];

        return $badges[$this->status] ?? 'bg-secondary text-white';
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-warning text-dark',
            'paid' => 'bg-success text-white',
            'failed' => 'bg-danger text-white',
            'refunded' => 'bg-info text-white'
        ];

        return $badges[$this->payment_status] ?? 'bg-secondary text-white';
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'cancelled' => 'Dibatalkan'
        ];

        return $statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Get formatted payment status
     */
    public function getFormattedPaymentStatusAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Dibayar',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan'
        ];

        return $statuses[$this->payment_status] ?? 'Unknown';
    }
}

// app/Models/OrderItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'subtotal',
        'attributes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'attributes' => 'array'
    ];

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted attributes
     */
    public function getFormattedAttributesAttribute()
    {
        if (empty($this->attributes)) {
            return '';
        }

        $formatted = [];
        foreach ($this->attributes as $name => $value) {
            $formatted[] = "{$name}: {$value}";
        }

        return implode(', ', $formatted);
    }
}
