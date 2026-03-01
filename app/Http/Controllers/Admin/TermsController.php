<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terms;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    /**
     * Show the terms & conditions.
     */
    public function index()
    {
        $terms = Terms::first();
        return view('admin.terms-privacy.terms.index', compact('terms'));
    }

    /**
     * Show the form for creating a new terms.
     */
    public function create()
    {
        return view('admin.terms-privacy.terms.create');
    }

    /**
     * Store a newly created terms in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Delete all existing terms and create new
        Terms::truncate();
        Terms::create($validated);

        return redirect()->route('admin.terms.index')
            ->with('success', 'Terms & Conditions updated successfully!');
    }

    /**
     * Show the form for editing the terms.
     */
    public function edit($id)
    {
        $terms = Terms::findOrFail($id);
        return view('admin.terms-privacy.terms.edit', compact('terms'));
    }

    /**
     * Update the specified terms in storage.
     */
    public function update(Request $request, $id)
    {
        $terms = Terms::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $terms->update($validated);

        return redirect()->route('admin.terms.index')
            ->with('success', 'Terms & Conditions updated successfully!');
    }

    /**
     * Remove the specified terms from storage.
     */
    public function destroy($id)
    {
        $terms = Terms::findOrFail($id);
        $terms->delete();

        return redirect()->route('admin.terms.index')
            ->with('success', 'Terms & Conditions deleted successfully!');
    }
}
