<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::withCount('products')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units',
            'symbol' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            Unit::create([
                'name' => $request->name,
                'symbol' => $request->symbol,
                'description' => $request->description,
                'is_active' => $request->is_active ?? true,
            ]);

            return redirect()->route('admin.units.index')
                ->with('success', 'Unit created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating unit: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::with(['products' => function ($query) {
            $query->with('category')->latest()->limit(10);
        }])
            ->withCount('products')
            ->findOrFail($id);

        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $unit = Unit::findOrFail($id);
        return view('admin.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $id,
            'symbol' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $unit->update([
                'name' => $request->name,
                'symbol' => $request->symbol,
                'description' => $request->description,
                'is_active' => $request->is_active ?? true,
            ]);

            return redirect()->route('admin.units.index')
                ->with('success', 'Unit updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating unit: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);

        // Check if unit has products
        if ($unit->products()->count() > 0) {
            return back()->with('error', 'Cannot delete unit with associated products. Please reassign products to another unit first.');
        }

        try {
            $unit->delete();

            return redirect()->route('admin.units.index')
                ->with('success', 'Unit deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting unit: ' . $e->getMessage());
        }
    }

    /**
     * Update unit status (Active/Inactive)
     */
    public function updateStatus(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'status' => 'required|boolean'
        ]);

        $unit->is_active = $request->status;
        $unit->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    }

    /**
     * Bulk actions (Delete, Activate, Deactivate)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:units,id'
        ]);

        try {
            $units = Unit::whereIn('id', $request->ids)->get();

            switch ($request->action) {
                case 'delete':
                    // Check if any unit has products
                    $unitsWithProducts = $units->filter(function ($unit) {
                        return $unit->products()->count() > 0;
                    });

                    if ($unitsWithProducts->count() > 0) {
                        return back()->with('error', 'Cannot delete units with associated products.');
                    }

                    Unit::whereIn('id', $request->ids)->delete();
                    $message = 'Units deleted successfully!';
                    break;

                case 'activate':
                    Unit::whereIn('id', $request->ids)->update(['is_active' => true]);
                    $message = 'Units activated successfully!';
                    break;

                case 'deactivate':
                    Unit::whereIn('id', $request->ids)->update(['is_active' => false]);
                    $message = 'Units deactivated successfully!';
                    break;
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Quick add unit via AJAX
     */
    public function quickAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,NULL,id',
            'short_code' => 'required|string|max:10',
        ]);
        try {
            $unit = \App\Models\Unit::create([
                'name' => $request->name,
                'symbol' => $request->short_code, // Use short_code as symbol for DB
                'is_active' => true,
            ]);
            return response()->json([
                'success' => true,
                'unit' => [
                    'id' => $unit->id,
                    'name' => $unit->name,
                    'short_code' => $unit->symbol, // Return symbol as short_code for frontend
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
