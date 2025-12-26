# VAT & AIT Management System Documentation

## Overview

This system provides comprehensive management of Value Added Tax (VAT) and Advance Income Tax (AIT) for your e-commerce store. It allows administrators to:

-   Set global VAT and AIT percentages
-   Configure whether taxes are included in displayed prices or added at checkout
-   Override tax settings on a per-product basis
-   Exempt specific products or categories from taxes
-   Schedule tax rate changes for future implementation
-   Track historical changes to tax settings
-   Generate reports on tax configuration

## Features

### 1. Global Tax Settings

Configure default VAT and AIT percentages that apply to all products.

**Located at:** Admin Dashboard → VAT & AIT → Global Settings

**Configuration Options:**

-   **VAT Enabled:** Toggle VAT on/off globally
-   **Default VAT Percentage:** Set the standard VAT rate (e.g., 15%)
-   **VAT Included in Price:** Choose if VAT is part of the displayed price
-   **AIT Enabled:** Toggle AIT on/off globally
-   **Default AIT Percentage:** Set the standard AIT rate (e.g., 2%)
-   **AIT Included in Price:** Choose if AIT is part of the displayed price
-   **AIT Exempt Categories:** Specify categories exempt from AIT (comma-separated IDs)

### 2. Product-Level Tax Overrides

Set custom tax rates for individual products.

**Located at:** Admin Dashboard → VAT & AIT → Product Overrides

**Per-Product Configuration:**

-   Override VAT with a custom percentage
-   Override AIT with a custom percentage
-   Exempt products from VAT entirely
-   Exempt products from AIT entirely
-   Set effective dates for overrides (current or future)
-   Document the reason for custom rates (audit trail)

### 3. Tax Calculation Services

The `TaxCalculator` service handles all tax calculations automatically.

**Key Methods:**

```php
use App\Services\TaxCalculator;

// Calculate VAT for a product
$vat = TaxCalculator::calculateVat($product, $price);
// Returns: ['vat_amount' => float, 'vat_percentage' => float, 'included' => bool]

// Calculate AIT for a product
$ait = TaxCalculator::calculateAit($product, $price);
// Returns: ['ait_amount' => float, 'ait_percentage' => float, 'included' => bool]

// Calculate both VAT and AIT
$taxes = TaxCalculator::calculateTaxes($product, $basePrice, $quantity);

// Get formatted price breakdown
$breakdown = TaxCalculator::getPriceBreakdown($product, $price);

// Get summary for multiple items (useful for orders/carts)
$summary = TaxCalculator::getSummaryForItems($items);
```

### 4. Helper Functions

Quick access functions for common tax operations:

```php
// Get VAT percentage for a product
$vatPercent = getProductVatPercentage($product);

// Get AIT percentage for a product
$aitPercent = getProductAitPercentage($product);

// Check if VAT is included
$isIncluded = isProductVatIncluded($product);

// Calculate taxes
$taxes = calculateProductTaxes($product, $basePrice, $quantity);

// Get price breakdown
$breakdown = getProductPriceBreakdown($product, $basePrice);

// Format amounts for display
$formatted = formatTaxAmount(150.50); // Output: ৳150.50
$percent = formatTaxPercentage(15.5); // Output: 15.50%

// Get current settings
$settings = getCurrentVatAitSettings();
```

### 5. Models & Database

#### VatAitSetting Model

Stores global VAT/AIT configuration.

```php
$settings = VatAitSetting::current(); // Get active settings

// Properties:
$settings->default_vat_percentage;      // e.g., 15.00
$settings->vat_enabled;                 // true/false
$settings->vat_included_in_price;       // true/false
$settings->default_ait_percentage;      // e.g., 2.00
$settings->ait_enabled;                 // true/false
$settings->ait_included_in_price;       // true/false
$settings->ait_exempt_categories;       // "1,2,5"
$settings->effective_from;              // DateTime
```

#### ProductTaxOverride Model

Stores product-specific tax overrides.

```php
$override = $product->taxOverride;

// Properties:
$override->override_vat;                // true/false
$override->vat_percentage;              // Custom VAT %
$override->vat_exempt;                  // true/false
$override->override_ait;                // true/false
$override->ait_percentage;              // Custom AIT %
$override->ait_exempt;                  // true/false
$override->effective_from;              // DateTime
$override->effective_until;             // DateTime (nullable)
$override->isActive();                  // Check if override applies
```

