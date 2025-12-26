# VAT & AIT System - Quick Setup Guide

## Installation & Setup Steps

### Step 1: Run Migrations

```bash
php artisan migrate
```

This will create two new tables:

-   `vat_ait_settings` - For global VAT/AIT configuration
-   `product_tax_overrides` - For product-specific tax overrides

### Step 2: Refresh Composer Autoload

Since we added helpers to composer.json, run:

```bash
composer dump-autoload
```

### Step 3: Access the Admin Panel

Navigate to your admin dashboard:

```
https://yoursite.com/admin/vat-ait
```

### Step 4: Configure Global Settings

1. Go to **VAT & AIT → Global Settings**
2. Set your default VAT percentage (e.g., 15%)
3. Choose if VAT is included in prices or added at checkout
4. Set your default AIT percentage (e.g., 2%)
5. Choose if AIT is included in prices or added at checkout
6. (Optional) Specify categories exempt from AIT
7. Click **Save Settings**

### Step 5: Configure Product Taxes (Optional)

To override tax settings for specific products:

1. Go to **VAT & AIT → Product Overrides**
2. Click the edit button (pencil icon) for a product
3. Configure custom VAT/AIT settings or exemptions
4. Add a reason for the override (for audit purposes)
5. Click **Save Tax Settings**

## Key Configuration Options

### VAT Settings

-   **Status:** Enable/disable VAT
-   **Percentage:** Default VAT rate (typically 15% in Bangladesh)
-   **Handling:**
    -   `Included in Price`: Price shown already includes VAT
    -   `Added at Checkout`: VAT calculated and added during checkout

### AIT Settings

-   **Status:** Enable/disable AIT
-   **Percentage:** Default AIT rate (typically 0-5% depending on product type)
-   **Handling:**
    -   `Included in Price`: Price shown already includes AIT
    -   `Added at Checkout`: AIT calculated and added during checkout
-   **Exempt Categories:** Specify which product categories are exempt from AIT

## Example Configurations

### Configuration A: VAT Included, AIT Added

```
VAT: 15% (included in displayed price)
AIT: 2% (added at checkout)

Display Price: ৳1000
- Contains ৳130.43 VAT
- Will add ৳17.39 AIT at checkout
```

### Configuration B: Both VAT and AIT Included

```
VAT: 15% (included in displayed price)
AIT: 2% (included in displayed price)

Display Price: ৳1000 (contains both VAT and AIT)
```

### Configuration C: Both Added at Checkout

```
VAT: 15% (added at checkout)
AIT: 2% (added at checkout)

Display Price: ৳1000 (before taxes)
Final: ৳1170 (after both taxes)
```

## Using in Your Code

### Blade Templates

Display tax information on product pages:

```blade
@php
    use App\Services\TaxCalculator;
    $breakdown = TaxCalculator::getPriceBreakdown($product, $product->final_price);
@endphp

<div>
    <p>Price: {{ $breakdown['base_price_formatted'] }}</p>
    @if($breakdown['vat_percentage'] > 0)
        <p>VAT ({{ $breakdown['vat_percentage'] }}%): {{ $breakdown['vat_amount_formatted'] }}</p>
    @endif
    @if($breakdown['ait_percentage'] > 0)
        <p>AIT ({{ $breakdown['ait_percentage'] }}%): {{ $breakdown['ait_amount_formatted'] }}</p>
    @endif
    <p>Total: {{ $breakdown['final_price_formatted'] }}</p>
</div>
```

### Controllers

Calculate taxes in your checkout or order controller:

```php
use App\Services\TaxCalculator;

$cartItems = [
    ['product' => $product, 'quantity' => 2, 'price' => 1000],
];

$summary = TaxCalculator::getSummaryForItems($cartItems);

// $summary contains:
// - base_price
// - vat_amount
// - ait_amount
// - final_price
```

### Helper Functions

Use convenience functions throughout your app:

```php
// In any view or controller
$vat = getProductVatPercentage($product);
$ait = getProductAitPercentage($product);
$breakdown = getProductPriceBreakdown($product, $price);
```

## Admin Routes Reference

```
GET  /admin/vat-ait                         - Main settings page
POST /admin/vat-ait/update-settings        - Update global settings
GET  /admin/vat-ait/products               - List products for tax config
GET  /admin/vat-ait/products/{id}/edit     - Edit product tax
POST /admin/vat-ait/products/{id}/update   - Save product tax
POST /admin/vat-ait/products/{id}/remove   - Remove product override
GET  /admin/vat-ait/history                - View settings history
GET  /admin/vat-ait/report                 - View statistics & report
GET  /admin/vat-ait/products/export        - Export as CSV
```

## Important Notes

1. **Database Backup:** Back up your database before running migrations
2. **Test First:** Test tax calculations on a staging site first
3. **Government Compliance:** Ensure your configuration complies with local tax regulations
4. **Historical Record:** The system maintains a complete history of all tax setting changes
5. **Product Overrides:** Only use product-level overrides when absolutely necessary
6. **Scheduled Changes:** You can schedule tax rate changes to take effect in the future

## Troubleshooting

**Issue: Tables not created after migration**

```bash
php artisan migrate:refresh  # To refresh (clears all data!)
# Or
php artisan migrate --step --force
```

**Issue: Helper functions not available**

```bash
composer dump-autoload
php artisan cache:clear
```

**Issue: Tax calculations seem incorrect**

-   Check if VAT/AIT is marked as "Included" or "Added"
-   Verify product doesn't have an override with different rates
-   Check "Effective From" date on product override

## Next Steps

1. Configure your global VAT/AIT settings
2. Test by viewing a product page - tax info should display
3. Verify calculations in admin report
4. Set up any product-specific overrides as needed
5. Consider integrating into invoice/order confirmations
6. Train staff on how to manage tax settings

## Support & Resources

-   **Admin Panel:** `/admin/vat-ait` - Full management interface
-   **Report:** View `/admin/vat-ait/report` for statistics
-   **History:** View `/admin/vat-ait/history` for all changes
-   **Documentation:** See `VAT_AIT_DOCUMENTATION.md` for detailed guide
