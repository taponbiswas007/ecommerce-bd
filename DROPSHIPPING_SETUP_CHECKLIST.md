# CJ Dropshipping Integration - Setup Checklist

Complete this checklist to ensure your dropshipping system is properly installed and configured.

## ✅ Installation Phase

### Database Setup

- [ ] Run migrations: `php artisan migrate`
- [ ] Verify tables created in database:
    - [ ] `dropshipping_products` table exists
    - [ ] `dropshipping_orders` table exists
    - [ ] `dropshipping_order_items` table exists
    - [ ] `dropshipping_settings` table exists
    - [ ] `dropshipping_api_logs` table exists
    - [ ] `orders` table has `dropshipping_order_id` relationship (optional)

### Model Classes

- [ ] `DropshippingProduct.php` model created
- [ ] `DropshippingOrder.php` model created
- [ ] `DropshippingOrderItem.php` model created
- [ ] `DropshippingSetting.php` model created
- [ ] `DropshippingApiLog.php` model created
- [ ] `Order.php` model updated with `dropshippingOrder()` relationship

### Service Classes

- [ ] `CJDropshippingService.php` created
- [ ] Service methods implemented:
    - [ ] `searchProducts()`
    - [ ] `getProductDetails()`
    - [ ] `createOrder()`
    - [ ] `getOrderStatus()`
    - [ ] `cancelOrder()`

### Controllers

- [ ] `DropshippingProductController.php` created
- [ ] `DropshippingOrderController.php` created
- [ ] `DropshippingSettingController.php` created
- [ ] All route methods implemented

### Routes

- [ ] Routes added to `routes/web.php`
- [ ] Admin dropshipping routes registered:
    - [ ] `/admin/dropshipping/settings`
    - [ ] `/admin/dropshipping/products`
    - [ ] `/admin/dropshipping/orders`
- [ ] Routes tested in browser

### Views

- [ ] Settings view created: `settings.blade.php`
- [ ] Product views created:
    - [ ] `products/index.blade.php`
    - [ ] `products/create.blade.php`
    - [ ] `products/edit.blade.php`
    - [ ] `products/show.blade.php`
- [ ] Order views created:
    - [ ] `orders/index.blade.php`
    - [ ] `orders/create.blade.php`
    - [ ] `orders/show.blade.php`

## ✅ Configuration Phase

### CJ Account Setup