#### Product Model (Extended)

```php
$product = Product::find(1);

// Get effective tax percentages
$vat = $product->getEffectiveVatPercentage();      // Considers overrides
$ait = $product->getEffectiveAitPercentage();

// Check tax inclusion
$vatIncluded = $product->isVatIncluded();
$aitIncluded = $product->isAitIncluded();

// Access override
$override = $product->taxOverride;
```

## Usage Examples

### Example 1: Display Product Price with Tax Info

```blade
@php
    use App\Services\TaxCalculator;
    $breakdown = TaxCalculator::getPriceBreakdown($product, $product->final_price);
@endphp

<div class="price-info">
    <h3>৳{{ $breakdown['final_price_formatted'] }}</h3>
    <p>Base Price: {{ $breakdown['base_price_formatted'] }}</p>

    @if($breakdown['vat_percentage'] > 0)
        <p>
            VAT ({{ $breakdown['vat_percentage'] }}%):
            {{ $breakdown['vat_amount_formatted'] }}
            {{ $breakdown['vat_included'] ? '(included)' : '(will be added)' }}
        </p>
    @endif

    @if($breakdown['ait_percentage'] > 0)
        <p>
            AIT ({{ $breakdown['ait_percentage'] }}%):
            {{ $breakdown['ait_amount_formatted'] }}
            {{ $breakdown['ait_included'] ? '(included)' : '(will be added)' }}
        </p>
    @endif
</div>
```

### Example 2: Calculate Cart Total with Taxes

```php
use App\Services\TaxCalculator;

$cartItems = [
    ['product' => $product1, 'quantity' => 2, 'price' => 1000],
    ['product' => $product2, 'quantity' => 1, 'price' => 5000],
];

$summary = TaxCalculator::getSummaryForItems($cartItems);

$total = $summary['final_price'];        // Total including taxes
$baseTotal = $summary['base_price'];    // Before taxes
$vatTotal = $summary['vat_amount'];     // Total VAT
$aitTotal = $summary['ait_amount'];     // Total AIT
```

### Example 3: Override VAT for a Product

```php
use App\Models\ProductTaxOverride;

$product = Product::find(1);

// Create override
ProductTaxOverride::create([
    'product_id' => $product->id,
    'override_vat' => true,
    'vat_percentage' => 10.00,
    'vat_included_in_price' => true,
    'reason' => 'Government exemption for agricultural products',
]);
```

### Example 4: Exempt Category from AIT

```php
use App\Models\VatAitSetting;

$settings = VatAitSetting::current();

// Add category 5 to AIT exemptions
$categories = $settings->getExemptCategoriesArray();
$categories[] = 5;
$settings->ait_exempt_categories = implode(',', array_unique($categories));
$settings->save();
```

## Admin Routes

All routes are prefixed with `/admin/vat-ait` and require admin authentication.

```
GET     /                           # View settings (index)
POST    /update-settings            # Update global settings
GET     /products                   # List products for tax config
GET     /products/{product}/edit    # Edit product tax
POST    /products/{product}/update  # Update product tax
POST    /products/{product}/remove  # Remove product override
POST    /products/bulk-update       # Bulk update multiple products
GET     /products/search            # Search/filter products
GET     /products/export            # Export as CSV
GET     /history                    # View settings history
GET     /report                     # View report & statistics
```

## Tax Calculation Logic

### When VAT is Included in Price

```
Display Price = ৳1000 (includes VAT)
VAT % = 15%

VAT Amount = (1000 × 15) / (100 + 15) = ৳130.43
Base Price = 1000 - 130.43 = ৳869.57
```

### When VAT is Added at Checkout

```
Display Price = ৳1000 (before VAT)
VAT % = 15%

VAT Amount = 1000 × 0.15 = ৳150
Total Price = 1000 + 150 = ৳1150
```

### When Both VAT and AIT Apply

```
Base Price = ৳1000
VAT = 15% (included)
AIT = 2% (not included)

Step 1: Calculate VAT (included in 1000)
VAT Amount = (1000 × 15) / 115 = ৳130.43

Step 2: Calculate AIT (on base after VAT)
Base without VAT = 1000 - 130.43 = ৳869.57
AIT Amount = 869.57 × 0.02 = ৳17.39

Final Total = ৳1017.39 (with VAT included + AIT added)
```

## Scheduled Tax Changes

You can schedule VAT/AIT changes to take effect in the future:

