<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Show the privacy policy.
     */
    public function index()
    {
        $privacyPolicy = PrivacyPolicy::first();
        return view('admin.terms-privacy.privacy.index', compact('privacyPolicy'));
    }

    /**
     * Show the form for creating a new privacy policy.
     */
    public function create()
    {
        return view('admin.terms-privacy.privacy.create');
    }

    /**
     * Store a newly created privacy policy in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Delete all existing privacy policies and create new
        PrivacyPolicy::truncate();
        PrivacyPolicy::create($validated);

        return redirect()->route('admin.privacy-policy.index')
            ->with('success', 'Privacy Policy updated successfully!');
    }

    /**
     * Show the form for editing the privacy policy.
     */
    public function edit($id)
    {
        $privacyPolicy = PrivacyPolicy::findOrFail($id);
        return view('admin.terms-privacy.privacy.edit', compact('privacyPolicy'));
    }

    /**
     * Update the specified privacy policy in storage.
     */
    public function update(Request $request, $id)
    {
        $privacyPolicy = PrivacyPolicy::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $privacyPolicy->update($validated);

        return redirect()->route('admin.privacy-policy.index')
            ->with('success', 'Privacy Policy updated successfully!');
    }

    /**
     * Remove the specified privacy policy from storage.
     */
    public function destroy($id)
    {
        $privacyPolicy = PrivacyPolicy::findOrFail($id);
        $privacyPolicy->delete();

        return redirect()->route('admin.privacy-policy.index')
            ->with('success', 'Privacy Policy deleted successfully!');
    }
}
