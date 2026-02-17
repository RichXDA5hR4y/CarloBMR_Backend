<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'subtotal'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'integer',
        'subtotal' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the order that owns the order item.
     * Relasi: OrderItem → Order (N:1)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product for the order item.
     * Relasi: OrderItem → Product (N:1)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}