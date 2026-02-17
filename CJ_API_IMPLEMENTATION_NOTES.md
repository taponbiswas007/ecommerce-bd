# CJ Dropshipping API Implementation Notes

## Important: Verify CJ API Documentation

The implementation provided uses **generic API endpoint structure** that aligns with common dropshipping API patterns. However, **CJ's actual API structure may differ**.

⚠️ **BEFORE USING IN PRODUCTION:**

1. Get CJ's official API documentation
2. Verify all endpoint URLs
3. Validate request/response formats
4. Check authentication method (the implementation uses token-based)
5. Test each method thoroughly

## CJ API Reference Pattern

### Expected Endpoints (May Vary)

```
CJ API Base: https://api.cjdropshipping.com

Product Search:
  POST /api/stock/search
  Params: keyword, page, pageSize

Product Details:
  POST /api/stock/detail
  Params: productId

Product Pricing:
  POST /api/stock/price
  Params: productIds[]

Product Inventory:
  POST /api/stock/inventory
  Params: productId

Order Creation:
  POST /api/order/add
  Params: Order object with products, shipping, etc.

Order Status:
  POST /api/order/status
  Params: orderNumber

Order Tracking:
  POST /api/order/tracking
  Params: orderNumber

Order Cancellation:
  POST /api/order/cancel
  Params: orderNumber, reason
```

## Authentication

The implementation uses:

```php
// Token-based authentication
$timestamp = time() * 1000;
$plain = $apiKey . $timestamp . $apiSecret;
$sign = hash('sha256', $plain);
$token = md5($apiKey . $sign . $timestamp);
```

**Verify this matches CJ's authentication method!**

Alternative methods may include:

- HTTP Basic Auth
- OAuth 2.0
- Alternative token format
- Different header name

## Response Format Handling

The implementation assumes:

```php
{
  "success": true,
  "message": "Success",
  "data": { /* actual data */ }
}
```

CJ may use different formats:

- `{"code": 0, "msg": "ok", "data": {...}}`
- `{"status": "success", "result": {...}}`
- Direct data without wrapper

## Implementation Adjustments Needed

### Step 1: Contact CJ for API Docs

Get official documentation that includes:

- [ ] All endpoint URLs
- [ ] Request format for each endpoint
- [ ] Response format for each endpoint
- [ ] Authentication method
- [ ] Rate limits
- [ ] Error codes and meanings
- [ ] Example requests/responses
- [ ] Required headers

### Step 2: Update Service Class

In `CJDropshippingService.php`, verify/update:

```php
// Update makeRequest() method response handling
private function makeRequest($method, $endpoint, $data = [])
{
    // Modify based on CJ's actual response format
    $responseData = $response->json();

    // Change this condition based on CJ's success indicator
    if (isset($responseData['success']) && !$responseData['success']) {
        throw new Exception($responseData['message'] ?? 'CJ API returned an error');
    }

    // Change this to extract correct data field
    return $responseData['data'] ?? $responseData;
}
```

### Step 3: Update Request Formats

Validate and update request data structure:

```php
// Example: Create Order request might need adjustment
private function formatOrderProducts($products)
{
    // Verify this matches CJ's expected format
    $formatted = [];
    foreach ($products as $product) {
        $formatted[] = [
            'productId' => $product['cj_product_id'],
            'quantity' => $product['quantity'],
            // Add/remove fields as per CJ docs
        ];
    }
    return $formatted;
}
```

### Step 4: Handle Status Codes

Different APIs use different status codes:

```php
// Current implementation assumes HTTP success codes (200-299)
if ($response->successful()) {
    // Process response
}

// CJ may use:
// - Always 200, but check "success" field
// - Always 200, check "code" field (0, 100, 101, etc.)
// - Standard HTTP codes (400, 401, 403, etc.)
```

## Common CJ API Patterns

### Authentication Token Header

```php
// Usually sent as:
'Authorization: Bearer ' . $token
// OR
'X-Authorization: ' . $token
// OR
'Authorization: ' . $token
// Verify actual header name from docs
```

### Rate Limits

```php
// Common CJ rate limits:
// - 100 requests per minute
// - 10,000 per day

// Implement rate limiting:
Cache::put('cj_api_calls_today', 0, 86400);
if (Cache::increment('cj_api_calls_today') > 10000) {
    throw new Exception('API rate limit exceeded');
}
```

### Error Handling

```php
// CJ might return:
// {"code": 401, "msg": "Unauthorized"}
// {"code": 404, "msg": "Order not found"}
// {"code": 500, "msg": "Server error"}

// Update error handling based on response codes
$code = $responseData['code'] ?? null;
if ($code === 401) {
    throw new Exception('Authentication failed: Invalid API key');
}
```

## Testing the API

### Manual Testing with cURL

