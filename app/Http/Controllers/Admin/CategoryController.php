<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->orderBy('order')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        try {
            $slug = Str::slug($request->name);

            // Check if slug already exists
            $slugCount = Category::where('slug', $slug)->count();
            if ($slugCount > 0) {
                $slug = $slug . '-' . ($slugCount + 1);
            }

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->parent_id = $request->parent_id;
            $category->order = $request->order ?? 0;
            $category->is_active = $request->is_active ?? true;
            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
            $category->meta_keywords = $request->meta_keywords;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();

                // Create directory if not exists
                $path = 'categories/' . date('Y/m');
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                // Save original image
                $imagePath = $path . '/' . $imageName;
                Storage::disk('public')->put($imagePath, file_get_contents($image));

                // Create thumbnail (300x300)
                $thumbnailPath = $path . '/thumb_' . $imageName;
                $thumbnail = Image::make($image)->fit(300, 300)->encode();
                Storage::disk('public')->put($thumbnailPath, $thumbnail);

                $category->image = $imagePath;
            }

            $category->save();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating category: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with(['parent', 'children', 'products'])
            ->withCount('products')
            ->findOrFail($id);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        try {
            // Check if slug needs to be updated
            if ($category->name != $request->name) {
                $slug = Str::slug($request->name);

                // Check if slug already exists
                $slugCount = Category::where('slug', $slug)
                    ->where('id', '!=', $id)
                    ->count();

                if ($slugCount > 0) {
                    $slug = $slug . '-' . ($slugCount + 1);
                }

                $category->slug = $slug;
            }

            $category->name = $request->name;
            $category->description = $request->description;
            $category->parent_id = $request->parent_id;
            $category->order = $request->order ?? 0;
            $category->is_active = $request->is_active ?? true;
            $category->meta_title = $request->meta_title;
            $category->meta_description = $request->meta_description;
            $category->meta_keywords = $request->meta_keywords;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);

                    // Also delete thumbnail if exists
                    $thumbPath = dirname($category->image) . '/thumb_' . basename($category->image);
                    if (Storage::disk('public')->exists($thumbPath)) {
                        Storage::disk('public')->delete($thumbPath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::slug($request->name) . '.' . $image->getClientOriginalExtension();

                // Create directory if not exists
                $path = 'categories/' . date('Y/m');
                if (!Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->makeDirectory($path);
                }

                // Save original image
                $imagePath = $path . '/' . $imageName;
                Storage::disk('public')->put($imagePath, file_get_contents($image));

                // Create thumbnail (300x300)
                $thumbnailPath = $path . '/thumb_' . $imageName;
                $thumbnail = Image::make($image)->fit(300, 300)->encode();
                Storage::disk('public')->put($thumbnailPath, $thumbnail);

                $category->image = $imagePath;
            }

            $category->save();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with associated products. Please move or delete products first.');
        }

        // Check if category has subcategories
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Cannot delete category with subcategories. Please delete subcategories first.');
        }

        try {
            // Delete image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);

                // Also delete thumbnail if exists
                $thumbPath = dirname($category->image) . '/thumb_' . basename($category->image);
                if (Storage::disk('public')->exists($thumbPath)) {
                    Storage::disk('public')->delete($thumbPath);
                }
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    /**
     * Update category status (Active/Inactive)
     */
    public function updateStatus(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'status' => 'required|boolean'
        ]);

        $category->is_active = $request->status;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    }

    /**
     * Reorder categories
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($request->categories as $item) {
                Category::where('id', $item['id'])->update(['order' => $item['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categories reordered successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reordering categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions (Delete, Activate, Deactivate)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        try {
            $categories = Category::whereIn('id', $request->ids)->get();

            switch ($request->action) {
                case 'delete':
                    // Check if any category has products
                    $categoriesWithProducts = $categories->filter(function ($category) {
                        return $category->products()->count() > 0;
                    });

                    if ($categoriesWithProducts->count() > 0) {
                        return back()->with('error', 'Cannot delete categories with associated products.');
                    }

                    // Check if any category has subcategories
                    $categoriesWithChildren = $categories->filter(function ($category) {
                        return $category->children()->count() > 0;
                    });

                    if ($categoriesWithChildren->count() > 0) {
                        return back()->with('error', 'Cannot delete categories with subcategories.');
                    }

                    // Delete images
                    foreach ($categories as $category) {
                        if ($category->image && Storage::disk('public')->exists($category->image)) {
                            Storage::disk('public')->delete($category->image);

                            $thumbPath = dirname($category->image) . '/thumb_' . basename($category->image);
                            if (Storage::disk('public')->exists($thumbPath)) {
                                Storage::disk('public')->delete($thumbPath);
                            }
                        }
                    }

                    Category::whereIn('id', $request->ids)->delete();
                    $message = 'Categories deleted successfully!';
                    break;

                case 'activate':
                    Category::whereIn('id', $request->ids)->update(['is_active' => true]);
                    $message = 'Categories activated successfully!';
                    break;

                case 'deactivate':
                    Category::whereIn('id', $request->ids)->update(['is_active' => false]);
                    $message = 'Categories deactivated successfully!';
                    break;
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Error performing bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Get categories for datatable (AJAX)
     */
    public function getCategories(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $searchValue = $request->get('search')['value'] ?? '';
        $orderColumn = $request->get('order')[0]['column'] ?? 0;
        $orderDirection = $request->get('order')[0]['dir'] ?? 'asc';

        $columns = ['id', 'name', 'parent_id', 'order', 'is_active', 'created_at'];
        $orderColumnName = $columns[$orderColumn] ?? 'id';

        $query = Category::with('parent');

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('description', 'like', '%' . $searchValue . '%')
                    ->orWhere('meta_title', 'like', '%' . $searchValue . '%')
                    ->orWhereHas('parent', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%' . $searchValue . '%');
                    });
            });
        }

        $totalRecords = $query->count();

        $categories = $query->orderBy($orderColumnName, $orderDirection)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->id,
                'name' => '<div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        ' . ($category->image ?
                    '<img src="' . asset('storage/' . $category->image) . '" alt="' . $category->name . '" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">'
                    : '<div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-tag text-muted"></i>
                            </div>'
                ) . '
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">' . $category->name . '</h6>
                        <small class="text-muted">' . ($category->parent ? 'Subcategory of: ' . $category->parent->name : 'Main Category') . '</small>
                    </div>
                </div>',
                'products_count' => '<span class="badge bg-primary">' . $category->products_count . ' products</span>',
                'order' => $category->order,
                'status' => '<span class="badge bg-' . ($category->is_active ? 'success' : 'danger') . '">' .
                    ($category->is_active ? 'Active' : 'Inactive') . '</span>',
                'actions' => '
                    <div class="btn-group">
                        <a href="' . route('admin.categories.show', $category->id) . '" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . route('admin.categories.edit', $category->id) . '" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="' . route('admin.categories.destroy', $category->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger confirm-delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                '
            ];
        }

        return response()->json([
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ]);
    }
    /**
     * Check slug availability
     */
    public function checkSlug(Request $request)
    {
        $slug = Str::slug($request->name);

        // Check if slug already exists
        $slugCount = Category::where('slug', $slug)->count();
        if ($slugCount > 0) {
            $slug = $slug . '-' . ($slugCount + 1);
        }

        return response()->json(['slug' => $slug]);
    }
}
