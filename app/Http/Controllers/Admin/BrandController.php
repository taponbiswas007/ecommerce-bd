<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::withCount('products')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,NULL,id,deleted_at,NULL',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
        ]);

        try {
            $slug = Str::slug($request->name);

            // Slug must be unique (including soft-deleted records)
            if (Brand::withTrashed()->where('slug', $slug)->exists()) {
                return redirect()->back()
                    ->withErrors(['name' => 'Brand name already exists. Please use a different name.'])
                    ->withInput();
            }

            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $slug;
            $brand->description = $request->description;
            $brand->website = $request->website;
            $brand->contact_email = $request->contact_email;
            $brand->contact_phone = $request->contact_phone;
            $brand->contact_address = $request->contact_address;
            $brand->country = $request->country;
            $brand->founded_year = $request->founded_year;
            $brand->sort_order = $request->sort_order ?? 0;
            $brand->is_active = $request->is_active ?? true;
            $brand->is_featured = $request->is_featured ?? false;
            $brand->meta_title = $request->meta_title;
            $brand->meta_description = $request->meta_description;
            $brand->meta_keywords = $request->meta_keywords;
            $brand->social_links = $request->social_links ?? [];
            $brand->created_by = Auth::id();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . Str::slug($request->name) . '.' . $logo->getClientOriginalExtension();

                // Create directory if not exists
                $path = 'brands/' . date('Y/m');
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                // Save original logo
                $logoPath = $path . '/' . $logoName;
                Storage::disk('public')->put($logoPath, file_get_contents($logo));

                // Create thumbnail (300x300)
                $thumbnailPath = $path . '/thumb_' . $logoName;
                $thumbnail = Image::read($logo)
                    ->cover(300, 300)
                    ->toJpeg();
                Storage::disk('public')->put($thumbnailPath, $thumbnail);

                $brand->logo = $logoPath;
            }

            $brand->save();

            return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        $brand->load(['products' => function ($query) {
            $query->withCount('reviews')->latest()->take(10);
        }]);

        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id . ',id,deleted_at,NULL',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
        ]);

        try {
            // Update slug if name changed
            if ($brand->name !== $request->name) {
                $slug = Str::slug($request->name);
                $slugExists = Brand::withTrashed()
                    ->where('slug', $slug)
                    ->where('id', '!=', $brand->id)
                    ->exists();

                if ($slugExists) {
                    return redirect()->back()
                        ->withErrors(['name' => 'Brand name already exists. Please use a different name.'])
                        ->withInput();
                }

                $brand->slug = $slug;
            }

            $brand->name = $request->name;
            $brand->description = $request->description;
            $brand->website = $request->website;
            $brand->contact_email = $request->contact_email;
            $brand->contact_phone = $request->contact_phone;
            $brand->contact_address = $request->contact_address;
            $brand->country = $request->country;
            $brand->founded_year = $request->founded_year;
            $brand->sort_order = $request->sort_order ?? 0;
            $brand->is_active = $request->is_active ?? true;
            $brand->is_featured = $request->is_featured ?? false;
            $brand->meta_title = $request->meta_title;
            $brand->meta_description = $request->meta_description;
            $brand->meta_keywords = $request->meta_keywords;
            $brand->social_links = $request->social_links ?? [];
            $brand->updated_by = Auth::id();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($brand->logo) {
                    Storage::disk('public')->delete($brand->logo);
                    // Delete thumbnail
                    $oldThumbPath = str_replace(basename($brand->logo), 'thumb_' . basename($brand->logo), $brand->logo);
                    Storage::disk('public')->delete($oldThumbPath);
                }

                $logo = $request->file('logo');
                $logoName = time() . '_' . Str::slug($request->name) . '.' . $logo->getClientOriginalExtension();

                // Create directory if not exists
                $path = 'brands/' . date('Y/m');
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                // Save original logo
                $logoPath = $path . '/' . $logoName;
                Storage::disk('public')->put($logoPath, file_get_contents($logo));

                // Create thumbnail (300x300)
                $thumbnailPath = $path . '/thumb_' . $logoName;
                $thumbnail = Image::read($logo)
                    ->cover(300, 300)
                    ->toJpeg();
                Storage::disk('public')->put($thumbnailPath, $thumbnail);

                $brand->logo = $logoPath;
            }

            $brand->save();

            return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            // Check if brand has products
            if ($brand->products()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete brand with associated products!');
            }

            // Soft delete the brand
            $brand->delete();

            return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update brand status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $brand = Brand::findOrFail($id);
            $brand->is_active = $request->is_active;
            $brand->save();

            return response()->json([
                'success' => true,
                'message' => 'Brand status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder brands
     */
    public function reorder(Request $request)
    {
        try {
            $orders = $request->orders;

            foreach ($orders as $order) {
                Brand::where('id', $order['id'])->update(['sort_order' => $order['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Brands reordered successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk action
     */
    public function bulkAction(Request $request)
    {
        try {
            $action = $request->action;
            $ids = $request->ids;

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least one brand!'
                ], 400);
            }

            switch ($action) {
                case 'delete':
                    Brand::whereIn('id', $ids)->delete();
                    $message = 'Selected brands deleted successfully!';
                    break;

                case 'activate':
                    Brand::whereIn('id', $ids)->update(['is_active' => true]);
                    $message = 'Selected brands activated successfully!';
                    break;

                case 'deactivate':
                    Brand::whereIn('id', $ids)->update(['is_active' => false]);
                    $message = 'Selected brands deactivated successfully!';
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action!'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show trashed brands
     */
    public function trashed()
    {
        $brands = Brand::onlyTrashed()
            ->withCount('products')
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return view('admin.brands.trashed', compact('brands'));
    }

    /**
     * Restore trashed brand
     */
    public function restore($id)
    {
        try {
            $brand = Brand::onlyTrashed()->findOrFail($id);
            $brand->restore();

            return redirect()->back()->with('success', 'Brand restored successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete brand
     */
    public function forceDelete($id)
    {
        try {
            $brand = Brand::onlyTrashed()->findOrFail($id);

            // Delete logo if exists
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
                // Delete thumbnail
                $thumbPath = str_replace(basename($brand->logo), 'thumb_' . basename($brand->logo), $brand->logo);
                Storage::disk('public')->delete($thumbPath);
            }

            $brand->forceDelete();

            return redirect()->back()->with('success', 'Brand permanently deleted!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