```bash
# Test API connectivity
curl -X POST https://api.cjdropshipping.com/api/test \
  -H "Content-Type: application/json" \
  -H "Authorization: YourTokenHere" \
  -d '{"test": "true"}'

# Test product search
curl -X POST https://api.cjdropshipping.com/api/stock/search \
  -H "Content-Type: application/json" \
  -H "Authorization: YourTokenHere" \
  -d '{"keyword": "test", "page": 1, "pageSize": 10}'
```

### PHP Testing

```php
// Test in tinker
php artisan tinker

$service = new App\Services\CJDropshippingService();
$results = $service->searchProducts('test', 1, 5);
dd($results);

// Check API logs
App\Models\DropshippingApiLog::latest()->first();
```

## Protocol Updates

Once you receive CJ API docs, update these files:

1. **Service Class** (`app/Services/CJDropshippingService.php`):
    - Update endpoint URLs
    - Fix authentication method
    - Adjust request/response handling
    - Update error handling

2. **Models** (`app/Models/Dropshipping*.php`):
    - May need new fields based on CJ data
    - Update casts if needed
    - Add any new relationships

3. **Controllers** (`app/Http/Controllers/Admin/Dropshipping*Controller.php`):
    - May need error handling updates
    - Adjust success/error messages
    - Update validation rules

4. **Views** (`.blade.php` files):
    - May need field displays updated
    - Adjust labels if terms differ
    - Update error messages

## CJ Account Requirements

Verify your CJ account has:

- [ ] API access enabled
- [ ] API Key generated
- [ ] API Secret generated
- [ ] API is active (not suspended)
- [ ] Your account is in good standing
- [ ] Sufficient credit/balance
- [ ] Shipping addresses set up
- [ ] Payment method on file

## Common CJ Integration Issues

### Issue: "Invalid Authentication"

- Verify API Key is exact (no spaces)
- Verify API Secret is exact (no spaces)
- Check token generation matches CJ method
- Verify timestamp format (milliseconds vs seconds)

### Issue: "Product Not Found"

- Product ID format might differ
- Search might require exact fields
- Stock status might affect availability
- Regional availability might be restricted

### Issue: "Order Creation Failed"

- Shipping address format might be strict
- Required fields might differ from docs
- Contact info format might matter
- Product IDs might need different format

### Issue: "Rate Limited"

- Implement request queuing
- Add delays between requests
- Check if batch endpoints available
- Cache product data to avoid repeated searches

## Webhook Integration (Optional)

CJ might offer webhooks for order status updates:

```php
// If available, listen for CJ webhooks
// In routes/web.php:
Route::post('/webhooks/cj', [WebhookController::class, 'handleCJ']);

// In new WebhookController:
public function handleCJ(Request $request)
{
    $event = $request->get('event');
    $data = $request->get('data');

    if ($event === 'order.status_changed') {
        $dropshippingOrder = DropshippingOrder::where(
            'cj_order_number',
            $data['orderNumber']
        )->first();

        if ($dropshippingOrder) {
            $dropshippingOrder->update([
                'cj_order_status' => $data['status'],
                'updated_at' => now(),
            ]);
        }
    }

    return response()->json(['success' => true]);
}
```

## Scheduled Syncing

Implement automatic status syncing:

```php
// In app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Sync pending orders every 10 minutes
    $schedule->call(function () {
        $orders = DropshippingOrder::pending()->get();
        foreach ($orders as $order) {
            $service = new CJDropshippingService();
            try {
                $status = $service->getOrderStatus($order->cj_order_number);
                $order->update(['cj_order_status' => $status]);
            } catch (\Exception $e) {
                \Log::error('CJ sync error: ' . $e->getMessage());
            }
        }
    })->everyTenMinutes();
}
```

## Documentation Links to Request from CJ

When contacting CJ support, request:

1. API development guide/documentation
2. Sandbox/test environment access
3. API endpoint specifications (OpenAPI/Swagger)
4. Example requests and responses
5. Webhook documentation (if available)
6. Rate limiting and quota info
7. SLA and support contacts
8. Status page/incident reporting

## Validation Checklist Before Production

- [ ] API credentials tested and working
- [ ] All endpoints verified with CJ docs
- [ ] Response format handling correct
- [ ] Error handling appropriate
- [ ] Rate limits respected
- [ ] Timeouts configured reasonably
- [ ] Logging captures all API issues
- [ ] Team trained on operation
- [ ] Monitoring/alerts set up
- [ ] Fallback procedures documented

---

## Support Resources

- **CJ Website**: https://www.cjdropshipping.com
- **CJ Support Email**: support@cjdropshipping.com
- **CJ API Docs**: Check your CJ account dashboard
- **Laravel Docs**: https://laravel.com/docs
- **This Project**: Check included documentation files

---

**⚠️ Critical Reminder:**
This implementation is a **template structure**. You **must** verify and adjust all API endpoints, authentication, and request/response formats based on CJ's actual API documentation before using in production.

Do not assume the default implementation matches CJ's specifications!

---

**Last Updated**: February 17, 2026
