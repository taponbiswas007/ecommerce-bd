<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;

class PaymentAccountController extends Controller
{
    public function index()
    {
        $accounts = PaymentAccount::orderByDesc('is_active')->orderBy('method')->orderBy('account_name')->paginate(20);
        return view('admin.payment-accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('admin.payment-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'method' => 'required|in:bkash,rocket,bank_transfer',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'instructions' => 'nullable|string|max:5000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        PaymentAccount::create($validated);

        return redirect()->route('admin.payment-accounts.index')->with('success', 'Payment account created.');
    }

    public function show(PaymentAccount $paymentAccount)
    {
        return view('admin.payment-accounts.show', compact('paymentAccount'));
    }

    public function edit(PaymentAccount $paymentAccount)
    {
        return view('admin.payment-accounts.edit', compact('paymentAccount'));
    }

    public function update(Request $request, PaymentAccount $paymentAccount)
    {
        $validated = $request->validate([
            'method' => 'required|in:bkash,rocket,bank_transfer',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'branch' => 'nullable|string|max:255',
            'instructions' => 'nullable|string|max:5000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $paymentAccount->update($validated);

        return redirect()->route('admin.payment-accounts.index')->with('success', 'Payment account updated.');
    }

    public function destroy(PaymentAccount $paymentAccount)
    {
        $paymentAccount->delete();
        return redirect()->route('admin.payment-accounts.index')->with('success', 'Payment account deleted.');
    }
}
