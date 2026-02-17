<?php

namespace App\Services;

use App\Models\Category;
use App\Models\DropshippingProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DropshippingLocalProductService
{
    private const USD_TO_BDT = 128;

    public function ensureLocalProduct(DropshippingProduct $dropshippingProduct): Product
    {
        if ($dropshippingProduct->local_product_id) {
            $localProduct = Product::find($dropshippingProduct->local_product_id);
            if ($localProduct) {
                // Sync images if missing
                $this->syncDropshippingImages($localProduct, $dropshippingProduct);
                return $localProduct;
            }
        }

        $category = Category::where('is_active', true)->orderBy('order')->first() ?? Category::first();
        $unit = Unit::first();

        if (!$category || !$unit) {
            throw new \Exception('Default category or unit is missing.');
        }

        $baseSlug = Str::slug($dropshippingProduct->name ?: 'dropshipping-product');
        $slug = $baseSlug . '-ds-' . $dropshippingProduct->id;
        $suffix = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-ds-' . $dropshippingProduct->id . '-' . $suffix;
            $suffix++;
        }

        $priceBdt = (float) ($dropshippingProduct->selling_price * self::USD_TO_BDT);

        $product = Product::create([
            'name' => $dropshippingProduct->name,
            'slug' => $slug,
            'short_description' => Str::limit(strip_tags($dropshippingProduct->description ?? ''), 200),
            'full_description' => $dropshippingProduct->description,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'base_price' => $priceBdt,
            'discount_price' => null,
            'stock_quantity' => $dropshippingProduct->stock,
            'min_order_quantity' => $dropshippingProduct->minimum_order_quantity ?? 1,
            'is_featured' => false,
            'is_active' => true,
        ]);

        // Download all images from dropshipping product
        $imageUrls = $this->extractAllDropshippingImageUrls($dropshippingProduct->image_url);
        if (!empty($imageUrls)) {
            foreach ($imageUrls as $index => $imageUrl) {
                $imagePath = $this->downloadDropshippingImage($imageUrl, $product->id, $index);
                if ($imagePath) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'is_primary' => ($index === 0), // First image is primary
                        'display_order' => $index,
                    ]);
                }
            }
        }

        $dropshippingProduct->local_product_id = $product->id;
        $dropshippingProduct->save();

        return $product;
    }

    /**
     * Sync all images from dropshipping product to local product
     */
    private function syncDropshippingImages(Product $localProduct, DropshippingProduct $dropshippingProduct): void
    {
        $imageUrls = $this->extractAllDropshippingImageUrls($dropshippingProduct->image_url);
        $existingImagesCount = $localProduct->images()->count();

        // Only sync if dropshipping product has more images than local product
        if (count($imageUrls) > $existingImagesCount) {
            // Get the highest display_order
            $maxOrder = $localProduct->images()->max('display_order') ?? -1;

            // Download missing images (skip the ones already downloaded)
            $imagesToDownload = array_slice($imageUrls, $existingImagesCount);

            foreach ($imagesToDownload as $index => $imageUrl) {
                $actualIndex = $existingImagesCount + $index;
                $imagePath = $this->downloadDropshippingImage($imageUrl, $localProduct->id, $actualIndex);
                if ($imagePath) {
                    ProductImage::create([
                        'product_id' => $localProduct->id,
                        'image_path' => $imagePath,
                        'is_primary' => false, // Only the first image should be primary
                        'display_order' => $maxOrder + 1 + $index,
                    ]);
                }
            }
        }
    }

    /**
     * Extract all image URLs from dropshipping product image_url field
     */
    private function extractAllDropshippingImageUrls($imageUrl): array
    {
        $urls = [];

        // If it's a JSON array string, decode it
        if (is_string($imageUrl) && str_starts_with(trim($imageUrl), '[')) {
            $decoded = json_decode($imageUrl, true);
            if (is_array($decoded) && !empty($decoded)) {
                $urls = $decoded;
            }
        } elseif (is_array($imageUrl)) {
            // Already an array
            $urls = $imageUrl;
        } elseif (is_string($imageUrl) && !empty($imageUrl)) {
            // Single URL string
            $urls = [$imageUrl];
        }

        // Convert http:// to https://
        $urls = array_map(function ($url) {
            if ($url && str_starts_with($url, 'http://')) {
                return 'https://' . substr($url, 7);
            }
            return $url;
        }, $urls);

        // Filter out empty values
        return array_filter($urls);
    }

    private function extractDropshippingImageUrl($imageUrl): ?string
    {
        if (is_string($imageUrl) && str_starts_with(trim($imageUrl), '[')) {
            $decoded = json_decode($imageUrl, true);
            if (is_array($decoded) && !empty($decoded)) {
                $imageUrl = $decoded[0];
            }
        }

        if ($imageUrl && str_starts_with($imageUrl, 'http://')) {
            $imageUrl = 'https://' . substr($imageUrl, 7);
        }

        return $imageUrl ?: null;
    }

    private function downloadDropshippingImage(string $imageUrl, int $productId, int $index = 0): ?string
    {
        try {
            $response = Http::timeout(15)->get($imageUrl);
            if (!$response->ok()) {
                return null;
            }

            $pathInfo = pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? 'image.jpg');
            $extension = $pathInfo['extension'] ?? 'jpg';
            $filename = 'products/dropshipping/' . $productId . '-' . $index . '-' . Str::random(6) . '.' . $extension;

            Storage::disk('public')->put($filename, $response->body());
            return $filename;
        } catch (\Exception $e) {
            return null;
        }
    }
}