- [ ] Register/have active CJ Dropshipping account
- [ ] Contact CJ to enable API access (if required)
- [ ] Generate API Key
- [ ] Generate API Secret
- [ ] Keep credentials safe (don't share publicly)

### Laravel Configuration

- [ ] Access Admin Dashboard
- [ ] Navigate to: **Dropshipping → Settings**
- [ ] Enter CJ API Key
- [ ] Enter CJ API Secret
- [ ] Verify API URL is set correctly
- [ ] Set default profit margin percentage (e.g., 20%)
- [ ] Click **"Test Connection"** button
- [ ] Confirm test passes (you should see success message)
- [ ] Enable **"Enable Dropshipping"** toggle if desired
- [ ] Option: Enable **"Auto-Confirm Orders"** (disabled by default, recommended)
- [ ] Save settings

### Frontend Integration (Optional)

- [ ] Update product listing query to include dropshipping products
- [ ] Add "Dropshipping" badge/indicator on product pages
- [ ] Update checkout to handle dropshipping products
- [ ] Test adding dropshipping product to cart
- [ ] Test checkout process with dropshipping product

## ✅ Testing Phase

### API Connectivity

- [ ] [ ] Test API connection from settings page
- [ ] Log should show successful response
- [ ] Check `dropshipping_api_logs` table for entries
- [ ] Verify no authentication errors

### Product Import

- [ ] Search for a test product from CJ
- [ ] Import product with sample selling price
- [ ] Verify product appears in products list
- [ ] Check product details show:
    - [ ] Product name from CJ
    - [ ] Cost price (CJ price)
    - [ ] Your selling price
    - [ ] Profit margin calculated
    - [ ] Stock level
- [ ] Edit the product (change price)
- [ ] Verify price saves correctly
- [ ] Profit margin updates automatically

### Order Submission

- [ ] Create a test order with imported dropshipping product
- [ ] Confirm the order in admin
- [ ] Go to **Dropshipping → Orders → Create**
- [ ] Select the test order
- [ ] Click **"Submit to CJ"**
- [ ] Verify success message
- [ ] Check dropshipping order created
- [ ] Verify order appears in orders list
- [ ] Check `dropshipping_orders` table has entry

### Order Sync

- [ ] Click **"Sync Status"** on test order
- [ ] Check status updated (should still be pending or confirmed)
- [ ] Verify `dropshipping_api_logs` shows sync request
- [ ] Check order shows in order history table on show page

## ✅ Documentation & Deployment Phase

### Documentation

- [ ] Review DROPSHIPPING_INTEGRATION_GUIDE.md
- [ ] Review DROPSHIPPING_QUICK_REFERENCE.md
- [ ] Share with team members who'll manage dropshipping
- [ ] Document your specific configuration (margins, processes, etc.)

### Deployment

- [ ] Flush all caches: `php artisan cache:clear`
- [ ] Clear configs: `php artisan config:clear`
- [ ] Rebuild autoloader: `composer dump-autoload`
- [ ] Test all routes work in production
- [ ] Verify database migrations applied on production server
- [ ] Test API connectivity in production

## ✅ Ongoing Operations

### Daily Tasks

- [ ] [ ] Monitor pending orders
- [ ] [ ] Sync order statuses (daily or based on schedule)
- [ ] Check for any API errors in logs

### Weekly Tasks

- [ ] [ ] Review profit margins on products
- [ ] [ ] Check stuck/stale orders
- [ ] [ ] Adjust prices if needed
- [ ] [ ] Review API logs for patterns/issues

### Monthly Tasks

- [ ] [ ] Generate reports on dropshipping performance
- [ ] [ ] Analyze profitability by product
- [ ] [ ] Review customer feedback on dropshipping orders
- [ ] [ ] Optimize product selection and pricing
- [ ] [ ] Archive old API logs

## ✅ Troubleshooting Checklist

### If API Connection Fails

- [ ] Verify API Key is correct (no extra spaces)
- [ ] Verify API Secret is correct
- [ ] Check CJ account is in good standing
- [ ] Verify API is enabled in CJ account settings
- [ ] Test with postman/curl to isolate issue
- [ ] Check firewall/network allows outbound HTTPS
- [ ] Check Laravel logs: `storage/logs/laravel.log`

### If Products Won't Import

- [ ] Search term too short (needs 2+ characters)?
- [ ] Product exists on CJ? Try different search terms
- [ ] Product has stock available?
- [ ] Check API logs for error details
- [ ] Verify cost price is being returned correctly

### If Orders Won't Submit

- [ ] Order status is "confirmed"?
- [ ] Customer has valid shipping address?
- [ ] Contact info (email/phone) filled in?
- [ ] Check order items have valid dropshipping products
- [ ] Check API logs for response errors
- [ ] Verify CJ order format is correct

### If Status Not Syncing

- [ ] CJ order number exists in database?
- [ ] API credentials still valid?
- [ ] Check API logs for sync failures
- [ ] Try manual sync first
- [ ] Check CJ order status on CJ website

## ✅ Performance Optimization

- [ ] Database indexes added (check in migrations)
- [ ] API calls wrapped in try-catch
- [ ] Error logging implemented
- [ ] API logs cleanup schedule (delete old logs)
- [ ] Consider background jobs for frequent syncs
- [ ] Batch operations for bulk updates

## ✅ Security Checklist

- [ ] API credentials stored safely (not in code/config)
- [ ] Only admins can access dropshipping section
- [ ] All user inputs validated
- [ ] API logs accessible only to admins
- [ ] No API credentials logged in error messages
- [ ] HTTPS enabled for API communication
- [ ] Rate limiting considered for API calls

## ✅ Team Training

Document who should handle:

- [ ] **Product Management**: Importing & updating dropshipping products
- [ ] **Order Management**: Submitting orders to CJ
- [ ] **Customer Support**: Handling dropshipping-specific issues
- [ ] **Analytics**: Monitoring profits and performance
- [ ] **Settings**: Managing API credentials

Train team on:

- [ ] How to import products
- [ ] How to submit orders
- [ ] How to check order status
- [ ] How to handle cancellations
- [ ] How to check profit reports

## ✅ Launch Readiness

Before going live with dropshipping:

- [ ] All features tested thoroughly
- [ ] Team trained on operations
- [ ] Documentation complete
- [ ] Backup plan for API failures
- [ ] Customer communication ready (about longer shipping)
- [ ] Support team briefed
- [ ] Monitor closely for first week
- [ ] Have CJ support contact info handy

## ✅ Post-Launch Monitoring

First Week:

- [ ] [] Monitor API logs daily
- [ ] [] Check for sync issues
- [ ] [] Verify orders reaching CJ
- [ ] [] Track customer feedback
- [ ] [] Monitor system performance

First Month:

- [ ] [] Review profitability
- [ ] [] Analyze customer satisfaction
- [ ] [] Optimize product selection
- [ ] [] Fine-tune pricing
- [ ] [] Document issues encountered
- [ ] [] Plan improvements

## Notes & Comments

```
Date Started: _______________
Completed By: _______________

Notes:
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________

Issues Encountered:
_________________________________________________________________

_________________________________________________________________

Improvements Made:
_________________________________________________________________

_________________________________________________________________
```

---

## Quick Contact List

- **CJ Support**: support@cjdropshipping.com
- **CJ API Docs**: https://api-docs.cjdropshipping.com
- **Your Laravel Project**: [Your Project URL]
- **Support Contact**: [Your Contact Info]

---

**Version**: 1.0  
**Last Updated**: February 17, 2026  
**Print this checklist and keep it handy during setup!**
