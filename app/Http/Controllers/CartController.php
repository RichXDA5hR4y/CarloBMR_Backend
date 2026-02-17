<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the specified cart
     * GET /api/cart
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->with('items.product')
                    ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        return response()->json($cart);
    }

    /**
     * Store a newly created cart item
     * POST /api/cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();
        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        // Get or create cart
        $cart = Cart::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'active'
            ]);
        }

        // Check if product already in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity
            ]);
        } else {
            // Create new cart item
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }

        return response()->json($cartItem->load('product'), 201);
    }

    /**
     * Update the specified cart item
     * PUT /api/cart/{id}
     */
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($cartItem->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Insufficient stock'], 400);
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return response()->json($cartItem->load('product'));
    }

    /**
     * Remove the specified cart item
     * DELETE /api/cart/{id}
     */
    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    /**
     * Clear the cart
     * DELETE /api/cart
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)
                    ->where('status', 'active')
                    ->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        return response()->json(['message' => 'Cart cleared']);
    }
}