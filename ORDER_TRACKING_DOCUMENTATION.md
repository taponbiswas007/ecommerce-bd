# Order Tracking System Documentation

## Overview

এই প্রজেক্টে একটি সম্পূর্ণ Order Tracking System ইমপ্লিমেন্ট করা হয়েছে যেখানে:

- Admin order status change করলে সাথে document upload করতে পারবে
- Customer সেই document download করতে পারবে
- Customer বিস্তারিত tracking timeline দেখতে পারবে
- সব status change এর history রাখা হয়

## Features Implemented

### 1. Order Status History Tracking

- **Database Table**: `order_status_histories`
- **Model**: `App\Models\OrderStatusHistory`
- প্রতিটি status change এর জন্য একটি record তৈরি হয়
- History তে থাকে:
    - Previous status
    - New status
    - Status change date & time
    - Admin notes
    - Document (যদি upload করা হয়)
    - Location information
    - কে update করেছে (Admin name)

### 2. Admin Features

#### Order Status Update (Admin Panel)

**Location**: `/admin/orders/{id}` - [admin/orders/show.blade.php](resources/views/admin/orders/show.blade.php)

Admin যা করতে পারে:

- Order status change করতে পারে
- Status change এর সাথে notes লিখতে পারে
- Document upload করতে পারে (PDF, JPG, PNG - Max 5MB)
- Current location যোগ করতে পারে (e.g., "Dhaka Distribution Center")

**Status Options**:

- Pending (অপেক্ষমাণ)
- Confirmed (নিশ্চিত)
- Processing (প্রক্রিয়াকরণ)
- Ready to Ship (পাঠানোর জন্য প্রস্তুত)
- Shipped (পাঠানো হয়েছে)
- Delivered (ডেলিভারি সম্পন্ন)
- Completed (সম্পূর্ণ)
- Cancelled (বাতিল)
- Refunded (ফেরত)

#### Order Tracking History (Admin Panel)

Admin panel এ পূর্ণ tracking timeline দেখায়:

- সব status change এর history
- কোন admin update করেছে
- Upload করা documents
- Location information
- Timestamp সহ সব details

### 3. Customer Features

#### Order Details Page

**Location**: `/orders/{id}` - [customer/orders/show.blade.php](resources/views/customer/orders/show.blade.php)

Customer দেখতে পারে:

- Order এর বর্তমান status
- সর্বশেষ 4টি tracking update
- Upload করা documents download করতে পারে
- "Track Order" button - full tracking page এ যাওয়ার জন্য

#### Dedicated Tracking Page

**Location**: `/orders/{id}/tracking` - [customer/orders/tracking.blade.php](resources/views/customer/orders/tracking.blade.php)

একটি beautiful timeline সহ:

- সব status change এর পূর্ণ history
- প্রতিটি update এর timestamp
- Admin এর notes
- Location information
- Documents download করার option
- Order items এর summary
- Quick info cards (Order date, total items, amount)

**Timeline Features**:

- Visual timeline with icons
- Color-coded status badges
- Current status highlighted করা থাকে
- Animated pulse effect বর্তমান status এ
- Bengali & English status display

#### Orders List Page

**Location**: `/orders` - [customer/orders/index.blade.php](resources/views/customer/orders/index.blade.php)

- প্রতিটি order এর জন্য "Track" button
- Confirmed থেকে Completed পর্যন্ত সব order track করা যায়

### 4. Routes Added

#### Customer Routes

```php
Route::get('/orders/{order}/tracking', [OrderController::class, 'tracking'])
    ->name('customer.orders.tracking');

Route::get('/orders/{order}/document/{historyId}', [OrderController::class, 'downloadDocument'])
    ->name('customer.orders.download-document');
```

#### Admin Routes

```php
Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])
    ->name('admin.orders.update-status');

Route::post('orders/{order}/upload-document', [OrderController::class, 'uploadDocument'])
    ->name('admin.orders.upload-document');

Route::get('orders/{order}/tracking-history', [OrderController::class, 'trackingHistory'])
    ->name('admin.orders.tracking-history');
```

