# CJ Dropshipping Integration Guide

## Overview

This guide explains how to integrate CJ Dropshipping API with your e-commerce platform. This system allows you to:

- Import dropshipping products from CJ directly
- Manage dropshipping inventory
- Submit orders to CJ automatically
- Track dropshipping orders separately
- Calculate and monitor profits on each dropshipping sale
- Display both local and dropshipping products on the frontend

## Architecture

### Database Structure

#### Dropshipping Products (`dropshipping_products`)

- Stores CJ dropshipping products imported to your store
- Maintains cost price and your selling price
- Tracks profit margin for each product
- Fields: `cj_product_id`, `name`, `description`, `unit_price`, `selling_price`, `profit_margin`, `stock`, etc.

#### Dropshipping Orders (`dropshipping_orders`)

- Tracks orders submitted to CJ
- Maintains separate order status from main orders
- Tracks costs vs revenue and profits
- Links to the main `orders` table
- Fields: `order_id`, `cj_order_number`, `cj_order_status`, `cost_price`, `selling_price`, `profit`

#### Dropshipping Order Items (`dropshipping_order_items`)

- Individual items in a dropshipping order
- Tracks per-item costs and prices
- Links both to dropshipping order and product

#### Dropshipping Settings (`dropshipping_settings`)

- Stores CJ API credentials
- Configuration options for automation
- Profit margin defaults

#### Dropshipping API Logs (`dropshipping_api_logs`)

- Logs all API requests/responses
- Helps with debugging and auditing

## Installation Steps

### 1. Run Database Migrations

```bash
php artisan migrate
```

This creates all required tables for the dropshipping system.

### 2. Configure CJ API Credentials

1. Go to Admin Dashboard
2. Navigate to **Dropshipping → Settings**
3. Enter your CJ API credentials:
    - **API Key**: Get from your CJ account settings
    - **API Secret**: Get from your CJ account settings
    - **API URL**: Default is `https://api.cjdropshipping.com`
4. Set your default profit margin percentage (e.g., 20%)
5. Click **Test Connection** to verify credentials
6. Save settings

### 3. How to Get CJ API Credentials

1. Log in to your CJ Dropshipping account
2. Go to **Settings → API Settings**
3. Generate new API Key and API Secret
4. Copy both and paste them into the dropshipping settings page
5. Click "Test Connection" to verify

## Using the Dropshipping System

### Dashboard Menu

A new menu section is available: **Dropshipping** with the following options:

- **Products**: Manage dropshipping products
- **Orders**: Manage dropshipping orders
- **Settings**: Configure API and options

### Importing Products from CJ

1. Go to **Dropshipping → Products**
2. Click **Import Product** button
3. Search for a product by keyword/name
4. Select from search results
5. Set your selling price (cost price shown automatically)
6. Review profit margin
7. Click **Import Product**

The product is now added to your dropshipping products list and available for customers.

### Managing Dropshipping Products

**List View:**

- Filter by search, status, and availability
- Bulk update prices (apply margin percentage)
- Quick edit individual products
- View products' order history
- Delete products from your store

**Edit Product:**

- Adjust selling price
- Enable/disable product
- Manage stock status
- View product details from CJ

### Submitting Orders to CJ

**Workflow:**

1. Customer places order (containing dropshipping products)
2. Admin confirms the order
3. Go to **Dropshipping → Orders → Submit Order**
4. Select confirmed orders
5. Click **Submit**
6. Order is submitted to CJ with customer details

**What Happens:**

- A dropshipping order is created and linked to the original order
- Order details are sent to CJ (products, quantities, shipping address)
- Tracking starts automatically
- Order status syncs periodically

### Monitoring Dropshipping Orders

**Order List:**

- View all dropshipping orders
- Filter by status: Pending, Confirmed, Shipped, Delivered
- Filter by date range
- Search by order number
- View key metrics: cost, revenue, profit

**Order Details:**

- Customer information
- Shipping details
- Timeline tracking (submitted, confirmed, shipped, delivered)
- Items ordered with costs and prices
- Financial breakdown (cost, revenue, profit, margin %)
- Sync status with CJ
- Cancel order (if needed)

### Syncing Order Status

Orders can be synced manually or automatically:

**Manual Sync:**

1. Go to **Dropshipping → Orders**
2. Click **Sync Status** on any order
3. Latest status from CJ is fetched and displayed

**Bulk Sync:**

1. Select multiple orders
2. Click **Bulk Sync Orders**
3. All selected orders are synced in batch

