# Product Management - Quick Reference

## 🎯 Quick Commands

### View Bulk Price Page

- **Route:** `/admin/bulk-price`
- **Menu:** Products → Bulk Price Update

### Test Routes in Tinker

```bash
php artisan tinker

# Check if column exists
Schema::hasColumn('products', 'hide_from_frontend')

# Get product count
Product::count()

# Update all products (example)
Product::whereIn('id', [1,2,3])->update(['is_active' => true])
```

## 📋 API Endpoints (AJAX)

### Get Filtered Products

```
GET /admin/bulk-price/get-products?
  category_id=1&
  min_price=100&
  max_price=5000&
  status=1&
  search=product_name&
  page=1
```

### Update Prices

```
POST /admin/bulk-price/update-prices
Body: {
  "product_ids": [1, 2, 3],
  "update_type": "percentage|fixed|formula",
  "percentage": 10         // for percentage mode
  "price": 500            // for fixed mode
  "amount": 50            // for formula mode
  "formula_type": "add|subtract"  // for formula mode
}
```

### Toggle Visibility

```
POST /admin/bulk-price/toggle-visibility
Body: {
  "product_ids": [1, 2, 3],
  "hide": true|false
}
```

### Update Status

```
POST /admin/products/{id}/status
Body: {
  "status": 1|0  // 1 = active, 0 = inactive
}
```

### Bulk Actions

```
POST /admin/products/bulk-action
Body: {
  "action": "activate|deactivate|featured|unfeatured|delete",
  "ids": [1, 2, 3, ...]
}
```

## 🔐 Security Notes

1. All endpoints require admin authentication
2. All POST requests require CSRF token
3. Database transactions prevent partial updates
4. Input validation on all endpoints
5. Authorization checks on all operations

## 🐛 Debugging

### Enable Query Logging

```php
// In routes/admin.php or controller
\DB::listen(function($query) {
    \Log::info($query->sql, $query->bindings);
});
```

### Check Active Products in Frontend

```php
// Products shown to customers
Product::where('is_active', true)
        ->where('hide_from_frontend', false)
        ->get();
```

### Check Product Visibility

```php
// Admin can see all
Product::all();

// Customers see
Product::where('hide_from_frontend', false)->get();
```

## 📊 Database Schema

### Products Table

```sql
-- New column added
ALTER TABLE products ADD COLUMN hide_from_frontend BOOLEAN DEFAULT 0;

-- Useful queries
SELECT id, name, base_price, is_active, hide_from_frontend FROM products;

-- Find hidden products
SELECT * FROM products WHERE hide_from_frontend = 1;

-- Find inactive products
SELECT * FROM products WHERE is_active = 0;

-- Find both hidden and inactive
SELECT * FROM products WHERE hide_from_frontend = 1 OR is_active = 0;
```

## 🎨 UI Elements

### Bulk Price Update View

- **Sidebar filters:** Category, price range, status, search
- **Update modes:** Fixed price, Percentage, Formula
- **Live preview:** Price calculations shown before applying
- **Pagination:** 50 products per page

### Products List

- **Bulk actions:** Activate, Deactivate, Featured, Delete
- **Individual actions:** Edit, Manage images, Manage prices, Delete
- **Status toggle:** Real-time active/inactive switch
- **Search:** Real-time by name/SKU

## ✅ Daily Workflows

### Update 100 Product Prices by 10%

1. Go to Bulk Price Update
2. Search for products (optional filter)
3. Click "Select All"
4. Choose "Percentage" mode
5. Enter "10"
6. Click "Update Prices"
7. Confirm

Saves **~2 hours** vs manual updates!

### Hide Seasonal Products

1. Go to Bulk Price Update
2. Filter by category (e.g., "Christmas Items")
3. Select products
4. Toggle "Hidden from Frontend"
5. Products stay in stock/system but invisible to customers

### Hide Products Needing Price Adjustment

1. Identify products to update
2. Go to Bulk Price Update
3. Hide them from frontend
4. Update prices
5. Make active again when ready

## 📈 Performance Tips

1. Use filters to reduce product set before bulk operations
2. Pagination is automatic - process in batches if > 500 products
3. Off-peak times recommended for bulk operations
4. Check logs after bulk operations: `storage/logs/laravel.log`

## 🆘 Support

### If bulk update fails:

1. Check browser console for JavaScript errors
2. Check network tab for API responses
3. View Laravel logs: `tail storage/logs/laravel.log`
4. Verify CSRF token in form

### If products not showing:

1. Check `is_active` flag
2. Check `hide_from_frontend` flag
3. Check soft-deleted status (trash)
4. Verify category is active

### Reset to known state:

```php
# Reactivate all products
Product::update(['is_active' => true, 'hide_from_frontend' => false]);

# OR in tinker
Product::query()->update(['is_active' => true, 'hide_from_frontend' => false]);
```

## 📞 Key Contacts

- **Admin Controller:** `app/Http/Controllers/Admin/BulkProductPriceController.php`
- **Product Model:** `app/Models/Product.php`
- **Routes:** `routes/web.php` (lines with bulk-price)
- **Views:** `resources/views/admin/products/bulk-price-update.blade.php`

---

**Last Updated:** 2026-03-01  
**Version:** 1.0.0