### 5. Database Schema

**Table**: `order_status_histories`

```php
- id
- order_id (foreign key)
- status (current status)
- previous_status (previous status)
- notes (text - admin notes)
- document_path (uploaded document path)
- document_name (original file name)
- updated_by (foreign key - admin user)
- location (current location)
- status_date (timestamp)
- created_at
- updated_at
```

### 6. Controller Methods

#### Admin OrderController

- `updateStatus()` - Status update করে এবং history record তৈরি করে
- `uploadDocument()` - Document upload করে এবং history তে save করে
- `trackingHistory()` - JSON response এ tracking history return করে

#### Customer OrderController

- `tracking()` - Tracking page দেখায়
- `downloadDocument()` - Document download করায় (security check সহ)

### 7. Model Relationships

**Order Model**:

```php
public function statusHistories() // সব history
public function latestStatusHistory() // সর্বশেষ history
```

**OrderStatusHistory Model**:

```php
public function order() // Parent order
public function updatedBy() // যে admin update করেছে
```

**Accessors**:

- `status_display` - Bengali & English status name
- `status_icon` - Font Awesome icon class
- `status_color` - Bootstrap color class

## Usage Instructions

### For Admin:

1. **Order Status Update করতে**:
    - Admin panel → Orders → Order details page এ যান
    - "Update Order Status" section এ:
        - নতুন status select করুন
        - (Optional) Location লিখুন
        - (Optional) Document upload করুন
        - (Optional) Notes লিখুন
    - "Update Status" button click করুন

2. **Tracking History দেখতে**:
    - Order details page এর নিচে "Order Tracking History" section দেখুন
    - সব update chronologically দেখাবে

### For Customer:

1. **Order Track করতে**:
    - My Orders page থেকে "Track" button click করুন
    - অথবা Order Details page থেকে "Track Order" button click করুন

2. **Document Download করতে**:
    - Tracking page এ যান
    - যে status update এ document আছে, সেখানে "Download" button পাবেন
    - অথবা Order Details page এর timeline থেকেও download করতে পারবেন

## Security Features

- Customer শুধু নিজের order এর document download করতে পারবে
- Order ownership verification করা হয়
- File existence check করা হয়
- File type validation (PDF, JPG, PNG only)
- Max file size: 5MB

## UI/UX Features

### Admin Panel:

- Clean form interface
- Color-coded timeline
- Badge system for statuses
- AJAX form submission
- Loading states

### Customer Panel:

- Beautiful gradient headers
- Animated timeline
- Responsive design
- Color-coded status badges
- Icon-based navigation
- Mobile-friendly layout

## Technical Details

**File Storage**:

- Documents stored in `storage/app/public/order_documents/`
- Accessible via `storage/order_documents/`

**Security**:

- CSRF protection
- Authentication required
- Authorization checks
- File validation

**Performance**:

- Eager loading with relationships
- Optimized queries
- Pagination support

## Example Workflow

1. **Customer places order** → Status: Pending
2. **Admin confirms order** → Status: Confirmed (History record created)
3. **Admin starts processing** → Status: Processing (with notes)
4. **Admin marks ready to ship** → Status: Ready to Ship (with location)
5. **Admin ships order** → Status: Shipped (with document + tracking info)
6. **Admin marks delivered** → Status: Delivered (with delivery document)
7. **Order completed** → Status: Completed

প্রতিটি step এ customer real-time tracking দেখতে পারবে!

## Future Enhancements (Optional)

- Email notification যখন status change হয়
- SMS notification
- Real-time updates via WebSocket
- Customer rating system after delivery
- Export tracking history as PDF
- Multiple documents per status
- Image preview before download

---

**Implementation Date**: February 9, 2026
**Developer Notes**: সম্পূর্ণ system production-ready এবং tested।
