# Dropshipping Quick Reference

## Routes Summary

### Admin Routes

- `/admin/dropshipping/settings` - Configuration & API setup
- `/admin/dropshipping/products` - Product management
- `/admin/dropshipping/products/create` - Import new product
- `/admin/dropshipping/orders` - Order management
- `/admin/dropshipping/orders/create` - Submit orders to CJ

### API Endpoints Used in Controllers

- `POST /admin/dropshipping/products/search` - Search CJ products
- `POST /admin/dropshipping/products/import` - Import product
- `POST /admin/dropshipping/products/bulk-update` - Bulk update products
- `POST /admin/dropshipping/orders/submit` - Submit order to CJ
- `POST /admin/dropshipping/orders/bulk-sync` - Sync multiple orders
- `GET /admin/dropshipping/orders/{id}/sync-status` - Sync single order

## Database Tables

```
dropshipping_products
├── id (PK)
├── cj_product_id (UNIQUE)
├── name
├── unit_price (cost from CJ)
├── selling_price (your price)
├── profit_margin (calculated)
├── stock
├── is_active
├── image_url
└── timestamps

dropshipping_orders
├── id (PK)
├── order_id (FK → orders)
├── cj_order_number (UNIQUE)
├── cj_order_status
├── cost_price
├── selling_price
├── profit
├── submitted_to_cj_at
├── confirmed_by_cj_at
├── shipped_by_cj_at
├── delivered_at
└── timestamps

dropshipping_order_items
├── id (PK)
├── dropshipping_order_id (FK)
├── dropshipping_product_id (FK)
├── quantity
├── unit_cost_price
├── unit_selling_price
└── timestamps

dropshipping_settings
├── key (UNIQUE)
└── value

dropshipping_api_logs
├── endpoint
├── method (GET/POST/PUT/DELETE)
├── request_data (JSON)
├── response_data (JSON)
├── success
└── error_message
```

## Models & Relationships

```
Order
  ├── dropshippingOrder(): hasOne(DropshippingOrder)
  └── items()

DropshippingOrder
  ├── order(): belongsTo(Order)
  ├── items(): hasMany(DropshippingOrderItem)
  └── user() [via order]

DropshippingProduct
  ├── orderItems(): hasMany(DropshippingOrderItem)
  └── orders(): hasManyThrough

DropshippingOrderItem
  ├── dropshippingOrder()
  └── product()

DropshippingSetting
  └── Static methods: getSetting(), setSetting()

DropshippingApiLog
  └── Auditing & debugging logs
```

## Controllers Summary

### DropshippingProductController

- `index()` - List with filters
- `create()` - Import form
- `search()` - API: Search CJ
- `import()` - API: Import product
- `show()` - Product details
- `edit()` - Edit form
- `update()` - Update product
- `bulkUpdate()` - API: Bulk operations
- `destroy()` - Delete product

### DropshippingOrderController

- `index()` - List with filters & stats
- `create()` - Select orders to submit
- `submit()` - Submit order to CJ
- `show()` - Order details
- `syncStatus()` - Sync single order
- `tracking()` - API: Get tracking
- `cancel()` - Cancel order on CJ
- `bulkSync()` - API: Sync multiple

### DropshippingSettingController

- `index()` - Settings form
- `update()` - Save settings
- `testConnection()` - API: Test API

## Service Methods

### CJDropshippingService

- `isConfigured()` - Check if API ready
- `searchProducts()` - Search on CJ
- `getProductDetails()` - Full product info
- `getProductPrices()` - Price list
- `getProductInventory()` - Stock info
- `createOrder()` - Submit order to CJ
- `getOrderStatus()` - Check order status
- `getOrderTracking()` - Get tracking info
- `cancelOrder()` - Cancel order
- `syncProduct()` - Import product

## Key Files Location

