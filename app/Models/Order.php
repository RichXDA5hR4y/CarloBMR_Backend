<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'payment_proof',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'whatsapp_message'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user that owns the order.
     * Relasi: Order → User (N:1)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order.
     * Relasi: Order → OrderItems (1:N)
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment for the order.
     * Relasi: Order → Payment (1:1)
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}