<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for customer
     * GET /api/orders
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Order::where('user_id', $user->id)
                      ->with('items.product')
                      ->orderBy('created_at', 'desc');

        $orders = $query->paginate($request->get('per_page', 10));

        return response()->json($orders);
    }

    /**
     * Display the specified order
     * GET /api/orders/{id}
     */
    public function show($id)
    {
        $order = Order::where('user_id', auth()->id())
                      ->with('items.product')
                      ->findOrFail($id);

        return response()->json($order);
    }

    /**
     * Store a newly created order
     * POST /api/orders
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $user = $request->user();

        // Get active cart
        $cart = Cart::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->with('items.product')
                    ->first();

        if (!$cart || $cart->items->count() === 0) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Calculate total
        $totalAmount = $cart->items->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // Generate order number
        $orderNumber = 'BMR-' . date('Ymd') . '-' . Str::padLeft(Order::count() + 1, 3, '0');

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => 'transfer',
            'payment_status' => 'unpaid',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'notes' => $request->notes
        ]);

        // Create order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->quantity * $item->price
            ]);

            // Reduce stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Mark cart as completed
        $cart->update(['status' => 'completed']);

        return response()->json($order->load('items.product'), 201);
    }

    /**
     * Update payment proof
     * POST /api/orders/{id}/payment-proof
     */
    public function updatePaymentProof(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'payment_proof' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payments', 'public');
            $order->update(['payment_proof' => $path, 'payment_status' => 'paid']);
        }

        return response()->json($order);
    }

    // ==================== ADMIN METHODS ====================

    /**
     * Display a listing of orders for admin
     * GET /api/admin/orders
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'items.product'])
                      ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $orders = $query->paginate($request->get('per_page', 10));

        return response()->json($orders);
    }

    /**
     * Update order status
     * PUT /api/admin/orders/{id}/status
     */
    public function adminUpdateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return response()->json($order);
    }

    /**
     * Update payment status
     * PUT /api/admin/orders/{id}/payment-status
     */
    public function adminUpdatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,verified'
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return response()->json($order);
    }
}