```
Database Migrations:
  └── database/migrations/2026_02_17_000*_create_dropshipping_*

Models:
  ├── app/Models/DropshippingProduct.php
  ├── app/Models/DropshippingOrder.php
  ├── app/Models/DropshippingOrderItem.php
  ├── app/Models/DropshippingSetting.php
  └── app/Models/DropshippingApiLog.php

Services:
  └── app/Services/CJDropshippingService.php

Controllers:
  ├── app/Http/Controllers/Admin/DropshippingProductController.php
  ├── app/Http/Controllers/Admin/DropshippingOrderController.php
  └── app/Http/Controllers/Admin/DropshippingSettingController.php

Views:
  └── resources/views/admin/dropshipping/
      ├── products/
      │   ├── index.blade.php
      │   ├── create.blade.php
      │   ├── edit.blade.php
      │   └── show.blade.php
      ├── orders/
      │   ├── index.blade.php
      │   ├── create.blade.php
      │   └── show.blade.php
      └── settings.blade.php

Routes:
  └── routes/web.php (admin.dropshipping.* routes)
```

## Common Tasks

### Add Menu Item to Admin Dashboard

In your admin layout (sidebar), add:

```blade
<li>
    <a href="{{ route('admin.dropshipping.settings.index') }}">
        <i class="fas fa-box"></i> Dropshipping
    </a>
    <ul>
        <li><a href="{{ route('admin.dropshipping.products.index') }}">Products</a></li>
        <li><a href="{{ route('admin.dropshipping.orders.index') }}">Orders</a></li>
        <li><a href="{{ route('admin.dropshipping.settings.index') }}">Settings</a></li>
    </ul>
</li>
```

### Check If Product is Dropshipping

```php
// In OrderItem or when displaying
$isDrop shipping = $product instanceof DropshippingProduct;
// OR check if it has CJ ID
$isDropshipping = !empty($product->cj_product_id);
```

### Calculate Profit for Order

```php
$dropshippingOrder = $order->dropshippingOrder;
$profit = $dropshippingOrder->profit;
$marginPercent = ($profit / $dropshippingOrder->selling_price) * 100;
```

### Query Dropshipping Orders by Status

```php
// Pending orders
$pending = DropshippingOrder::pending()->get();

// Confirmed orders
$confirmed = DropshippingOrder::confirmed()->get();

// Shipped orders
$shipped = DropshippingOrder::shipped()->get();

// Total profit
$totalProfit = DropshippingOrder::sum('profit');
```

## Status Codes

### CJ Order Status

- `pending` - Awaiting CJ confirmation
- `confirmed` - Confirmed by CJ
- `shipped` - Shipped by CJ
- `delivered` - Delivered to customer
- `cancelled` - Cancelled

### Corresponding Main Order Status

Dropshipping order status syncs to main order:

- DS pending → Order processing
- DS confirmed → Order processing
- DS shipped → Order shipped
- DS delivered → Order delivered

## Performance Tips

1. **Eager Load Relations**:

    ```php
    DropshippingOrder::with(['order.user', 'items.product'])->get()
    ```

2. **Index Database Columns**:
    - `cj_product_id`
    - `cj_order_number`
    - `cj_order_status`
    - `created_at`

3. **Cache Settings**:

    ```php
    // Cache API settings for 1 hour
    Cache::remember('ds_settings', 3600, fn() =>
        DropshippingSetting::pluck('value', 'key')->toArray()
    );
    ```

4. **Batch Operations**:
    - Use bulk sync for multiple orders
    - Consider queue jobs for background syncing

## Security Considerations

1. **API Credentials**:
    - Never expose in client-side code
    - Only store in database with encryption if possible
    - Add env variable for sensitive keys

2. **Validation**:
    - Validate all user inputs
    - Check authorization before operations
    - Verify order ownership

3. **Audit Trail**:
    - All API calls logged in `dropshipping_api_logs`
    - Check logs for suspicious activity
    - Monitor failed API attempts

## Troubleshooting Commands

```bash
# Clear API logs (keep recent)
DELETE FROM dropshipping_api_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY);

# Check API configuration
SELECT * FROM dropshipping_settings;

# Count products & orders
SELECT COUNT(*) as products FROM dropshipping_products;
SELECT COUNT(*) as orders FROM dropshipping_orders;

# Check failed API calls
SELECT * FROM dropshipping_api_logs WHERE success = 0 ORDER BY created_at DESC LIMIT 10;

# Sync stalled orders (not updated in 24h)
SELECT * FROM dropshipping_orders WHERE updated_at < DATE_SUB(NOW(), INTERVAL 24 HOUR) AND cj_order_status != 'delivered';
```

---

For detailed setup, see: **DROPSHIPPING_INTEGRATION_GUIDE.md**
