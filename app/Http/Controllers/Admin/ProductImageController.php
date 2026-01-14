<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class ProductImageController extends Controller
{
    // Show all images for a product
    public function index(Product $product)
    {
        $images = $product->images()->ordered()->get();
        return view('admin.product-images.index', compact('product', 'images'));
    }

    // Show upload form
    public function create(Product $product)
    {
        return view('admin.product-images.create', compact('product'));
    }

    // Store multiple images
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'alt_text' => 'nullable|string|max:255',
            'is_featured' => 'sometimes|boolean',
            'display_order' => 'nullable|integer',
        ]);

        $uploadedImages = [];
        $displayOrder = $product->images()->max('display_order') ?? 0;

        foreach ($request->file('images') as $index => $image) {
            try {
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                // Store original image
                $path = $image->storeAs('products/' . $product->id, $filename, 'public');

                // Create multiple sizes (optional)
                $this->createImageSizes($image, $product->id, $filename);

                // Create image record
                $imageData = [
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'alt_text' => $request->alt_text ?: pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME),
                    'display_order' => $displayOrder + $index + 1,
                ];

                // Set first image as primary if no primary exists
                if ($index === 0 && !$product->images()->where('is_primary', true)->exists()) {
                    $imageData['is_primary'] = true;
                }

                $productImage = ProductImage::create($imageData);
                $uploadedImages[] = $productImage;
            } catch (\Exception $e) {
                // Clean up uploaded files on error
                foreach ($uploadedImages as $uploadedImage) {
                    Storage::disk('public')->delete($uploadedImage->image_path);
                    $uploadedImage->delete();
                }

                return redirect()->back()
                    ->with('error', 'Failed to upload images: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return redirect()->route('admin.products.images.index', $product->id)
            ->with('success', count($uploadedImages) . ' images uploaded successfully.');
    }

    // Show single image
    public function show(Product $product, ProductImage $image)
    {
        return view('admin.product-images.show', compact('product', 'image'));
    }

    // Edit image
    public function edit(Product $product, ProductImage $image)
    {
        return view('admin.product-images.edit', compact('product', 'image'));
    }

    // Update image
    public function update(Request $request, Product $product, ProductImage $image)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer',
            'is_primary' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
        ]);

        $data = $request->only(['alt_text', 'display_order']);

        // Handle primary image
        if ($request->has('is_primary') && $request->is_primary) {
            // Remove primary from other images
            $product->images()->where('is_primary', true)->update(['is_primary' => false]);
            $data['is_primary'] = true;
        } else {
            $data['is_primary'] = false;
        }

        // Handle featured image
        if ($request->has('is_featured') && $request->is_featured) {
            // Remove featured from other images if needed
            $product->images()->where('is_featured', true)->update(['is_featured' => false]);
            $data['is_featured'] = true;
        } else {
            $data['is_featured'] = false;
        }

        // If a new image file is uploaded, delete all old image sizes
        if ($request->hasFile('image')) {
            $oldPath = $image->image_path;
            $paths = [];
            if ($oldPath) {
                $paths[] = $oldPath;
                $filename = basename($oldPath);
                $dir = dirname($oldPath);
                $paths[] = $dir . '/large_' . $filename;
                $paths[] = $dir . '/medium_' . $filename;
                $paths[] = $dir . '/thumb_' . $filename;
            }
            foreach ($paths as $path) {
                if (Storage::disk($image->disk ?? 'public')->exists($path)) {
                    Storage::disk($image->disk ?? 'public')->delete($path);
                }
            }

            // Save new image and sizes
            $newFile = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $newFile->getClientOriginalExtension();
            $path = $newFile->storeAs('products/' . $product->id, $filename, 'public');
            $this->createImageSizes($newFile, $product->id, $filename);
            $data['image_path'] = $path;
        }

        $image->update($data);

        return redirect()->route('admin.products.images.index', $product->id)
            ->with('success', 'Image updated successfully.');
    }

    // Delete image
    public function destroy(Product $product, ProductImage $image)
    {
        // Store image path before deletion
        $imagePath = $image->image_path;
        $isPrimary = $image->is_primary;

        // Delete all image sizes
        $paths = [];
        if ($imagePath) {
            $paths[] = $imagePath;
            $filename = basename($imagePath);
            $dir = dirname($imagePath);
            $paths[] = $dir . '/large_' . $filename;
            $paths[] = $dir . '/medium_' . $filename;
            $paths[] = $dir . '/thumb_' . $filename;
        }
        foreach ($paths as $path) {
            if (Storage::disk($image->disk ?? 'public')->exists($path)) {
                Storage::disk($image->disk ?? 'public')->delete($path);
            }
        }

        $image->delete();

        // If we deleted the primary image, set a new primary
        if ($isPrimary && $product->images()->count() > 0) {
            $newPrimary = $product->images()->first();
            $newPrimary->update(['is_primary' => true]);
        }

        return redirect()->route('admin.products.images.index', $product->id)
            ->with('success', 'Image deleted successfully.');
    }

    // Bulk actions
    public function bulkAction(Request $request, Product $product)
    {
        Log::info('BulkAction request', [
            'all' => $request->all(),
            'ids' => $request->input('ids'),
            'ids_array' => $request->input('ids', []),
            'display_order' => $request->input('display_order'),
        ]);

        // Decode JSON payloads from the frontend form (hidden inputs) so validation sees arrays
        $idsInput = $request->input('ids');
        if (is_string($idsInput)) {
            $decoded = json_decode($idsInput, true);
            $request->merge(['ids' => is_array($decoded) ? $decoded : []]);
        }

        $displayOrderInput = $request->input('display_order');
        if (is_string($displayOrderInput)) {
            $decoded = json_decode($displayOrderInput, true);
            $request->merge(['display_order' => is_array($decoded) ? $decoded : []]);
        }


        // Cast ids to integers for validation
        $ids = array_map('intval', (array) $request->input('ids', []));
        $request->merge(['ids' => $ids]);


        try {
            $request->validate([
                'action' => 'required|in:set_primary,set_featured,delete,update_order',
                'ids' => 'required|array',
                'ids.*' => 'exists:product_images,id',
                'display_order' => 'array|nullable',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('BulkDelete validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            throw $e;
        }

        $ids = $request->ids;

        switch ($request->action) {
            case 'set_primary':
                // Remove primary from all images
                $product->images()->update(['is_primary' => false]);
                // Set selected as primary (take first)
                ProductImage::where('id', $ids[0])->update(['is_primary' => true]);
                $message = 'Primary image set successfully.';
                break;

            case 'set_featured':
                // Remove featured from all images
                $product->images()->update(['is_featured' => false]);
                // Set selected as featured (take first)
                ProductImage::where('id', $ids[0])->update(['is_featured' => true]);
                $message = 'Featured image set successfully.';
                break;

            case 'delete':
                foreach ($ids as $id) {
                    $image = ProductImage::find($id);
                    if ($image) {
                        $paths = [];
                        $imagePath = $image->image_path;
                        if ($imagePath) {
                            $paths[] = $imagePath;
                            $filename = basename($imagePath);
                            $dir = dirname($imagePath);
                            $paths[] = $dir . '/large_' . $filename;
                            $paths[] = $dir . '/medium_' . $filename;
                            $paths[] = $dir . '/thumb_' . $filename;
                        }
                        foreach ($paths as $path) {
                            if (Storage::disk($image->disk ?? 'public')->exists($path)) {
                                Storage::disk($image->disk ?? 'public')->delete($path);
                            }
                        }
                        Log::info('BulkDelete', [
                            'id' => $id,
                            'image_path' => $image->image_path,
                            'all_files_deleted' => $paths,
                        ]);
                        $image->delete();
                    } else {
                        Log::warning('BulkDelete: Image not found', ['id' => $id]);
                    }
                }
                $message = count($ids) . ' images deleted successfully.';
                break;

            case 'update_order':
                $orders = $request->display_order ?? [];

                foreach ($orders as $id => $order) {
                    ProductImage::where('id', $id)->update(['display_order' => $order]);
                }
                $message = 'Display order updated successfully.';
                break;
        }

        return redirect()->route('admin.products.images.index', $product->id)
            ->with('success', $message);
    }

    // Reorder images
    public function reorder(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:product_images,id',
            'images.*.order' => 'required|integer',
        ]);

        foreach ($request->images as $item) {
            ProductImage::where('id', $item['id'])
                ->update(['display_order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order updated successfully.'
        ]);
    }

    // Set as primary
    public function setPrimary(Product $product, ProductImage $image)
    {
        // Remove primary from all images
        $product->images()->update(['is_primary' => false]);

        // Set new primary
        $image->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Primary image updated.'
        ]);
    }

    // Set as featured
    public function setFeatured(Product $product, ProductImage $image)
    {
        // Remove featured from all images
        $product->images()->update(['is_featured' => false]);

        // Set new featured
        $image->update(['is_featured' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Featured image updated.'
        ]);
    }

    // Private method to create image sizes
    private function createImageSizes($image, $productId, $filename)
    {
        try {
            $imageManager = Image::read($image);

            // Large (1200px)
            $largePath = 'products/' . $productId . '/large_' . $filename;
            $imageManager
                ->scale(width: 1200)
                ->save(storage_path('app/public/' . $largePath));

            // Medium (600px)
            $mediumPath = 'products/' . $productId . '/medium_' . $filename;
            $imageManager
                ->scale(width: 600)
                ->save(storage_path('app/public/' . $mediumPath));

            // Thumbnail (150px)
            $thumbPath = 'products/' . $productId . '/thumb_' . $filename;
            $imageManager
                ->scale(width: 150)
                ->save(storage_path('app/public/' . $thumbPath));
        } catch (\Exception $e) {
            Log::error('Image processing failed: ' . $e->getMessage());
        }
    }
}
