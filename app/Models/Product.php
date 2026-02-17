<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image_url',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the category that owns the product.
     * Relasi: Product → Category (N:1)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the cart items for the product.
     * Relasi: Product → CartItems (1:N)
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for the product.
     * Relasi: Product → OrderItems (1:N)
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}