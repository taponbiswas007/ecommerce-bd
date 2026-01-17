<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = \App\Models\Order::with(['user', 'items'])->orderByDesc('created_at')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For manual order entry, you may want to pass products, users, etc.
        return view('admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_status' => 'required|string',
            'shipping_name' => 'required|string',
            'shipping_phone' => 'required|string',
            'shipping_address' => 'required|string',
            // Add more validation as needed
        ]);

        $order = \App\Models\Order::create($validated);
        // Optionally handle items, etc.
        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = \App\Models\Order::with(['user', 'items'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $order = \App\Models\Order::with(['user', 'items'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $rules = [
            'order_status' => 'required|string',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ];
        // If status is shipped, require a document
        if ($request->order_status === 'shipped') {
            $rules['delivery_document'] = 'required|file|mimes:png,jpg,jpeg,pdf|max:5120';
        } else {
            $rules['delivery_document'] = 'nullable|file|mimes:png,jpg,jpeg,pdf|max:5120';
        }
        $validated = $request->validate($rules);

        // Handle file upload
        if ($request->hasFile('delivery_document')) {
            $file = $request->file('delivery_document');
            $path = $file->store('order_documents', 'public');
            $validated['delivery_document'] = $path;
        } else {
            unset($validated['delivery_document']);
        }

        $order->update($validated);
        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
