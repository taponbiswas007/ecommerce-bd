<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        // Only allow customers to submit reviews
        if (!$request->user() || !$request->user()->isCustomer()) {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can submit reviews.'
            ], 403);
        }

        $validated = $request->validate([
            'product_id' => 'required', // hashid
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'required|string',
        ]);

        $product = \App\Models\Product::findByHashid($validated['product_id']);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid product.'
            ], 404);
        }
        $user = $request->user();

        $review = new \App\Models\Review();
        $review->product_id = $product->id;
        $review->user_id = $user->id;
        $review->name = $user->name;
        $review->email = $user->email;
        $review->rating = $validated['rating'];
        $review->comment = $validated['review'];
        $review->status = 'pending';
        $review->is_verified_purchase = false; // Optionally set true if you want to check order history
        if (!empty($validated['title'])) {
            $review->comment = '<strong>' . e($validated['title']) . ":</strong> " . $review->comment;
        }
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully! Your review will be visible after approval.'
        ]);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
