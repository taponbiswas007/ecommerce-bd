# VAT & AIT System - Quick Reference

## Access the Admin Interface

```
URL: https://yoursite.com/admin/vat-ait
Requires: Admin login
```

## Quick Navigation

| Feature             | URL                                 | Purpose                         |
| ------------------- | ----------------------------------- | ------------------------------- |
| **Global Settings** | `/admin/vat-ait`                    | Configure default VAT/AIT rates |
| **Product Taxes**   | `/admin/vat-ait/products`           | Manage product-level overrides  |
| **Edit Product**    | `/admin/vat-ait/products/{id}/edit` | Set custom tax for a product    |
| **History**         | `/admin/vat-ait/history`            | View all settings changes       |
| **Report**          | `/admin/vat-ait/report`             | View statistics & summary       |
| **Export**          | `/admin/vat-ait/products/export`    | Download taxes as CSV           |

## Configuration in 5 Minutes

### 1. Set Global VAT

-   Go to `/admin/vat-ait`
-   Enter VAT percentage (e.g., 15)
-   Choose: Included in price OR Added at checkout
-   Click Save

### 2. Set Global AIT

-   Enter AIT percentage (e.g., 2)
-   Choose: Included in price OR Added at checkout
-   (Optional) Specify exempt categories
-   Click Save

### 3. Configure Product Override (If Needed)

-   Go to `/admin/vat-ait/products`
-   Click edit for a product
-   Override VAT or AIT percentage
-   OR mark as exempt
-   Click Save

### 4. View Report

-   Go to `/admin/vat-ait/report`
-   See statistics and summary
-   Done!

## Common Settings

### Bangladesh Standard

```
VAT: 15% (included in price)
AIT: 2% (added at checkout)
```

### Bangladesh with Both Included

```
VAT: 15% (included in price)
AIT: 2% (included in price)
```

### Bangladesh with Both Added

```
VAT: 15% (added at checkout)
AIT: 2% (added at checkout)
```

## Using in Blade Templates

### Show Product Price with Taxes

```blade
@php
    $breakdown = TaxCalculator::getPriceBreakdown($product, $product->final_price);
@endphp

<div>
    Price: {{ $breakdown['base_price_formatted'] }}
    @if($breakdown['vat_percentage'] > 0)
        <p>VAT ({{ $breakdown['vat_percentage'] }}%): {{ $breakdown['vat_amount_formatted'] }}</p>
    @endif
    @if($breakdown['ait_percentage'] > 0)
        <p>AIT ({{ $breakdown['ait_percentage'] }}%): {{ $breakdown['ait_amount_formatted'] }}</p>
    @endif
    Total: {{ $breakdown['final_price_formatted'] }}
</div>
```

## Using Helper Functions

```php
// Get percentages
$vat = getProductVatPercentage($product);      // e.g., 15
$ait = getProductAitPercentage($product);      // e.g., 2

// Check inclusion
$vatIncluded = isProductVatIncluded($product); // true/false
$aitIncluded = isProductAitIncluded($product); // true/false

// Calculate
$taxes = calculateProductTaxes($product, 1000, 1); // Returns array
$breakdown = getProductPriceBreakdown($product, 1000);

// Format
echo formatTaxAmount(150);        // ৳150.00
echo formatTaxPercentage(15.5);   // 15.50%

// Get settings
$settings = getCurrentVatAitSettings();
```

## Using TaxCalculator Service

```php
use App\Services\TaxCalculator;

// Calculate VAT
$vat = TaxCalculator::calculateVat($product, $price);
// Returns: ['vat_amount' => 150, 'vat_percentage' => 15, 'included' => true]

// Calculate AIT
$ait = TaxCalculator::calculateAit($product, $price);
// Returns: ['ait_amount' => 20, 'ait_percentage' => 2, 'included' => false]

// Calculate both
$taxes = TaxCalculator::calculateTaxes($product, $price, $quantity);

// Get breakdown
$breakdown = TaxCalculator::getPriceBreakdown($product, $price);

// Cart total
$items = [
    ['product' => $p1, 'quantity' => 2, 'price' => 1000],
    ['product' => $p2, 'quantity' => 1, 'price' => 5000],
];
$summary = TaxCalculator::getSummaryForItems($items);
// Returns: ['base_price', 'vat_amount', 'ait_amount', 'final_price']
```

## Admin Routes API

### Global Settings

```php
GET  /admin/vat-ait                  // View + update form
POST /admin/vat-ait/update-settings  // Submit updates
```

### Product Management

