<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = \App\Models\Review::with(['product', 'user'])
            ->orderByDesc('created_at')
            ->paginate(25);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Approve a review.
     */
    public function approve($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->status = 'approved';
        $review->save();
        return redirect()->back()->with('success', 'Review approved.');
    }

    /**
     * Reject a review.
     */
    public function reject($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->status = 'rejected';
        $review->save();
        return redirect()->back()->with('success', 'Review rejected.');
    }

    /**
     * Delete a review.
     */
    public function destroy($id)
    {
        $review = \App\Models\Review::findOrFail($id);
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted.');
    }
}
