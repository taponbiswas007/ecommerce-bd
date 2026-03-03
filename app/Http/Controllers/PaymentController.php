<?php

namespace App\Http\Controllers;

use App\Mail\PaymentProofSubmittedMail;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\PaymentAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    /**
     * Show payment / order summary for the given order
     */
    public function show(Order $order)
    {
        // Ensure the authenticated user owns the order
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $order->loadMissing('paymentAccount');

        return view('payment.show', compact('order'));
    }

    public function process(Request $request, Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_reference' => 'required|string|max:255',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $selectedAccount = PaymentAccount::where('id', $order->payment_account_id)
            ->where('is_active', true)
            ->where('method', $order->payment_method)
            ->first();

        if (!$selectedAccount) {
            return back()
                ->withErrors([
                    'payment_account_id' => 'Admin has not assigned a valid payment account for this order yet.',
                ])
                ->with('error', 'Payment submit করা যায়নি। Admin account assignment verify করে আবার চেষ্টা করুন।')
                ->withInput();
        }

        $proofPath = $request->hasFile('payment_proof')
            ? $request->file('payment_proof')->store('payment_proofs', 'public')
            : $order->payment_proof_path;

        $order->update([
            'payment_reference' => $validated['payment_reference'],
            'payment_account_id' => $selectedAccount->id,
            'payment_proof_path' => $proofPath,
            'payment_status' => 'pending',
            'negotiation_status' => 'proof_submitted',
            'negotiation_updated_at' => now(),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->order_status,
            'previous_status' => $order->order_status,
            'notes' => 'Customer submitted payment proof. Ref: ' . $validated['payment_reference'],
            'document_path' => $proofPath,
            'document_name' => $request->hasFile('payment_proof') ? $request->file('payment_proof')->getClientOriginalName() : null,
            'updated_by' => Auth::id(),
            'status_date' => now(),
        ]);

        $order->loadMissing(['user', 'paymentAccount']);
        $adminEmails = User::where('role', 'admin')->whereNotNull('email')->pluck('email')->unique()->values();
        if ($adminEmails->isNotEmpty()) {
            Mail::to($adminEmails->all())->send(new PaymentProofSubmittedMail($order));
        }

        return redirect()->route('customer.orders.show', $order)->with('success', 'Payment proof submitted successfully. Admin will verify soon.');
    }

    public function success(Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return redirect()->route('customer.orders.show', $order)->with('success', 'Payment step completed.');
    }

    public function cancel(Order $order)
    {
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return redirect()->route('customer.orders.show', $order)->with('error', 'Payment step cancelled. You can submit proof later.');
    }
}