## Product Display on Frontend

### Combined Product Listing

Both local and dropshipping products are displayed together on:

- Home page
- Shop page
- Category pages
- Search results

Products are marked with a badge indicating:

- **Local Product**: In-house inventory
- **Dropshipping Product**: From CJ

### Product Details Page

Shows:

- Full product information
- Availability status
- Price (selling price)
- Shipping information
- Add to cart button

### Checkout Process

When a customer buys dropshipping products:

1. Order is created with order items
2. Payment is processed as normal
3. Order status becomes "confirmed"
4. Product type (local/dropshipping) is tracked
5. Admin can then submit to CJ

## API Reference

### CJDropshippingService Class

Located at: `app/Services/CJDropshippingService.php`

**Key Methods:**

```php
// Search products
$service->searchProducts($keyword, $page, $limit);

// Get product details
$service->getProductDetails($cjProductId);

// Get product prices
$service->getProductPrices($cjProductIds);

// Get inventory
$service->getProductInventory($cjProductId);

// Create order
$service->createOrder($orderData);

// Get order status
$service->getOrderStatus($cjOrderNumber);

// Get tracking info
$service->getOrderTracking($cjOrderNumber);

// Cancel order
$service->cancelOrder($cjOrderNumber, $reason);

// Sync product
$service->syncProduct($cjProductId, $sellingPrice);
```

## Settings Explained

### CJ API Credentials

- **API Key & Secret**: Required for all API requests
- **API URL**: Base endpoint for CJ (usually doesn't change)

### Dropshipping Options

- **Enable Dropshipping**: Turn feature on/off
- **Auto-Confirm Orders**: Automatically confirm orders on CJ when submitted
- **Default Profit Margin**: Percentage added to cost price when importing (e.g., 20%)

## Monitoring & Reports

### Dashboard Metrics

- **Total Orders**: All dropshipping orders submitted
- **Pending Orders**: Awaiting confirmation from CJ
- **Shipped Orders**: In transit to customer
- **Total Profit**: Sum of all profits from dropshipping sales

### API Logs

All API calls are logged in `dropshipping_api_logs` table:

- Request/response data
- Success/failure status
- Error messages
- Related order/product

You can view logs for debugging purposes.

## Troubleshooting

### API Connection Failed

- Verify API Key and Secret are correct
- Check CJ account is active and in good standing
- Ensure API is enabled in CJ settings
- Check firewall/network connectivity

### Orders Not Syncing

- Click "Sync Status" manually
- Check API logs for errors
- Verify CJ order number is correct
- Ensure API credentials are still valid

### Products Not Importing

- Search term must be at least 2 characters
- Product might not be available in CJ
- Check stock availability on CJ
- Try different search keywords

### Profit Calculation

- Profit = Selling Price - Cost Price
- Margin % = (Profit / Selling Price) × 100
- Review individual product economics

## Best Practices

1. **Regular Syncing**: Sync orders frequently to keep status current
2. **Profit Margins**: Set competitive margins to maximize profit
3. **Stock Monitoring**: Track stock levels to avoid overselling
4. **Customer Communication**: Inform customers about dropshipping shipping times
5. **API Monitoring**: Check API logs periodically for issues
6. **Testing**: Test with small orders first
7. **Documentation**: Keep records of profit margins and strategies

## Advanced Features

### Bulk Price Updates

- Update multiple product prices at once
- Apply percentage margin to all selected products
- Useful for promotions or margin adjustments

### Order Cancellation

- Cancel orders submitted to CJ if needed
- Provide reason for cancellation
- Updates order status and main order

### Tracking Integration

- Automatic tracking number capture
- Customers can track orders in their dashboard
- Real-time status updates

## Support & Debugging

### Check API Logs

Go to database and query: `SELECT * FROM dropshipping_api_logs ORDER BY created_at DESC`

### Enable Debug Mode

Set `APP_DEBUG=true` in `.env` and check `storage/logs/laravel.log`

### Common Errors

- **401 Unauthorized**: Check API credentials
- **404 Not Found**: Product/order not found on CJ
- **Timeout**: CJ server might be slow, try again later
- **Invalid Address**: Shipping address format incorrect

## Next Steps

1. ✅ Run migrations
2. ✅ Configure API credentials
3. ✅ Test API connection
4. ✅ Import first product
5. ✅ Create test order
6. ✅ Submit order to CJ
7. ✅ Monitor order status
8. ✅ Track shipment

---

**Version**: 1.0  
**Last Updated**: February 17, 2026
