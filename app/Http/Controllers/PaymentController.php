<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Store a newly created payment
     * POST /api/payments/{order_id}
     */
    public function store(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $request->validate([
            'payment_method' => 'required|in:transfer,cash',
            'bank_name' => 'required_if:payment_method,transfer',
            'account_number' => 'required_if:payment_method,transfer',
            'account_name' => 'required_if:payment_method,transfer',
            'amount' => 'required|integer|min:0',
            'proof_image' => 'nullable|image|max:2048'
        ]);

        $paymentData = [
            'order_id' => $orderId,
            'payment_method' => $request->payment_method,
            'bank_name' => $request->bank_name ?? null,
            'account_number' => $request->account_number ?? null,
            'account_name' => $request->account_name ?? null,
            'amount' => $request->amount,
            'status' => 'pending'
        ];

        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('payments', 'public');
            $paymentData['proof_image'] = $path;
        }

        $payment = Payment::create($paymentData);

        return response()->json($payment, 201);
    }

    /**
     * Update the specified payment
     * PUT /api/payments/{id}
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string'
        ]);

        $payment->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        // Update order payment status
        if ($request->status === 'verified') {
            $payment->order()->update(['payment_status' => 'verified']);
        }

        return response()->json($payment);
    }

    /**
     * Get payment info (rekening tujuan)
     * GET /api/payments/info
     */
    public function getPaymentInfo()
    {
        // Default payment info
        return response()->json([
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'Carlo BMR Shop'
        ]);
    }
}