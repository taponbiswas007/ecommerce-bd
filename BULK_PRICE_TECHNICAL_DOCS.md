# Bulk Price Management - Technical Implementation

## Architecture Overview

```
┌─────────────────────────────────────────────────────┐
│                    User Interface                    │
│  (bulk-price-update.blade.php - Enhanced with Tabs) │
└─────────────────┬───────────────────────────────────┘
                  │
                  │ AJAX Requests
                  ▼
┌─────────────────────────────────────────────────────┐
│              Route Layer (web.php)                   │
│  - /bulk-price/*                                     │
│  - 11 total endpoints                                │
└─────────────────┬───────────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────────┐
│      Controller (BulkProductPriceController)        │
│  - Data validation                                   │
│  - Business logic                                    │
│  - AI integration                                    │
└─────────────┬───────────────────────────────────────┘
              │
              ├──────────┬──────────┬──────────┐
              ▼          ▼          ▼          ▼
         ┌────────┐ ┌────────┐ ┌──────┐ ┌─────────┐
         │Product │ │ Price  │ │ AI   │ │Category │
         │ Model  │ │ Model  │ │Service│ │ Model   │
         └────────┘ └────────┘ └──────┘ └─────────┘
              │          │
              ▼          ▼
         ┌────────────────────┐
         │    Database        │
         │ - products         │
         │ - product_prices   │
         └────────────────────┘
```

## Database Schema

### products table (relevant columns)

```sql
- id (primary key)
- name
- sku
- base_price (decimal)
- discount_price (decimal, nullable)
- category_id (foreign key)
- stock_quantity
- sold_count
- view_count
- is_active
```

### product_prices table (tier pricing)

```sql
- id (primary key)
- product_id (foreign key)
- min_quantity (integer)
- max_quantity (integer, nullable)
- price (decimal)
- created_at
- updated_at
```

## API Endpoints

### 1. GET /admin/bulk-price

**Purpose:** Load main page  
**Returns:** Blade view  
**Auth:** Required

### 2. GET /admin/bulk-price/get-products

**Purpose:** Fetch filtered products  
**Parameters:**

```php
- search: string (optional)
- category_id: integer (optional)
- min_price: decimal (optional)
- max_price: decimal (optional)
- status: boolean (optional)
- discount_filter: enum['with','without'] (optional)
```

**Returns:**

```json
{
  "products": {
    "data": [...],
    "current_page": 1,
    "last_page": 10,
    "total": 500
  },
  "total": 500
}
```

### 3. POST /admin/bulk-price/update-prices

**Purpose:** Update base or discount prices  
**Parameters:**

```php
{
  "update_type": "fixed|percentage|formula",
  "price_field": "base_price|discount_price",
  "ids": [1, 2, 3],

  // For fixed
  "fixed_price": 100.00,

  // For percentage
  "percentage": 10.5,
  "percentage_direction": "increase|decrease",

  // For formula
  "formula_type": "increase|decrease",
  "formula_value": 50.00
}
```

**Returns:**

```json
{
    "success": true,
    "message": "Successfully updated...",
    "count": 3
}
```

### 4. POST /admin/bulk-price/apply-discount

**Purpose:** Apply discount to multiple products  
**Parameters:**

```php
{
  "ids": [1, 2, 3],
  "discount_type": "percentage|fixed|absolute",
  "discount_value": 20.00
}
```

### 5. POST /admin/bulk-price/remove-discount

**Purpose:** Remove discount from products  
**Parameters:**

```php
{
  "ids": [1, 2, 3]
}
```

### 6. POST /admin/bulk-price/apply-tiers

**Purpose:** Add quantity-based pricing  
**Parameters:**

```php
{
  "ids": [1, 2, 3],
  "tiers": [
    {
      "min_quantity": 10,
      "max_quantity": 50,
      "price": 95.00
    },
    {
      "min_quantity": 51,
      "max_quantity": null,
      "price": 90.00
    }
  ]
}
```

### 7-9. AI Endpoints

**Purpose:** Get AI-powered insights  
**Endpoints:**

- POST /admin/bulk-price/ai-suggest
- POST /admin/bulk-price/ai-optimize
- POST /admin/bulk-price/ai-market

**Parameters:**

```php
{
  "ids": [1, 2, 3]
}
```

**Returns:**

```json
{
    "success": true,
    "suggestion|optimization|analysis": "AI generated text..."
}
```

### 10. GET /admin/bulk-price/export

**Purpose:** Export prices to CSV  
**Parameters:** Same as get-products  
**Returns:** File download

## Frontend Implementation

### JavaScript Architecture

```javascript
// Global State
let allProducts = []; // Current page products
let selectedProducts = []; // User selected products
let currentUpdateType = "fixed";
let currentPriceField = "base_price";
let tierPrices = []; // Staged tier prices

// Main Functions
-loadProducts() - // Fetch and render products
    renderProducts() - // Update table HTML
    updateSelectedProducts() - // Track selections
    updateExpectedPrices() - // Live preview calculation
    submitPriceUpdate(); // Send update request
```

