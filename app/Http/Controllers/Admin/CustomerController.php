<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
            'district' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        try {
            $customer = new User();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->password = Hash::make($request->password);
            $customer->role = 'customer';
            $customer->district = $request->district;
            $customer->upazila = $request->upazila;
            $customer->address = $request->address;
            $customer->is_active = $request->boolean('is_active');
            $customer->save();

            return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $customer)
    {
        $customer->load('orders');

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $customer->id,
            'password' => 'nullable|string|min:6|confirmed',
            'district' => 'nullable|string|max:255',
            'upazila' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        try {
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            if ($request->filled('password')) {
                $customer->password = Hash::make($request->password);
            }
            $customer->district = $request->district;
            $customer->upazila = $request->upazila;
            $customer->address = $request->address;
            $customer->is_active = $request->boolean('is_active');
            $customer->save();

            return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer)
    {
        try {
            if ($customer->role !== 'customer') {
                return redirect()->back()->with('error', 'Only customers can be deleted here.');
            }

            $customer->delete();

            return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