1. Navigate to Admin → VAT & AIT → Global Settings
2. Set the desired tax percentages
3. Set "Effective From" to a future date/time
4. Save

The new settings will automatically take effect at the scheduled time.

## Best Practices

1. **Document Changes:** Always add notes explaining why you changed tax rates
2. **Test Before Implementation:** Use the report to verify tax configurations
3. **Product Overrides:** Only use product-level overrides for genuinely exempt products
4. **Category Exemptions:** Use category-level AIT exemptions for product categories, not individual products
5. **Effective Dates:** Plan tax changes in advance using the scheduled implementation feature
6. **Audit Trail:** Check the history regularly to ensure proper compliance

## Common Scenarios

### Scenario 1: Product is Essential Commodity (VAT Exempt)

1. Go to Admin → VAT & AIT → Product Overrides
2. Find the product
3. Check "Exempt this product from VAT"
4. Enter reason: "Essential commodity - Government exemption"
5. Save

### Scenario 2: Export Product (AIT Exempt)

1. Go to Admin → VAT & AIT → Product Overrides
2. Find the product
3. Check "Exempt this product from AIT"
4. Enter reason: "Export product"
5. Save

### Scenario 3: Custom Tax Rate for Specific Product

1. Go to Admin → VAT & AIT → Product Overrides
2. Find the product
3. Check "Override VAT for this product"
4. Enter custom percentage (e.g., 10%)
5. Check "Override AIT for this product"
6. Enter custom percentage (e.g., 1%)
7. Add notes explaining the business reason
8. Save

### Scenario 4: Government Changes Tax Rate

1. Go to Admin → VAT & AIT → Global Settings
2. Change "Default VAT Percentage" to new rate
3. Set "Effective From" to implementation date
4. Add note: "Updated as per Government Notification No. XXX dated XXX"
5. Save

The new rate takes effect automatically on the specified date.

## Troubleshooting

**Q: Why is the product showing global tax instead of custom?**
A: Check if the override's "Effective From" date is in the past. Also verify that at least one override option (VAT override, AIT override, or exemption) is enabled.

**Q: My category AIT exemption isn't working**
A: Verify the category ID is correct in the "AIT Exempt Categories" field. Category IDs are comma-separated.

**Q: Tax calculations seem wrong**
A: Check if the "Included in Price" setting is correct. Tax calculations differ based on whether taxes are included or added.

**Q: How do I revert a scheduled tax change?**
A: Delete the future-dated setting record from the database or set a new current setting with the correct rates.

## API Integration

If you're integrating this with external systems:

```php
// Get current tax configuration
$settings = VatAitSetting::current();
$json = json_encode([
    'vat_percentage' => $settings->default_vat_percentage,
    'vat_enabled' => $settings->vat_enabled,
    'vat_included' => $settings->vat_included_in_price,
    'ait_percentage' => $settings->default_ait_percentage,
    'ait_enabled' => $settings->ait_enabled,
    'ait_included' => $settings->ait_included_in_price,
]);

// Get tax breakdown for an order
use App\Services\TaxCalculator;
$breakdown = TaxCalculator::getPriceBreakdown($product, $price);
```

## Database Schema

### vat_ait_settings

-   `id`: Primary key
-   `default_vat_percentage`: Decimal(8,2)
-   `vat_enabled`: Boolean
-   `vat_included_in_price`: Boolean
-   `default_ait_percentage`: Decimal(8,2)
-   `ait_enabled`: Boolean
-   `ait_included_in_price`: Boolean
-   `ait_exempt_categories`: Text (comma-separated category IDs)
-   `notes`: Text
-   `effective_from`: Timestamp
-   `created_at`, `updated_at`: Timestamps
-   `deleted_at`: Soft delete

### product_tax_overrides

-   `id`: Primary key
-   `product_id`: Foreign key to products
-   `override_vat`: Boolean
-   `vat_percentage`: Decimal(8,2)
-   `vat_included_in_price`: Boolean (nullable)
-   `override_ait`: Boolean
-   `ait_percentage`: Decimal(8,2)
-   `ait_included_in_price`: Boolean (nullable)
-   `vat_exempt`: Boolean
-   `ait_exempt`: Boolean
-   `reason`: Text
-   `effective_from`: Timestamp
-   `effective_until`: Timestamp (nullable)
-   `created_at`, `updated_at`: Timestamps
-   `deleted_at`: Soft delete