### Key Features

#### 1. Live Preview System

```javascript
function updateExpectedPrices() {
    // Calculate new price based on:
    // - Current price
    // - Update type (fixed/percentage/formula)
    // - Direction (increase/decrease)
    // Display in price-comparison format:
    // OLD_PRICE ↑/↓ NEW_PRICE
}
```

#### 2. Price Calculation Logic

```javascript
// Fixed
newPrice = fixedValue;

// Percentage Increase
newPrice = currentPrice * (1 + percentage / 100);

// Percentage Decrease
newPrice = currentPrice * (1 - percentage / 100);

// Add Amount
newPrice = currentPrice + amount;

// Subtract Amount
newPrice = Math.max(0, currentPrice - amount);
```

#### 3. Discount Validation

```javascript
// Client-side
if (discount_price >= base_price) {
  // Show warning
  // Don't apply
}

// Server-side (Controller)
if ($product->discount_price >= $product->base_price) {
  $product->discount_price = null;
}
```

## Backend Implementation

### Controller Methods

#### updatePrices() - Core Logic

```php
foreach ($ids as $id) {
    $product = Product::find($id);

    // Get current price (base or discount)
    $currentPrice = $priceField === 'base_price'
        ? $product->base_price
        : ($product->discount_price ?? $product->base_price);

    // Calculate new price
    switch ($updateType) {
        case 'fixed':
            $newPrice = $fixedPrice;
            break;

        case 'percentage':
            if ($direction === 'increase') {
                $newPrice = $currentPrice * (1 + $percentage/100);
            } else {
                $newPrice = $currentPrice * (1 - $percentage/100);
            }
            break;

        case 'formula':
            if ($formulaType === 'increase') {
                $newPrice = $currentPrice + $value;
            } else {
                $newPrice = max(0, $currentPrice - $value);
            }
            break;
    }

    // Update and validate
    $product->{$priceField} = max(0, $newPrice);

    // Discount validation
    if ($priceField === 'discount_price'
        && $product->discount_price >= $product->base_price) {
        $product->discount_price = null;
    }

    $product->save();
}
```

#### applyDiscount() - Discount Logic

```php
foreach ($ids as $id) {
    $product = Product::find($id);

    switch ($discountType) {
        case 'percentage':
            // % off base price
            $product->discount_price =
                $product->base_price * (1 - $discountValue/100);
            break;

        case 'fixed':
            // Fixed amount off
            $product->discount_price =
                max(0, $product->base_price - $discountValue);
            break;

        case 'absolute':
            // Set specific price
            $product->discount_price = $discountValue;
            break;
    }

    // Validate
    if ($product->discount_price >= $product->base_price) {
        $product->discount_price = null;
    } else {
        $product->save();
    }
}
```

#### applyTiers() - Tier Pricing

```php
foreach ($ids as $productId) {
    $product = Product::find($productId);

    // Clear existing tiers
    $product->prices()->delete();

    // Add new tiers
    foreach ($tiers as $tier) {
        $product->prices()->create([
            'min_quantity' => $tier['min_quantity'],
            'max_quantity' => $tier['max_quantity'] ?? null,
            'price' => $tier['price'],
        ]);
    }
}
```

### AI Integration

```php
use App\Services\AI\AIService;

public function aiSuggest(Request $request) {
    // Gather product data
    $products = Product::with('category')
        ->whereIn('id', $request->ids)
        ->get();

    $productData = $products->map(function ($product) {
        return [
            'name' => $product->name,
            'category' => $product->category->name,
            'current_price' => $product->base_price,
            'stock' => $product->stock_quantity,
        ];
    });

    // Create prompt
    $prompt = "Analyze these products and suggest pricing:\n\n"
            . json_encode($productData, JSON_PRETTY_PRINT)
            . "\n\nProvide recommendations...";

    // Call AI service
    $aiService = new AIService();
    $result = $aiService->generate($prompt);

    return response()->json([
        'success' => $result['success'],
        'suggestion' => $result['content'],
    ]);
}
```

### Export Implementation

```php
public function exportPrices(Request $request) {
    // Apply filters
    $query = Product::with(['category', 'prices']);
    // ... apply same filters as getProducts()

    $products = $query->get();

    // Prepare CSV data
    $csvData = [];
    $csvData[] = ['ID', 'Name', 'SKU', ...]; // Headers

    foreach ($products as $product) {
        $tierPrices = $product->prices
            ->map(fn($p) => "{$p->min_quantity}-{$p->max_quantity}: {$p->price}")
            ->implode('; ');

        $csvData[] = [
            $product->id,
            $product->name,
            $product->sku,
            // ...
            $tierPrices ?: 'None',
        ];
    }

    // Write to file
    $file = fopen($filePath, 'w');
    foreach ($csvData as $row) {
        fputcsv($file, $row);
    }
    fclose($file);

    return response()->download($filePath)
        ->deleteFileAfterSend();
}
```

