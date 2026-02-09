# Order Status Update Troubleshooting

## Issue: Order status update not working

### ✅ Fixed Issues:

1. **updateStatus method incomplete** - Fixed to properly:
    - Save order status
    - Handle document uploads
    - Create history records
    - Update timestamp fields

2. **JavaScript improvements**:
    - Better error handling
    - Bootstrap alert messages instead of browser alerts
    - Validation error display
    - Smooth scrolling to alerts

3. **Controller cleanup**:
    - Removed duplicate methods
    - Fixed document upload logic
    - Proper FormData handling

## Testing Steps:

### 1. Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 2. Check Database

```sql
-- Check if order_status_histories table exists
SHOW TABLES LIKE 'order_status_histories';

-- Check table structure
DESCRIBE order_status_histories;
```

### 3. Test in Browser

#### Admin Panel:

1. Navigate to: `/admin/orders/{any_order_id}`
2. Open browser console (F12)
3. Try to update status
4. Check for any JavaScript errors

#### Expected Behavior:

- ✅ Form disables button while submitting
- ✅ Shows "Updating..." spinner
- ✅ On success: Green alert message appears
- ✅ Page reloads after 1.5 seconds
- ✅ New history entry appears in timeline
- ✅ Order status updated in database

#### If Error Occurs:

- ❗ Red alert message appears
- ❗ Validation errors shown in list
- ❗ Button re-enables for retry
- ❗ Check console for details

### 4. Common Issues & Solutions

#### Issue: "CSRF token mismatch"

**Solution**:

```bash
php artisan cache:clear
```

Then refresh the page

#### Issue: "Route not found"

**Solution**:

```bash
php artisan route:cache
# or
php artisan route:clear
```

#### Issue: "Class OrderStatusHistory not found"

**Solution**:

```bash
composer dump-autoload
```

#### Issue: "Column not found"

**Solution**: Run migration again

```bash
php artisan migrate:fresh
# or just the specific migration
php artisan migrate --path=/database/migrations/2026_02_09_000001_create_order_status_histories_table.php
```

#### Issue: "Storage link not working for documents"

**Solution**:

```bash
php artisan storage:link
```

### 5. Manual Test via Artisan Tinker

```php
php artisan tinker

// Get an order
$order = \App\Models\Order::first();

// Check current status
$order->order_status;

// Update status manually
$order->order_status = 'confirmed';
$order->save();

// Create a history record
\App\Models\OrderStatusHistory::create([
    'order_id' => $order->id,
    'status' => 'confirmed',
    'previous_status' => 'pending',
    'notes' => 'Test history',
    'updated_by' => 1, // Admin user ID
    'status_date' => now(),
]);

// Check if it worked
$order->statusHistories()->count();
$order->statusHistories;
```

### 6. Check Logs

If still not working, check Laravel logs:

```bash
tail -f storage/logs/laravel.log
```

### 7. Network Tab Check

In browser console Network tab:

1. Open Network tab
2. Submit the form
3. Look for the POST request to `/admin/orders/{id}/update-status`
4. Check:
    - Request Headers (should have X-CSRF-TOKEN)
    - Request Payload (should have FormData)
    - Response (should be JSON with success: true)

### 8. Verify File Permissions

```bash
# Make sure storage is writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Check if storage/app/public/order_documents exists
ls -la storage/app/public/
```

## Updated Files:

1. ✅ `app/Http/Controllers/Admin/OrderController.php`
    - Fixed `updateStatus()` method
    - Cleaned duplicate methods
    - Proper document handling

2. ✅ `resources/views/admin/orders/show.blade.php`
    - Added alert message div
    - Improved JavaScript with better error handling
    - Bootstrap alerts instead of browser alerts

## Expected Database Records:

After successful update, you should see:

**orders table**:

- `order_status` changed to new value
- `confirmed_at`, `shipped_at`, `delivered_at`, or `completed_at` updated (based on status)

**order_status_histories table**:

- New record with:
    - `order_id`
    - `status` (new status)
    - `previous_status` (old status)
    - `notes` (if provided)
    - `document_path` (if file uploaded)
    - `updated_by` (admin user ID)
    - `location` (if provided)
    - `status_date` (current timestamp)

## Quick Verification Command:

Run this after trying to update:

```bash
php artisan tinker

# Check last status history
\App\Models\OrderStatusHistory::latest()->first();

# If null, then history is not being created - check controller
```

## Contact Admin if Still Not Working:

Provide:

1. Laravel version: `php artisan --version`
2. Error from browser console (F12 → Console)
3. Error from `storage/logs/laravel.log`
4. Screenshot of Network tab showing the request/response