```php
GET  /admin/vat-ait/products              // List all
GET  /admin/vat-ait/products/{id}/edit    // Edit form
POST /admin/vat-ait/products/{id}/update  // Submit
POST /admin/vat-ait/products/{id}/remove  // Delete override
POST /admin/vat-ait/products/bulk-update  // Bulk changes
GET  /admin/vat-ait/products/search       // Search/filter
GET  /admin/vat-ait/products/export       // CSV download
```

### Reports & History

```php
GET /admin/vat-ait/history  // Settings timeline
GET /admin/vat-ait/report   // Statistics
```

## Model Methods

### Product Model

```php
$product->getEffectiveVatPercentage();  // Get VAT %
$product->getEffectiveAitPercentage();  // Get AIT %
$product->isVatIncluded();              // Is VAT included?
$product->isAitIncluded();              // Is AIT included?
$product->taxOverride;                  // Get override object
```

### VatAitSetting Model

```php
VatAitSetting::current();                    // Get active settings
$settings->isCategoryAitExempt($categoryId);  // Check exemption
$settings->getExemptCategoriesArray();       // Get exempt categories
```

### ProductTaxOverride Model

```php
$override->isActive();                  // Is override active?
$override->getEffectiveVatPercentage(); // Get VAT %
$override->getEffectiveAitPercentage(); // Get AIT %
$override->getVatIncludedInPrice();     // VAT handling
$override->getAitIncludedInPrice();     // AIT handling
```

## Database Schema Quick Reference

### vat_ait_settings

| Column                 | Type         | Notes          |
| ---------------------- | ------------ | -------------- |
| default_vat_percentage | decimal(8,2) | e.g., 15.00    |
| vat_enabled            | boolean      | Enable/disable |
| vat_included_in_price  | boolean      | Included?      |
| default_ait_percentage | decimal(8,2) | e.g., 2.00     |
| ait_enabled            | boolean      | Enable/disable |
| ait_included_in_price  | boolean      | Included?      |
| ait_exempt_categories  | text         | "1,2,5"        |
| effective_from         | timestamp    | When active    |

### product_tax_overrides

| Column          | Type         | Notes       |
| --------------- | ------------ | ----------- |
| product_id      | bigint       | Foreign key |
| override_vat    | boolean      | Override?   |
| vat_percentage  | decimal(8,2) | Custom %    |
| override_ait    | boolean      | Override?   |
| ait_percentage  | decimal(8,2) | Custom %    |
| vat_exempt      | boolean      | Exempt?     |
| ait_exempt      | boolean      | Exempt?     |
| effective_from  | timestamp    | Start date  |
| effective_until | timestamp    | End date    |

## Troubleshooting

**Q: Tax info not showing on product page?**
A: Check that global settings are configured and saved.

**Q: Calculations seem wrong?**
A: Verify "Included in Price" vs "Added at Checkout" setting.

**Q: Product override not working?**
A: Check override "Effective From" date is in the past.

**Q: Category exemption not applied?**
A: Verify correct category ID in "AIT Exempt Categories".

**Q: Functions not available?**
A: Run `composer dump-autoload` and clear cache.

## Key Concepts

### VAT (Value Added Tax)

-   Tax on consumption at each stage
-   Usually included in displayed price in Bangladesh
-   Standard rate: 15%

### AIT (Advance Income Tax)

-   Withholding tax on purchases
-   Usually NOT included in displayed price
-   Standard rate: 0-5% depending on product

### Included in Price

-   Price shown = final price
-   Tax already calculated in amount shown

### Added at Checkout

-   Price shown = before tax
-   Tax calculated and added during checkout

## Files & Locations

| Component  | Location                                                 |
| ---------- | -------------------------------------------------------- |
| Models     | `app/Models/VatAitSetting.php`, `ProductTaxOverride.php` |
| Service    | `app/Services/TaxCalculator.php`                         |
| Helpers    | `app/Helpers/TaxHelper.php`                              |
| Controller | `app/Http/Controllers/Admin/VatAitController.php`        |
| Views      | `resources/views/admin/vat-ait/`                         |
| Migrations | `database/migrations/2025_12_26_*`                       |
| Routes     | `routes/web.php` (admin group)                           |
| Docs       | `VAT_AIT_*.md` files                                     |

## Next Steps

1. ✅ Run migrations: `php artisan migrate`
2. ✅ Configure global settings
3. ✅ Set product overrides if needed
4. ✅ Test on product page
5. ✅ Review report
6. ✅ Train staff

---

**Last Updated:** 2025-12-26  
**System Version:** 1.0  
**Status:** Production Ready