## Security Considerations

### 1. Authentication

```php
// In routes/web.php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('bulk-price')->...
});
```

### 2. Authorization

```php
// Check if user has permission
if (!auth()->user()->can('manage-products')) {
    abort(403);
}
```

### 3. Validation

```php
$request->validate([
    'ids' => 'required|array|min:1',
    'ids.*' => 'exists:products,id',  // Verify products exist
    'price_field' => 'required|in:base_price,discount_price',
    // ... more validation
]);
```

### 4. SQL Injection Prevention

```php
// Using Eloquent (parameterized queries)
Product::whereIn('id', $request->ids)->update([...]);

// Not vulnerable to SQL injection
```

### 5. CSRF Protection

```javascript
// In JavaScript
headers: {
    'X-CSRF-TOKEN': '{{ csrf_token() }}'
}
```

## Performance Optimization

### 1. Batch Updates

```php
// Instead of saving each product individually
// Use batch update where possible
Product::whereIn('id', $ids)->update(['discount_price' => null]);
```

### 2. Eager Loading

```php
// Load relationships in one query
$products = Product::with(['category', 'unit', 'prices'])->get();
```

### 3. Pagination

```php
// Don't load all products at once
$products = $query->paginate(50);
```

### 4. Database Transactions

```php
DB::beginTransaction();
try {
    // Multiple operations
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
}
```

### 5. Frontend Debouncing

```javascript
// Don't send request on every keystroke
document
    .getElementById("searchInput")
    .addEventListener("keyup", debounce(loadProducts, 500));
```

## Error Handling

### Backend

```php
try {
    // Operations
    return response()->json([
        'success' => true,
        'message' => 'Success message'
    ]);
} catch (\Exception $e) {
    Log::error('Error context:', ['error' => $e->getMessage()]);
    return response()->json([
        'success' => false,
        'message' => 'User-friendly error message'
    ], 500);
}
```

### Frontend

```javascript
fetch(url, options)
    .then((r) => r.json())
    .then((data) => {
        if (data.success) {
            Swal.fire("Success", data.message, "success");
        } else {
            Swal.fire("Error", data.message, "error");
        }
    })
    .catch((error) => {
        console.error("Error:", error);
        Swal.fire("Error", "Network or server error", "error");
    });
```

## Testing Guidelines

### Unit Tests

```php
// Test price calculation logic
public function test_percentage_increase_calculation()
{
    $product = Product::factory()->create(['base_price' => 100]);

    // Apply 10% increase
    $response = $this->post('/admin/bulk-price/update-prices', [
        'update_type' => 'percentage',
        'percentage' => 10,
        'percentage_direction' => 'increase',
        'price_field' => 'base_price',
        'ids' => [$product->id],
    ]);

    $product->refresh();
    $this->assertEquals(110, $product->base_price);
}
```

### Feature Tests

```php
public function test_bulk_discount_application()
{
    $products = Product::factory()->count(5)->create();

    $response = $this->post('/admin/bulk-price/apply-discount', [
        'ids' => $products->pluck('id')->toArray(),
        'discount_type' => 'percentage',
        'discount_value' => 20,
    ]);

    $response->assertJson(['success' => true]);

    // Verify discounts applied
    $products->each(function ($product) {
        $product->refresh();
        $this->assertNotNull($product->discount_price);
    });
}
```

## Deployment Checklist

- [ ] Run migrations (product_prices table exists)
- [ ] Configure AI service in .env
- [ ] Set up storage/app/exports directory (writable)
- [ ] Clear route cache: `php artisan route:clear`
- [ ] Clear view cache: `php artisan view:clear`
- [ ] Test with staging data first
- [ ] Backup database before production use
- [ ] Monitor logs after deployment
- [ ] Test all features in production environment

## Monitoring

### What to Monitor

1. API response times
2. AI service availability
3. Export file generation
4. Database transaction failures
5. User errors (validation failures)

### Logging

```php
// In controller methods
Log::info('Bulk price update', [
    'user_id' => auth()->id(),
    'product_count' => count($ids),
    'update_type' => $updateType,
]);
```

## Future Enhancements

### Potential Features

1. **Schedule price updates** - Set future price changes
2. **Price history** - Track all price changes
3. **Undo functionality** - Rollback last operation
4. **Import from CSV** - Bulk import prices
5. **Price rules** - Automated pricing rules
6. **A/B testing** - Test different prices
7. **Competitor tracking** - Auto-monitor competitor prices
8. **Dynamic pricing** - AI-based real-time pricing

---

**Version:** 2.0  
**Last Updated:** March 2026  
**Maintained By:** Development Team
