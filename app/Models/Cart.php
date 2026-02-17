<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user that owns the cart.
     * Relasi: Cart → User (N:1)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items for the cart.
     * Relasi: Cart → CartItems (1:N)
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}