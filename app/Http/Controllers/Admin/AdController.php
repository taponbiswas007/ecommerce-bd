<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = Ad::orderByDesc('id')->get();
        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:255',
            'badge' => 'required|string|max:255',
        ]);
        Ad::create($validated);
        return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ad $ad)
    {
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:255',
            'badge' => 'required|string|max:255',
        ]);
        $ad->update($validated);
        return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ad $ad)
    {
        $ad->delete();
        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted successfully.');
    }
}
