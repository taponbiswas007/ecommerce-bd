# অর্ডার ট্র্যাকিং সিস্টেম - দ্রুত শুরু করার গাইড

## কি কি নতুন ফিচার যোগ হয়েছে?

### ১. Admin Panel এ যা যোগ হয়েছে:

#### Order Status Update

- Admin করতে পারবে:
  ✅ Order status change করতে পারবে
  ✅ Status এর সাথে document upload করতে পারবে (PDF, JPG, PNG)
  ✅ Notes লিখতে পারবে
  ✅ Current location add করতে পারবে

**কোথায় পাবেন**: Admin Panel → Orders → যেকোনো order এ click → "Update Order Status" section

#### Tracking History

- সম্পূর্ণ history দেখা যাবে:
  ✅ কখন কোন status change হয়েছে
  ✅ কোন admin change করেছে
  ✅ Upload করা documents
  ✅ Location information
  ✅ Notes

**কোথায় পাবেন**: Same page এর নিচে "Order Tracking History" section

### ২. Customer Panel এ যা যোগ হয়েছে:

#### Track Order Button

- Customer এখন order track করতে পারবে
- "My Orders" page থেকে প্রতিটি order এ "Track" button আছে

#### Tracking Page

একটি dedicated tracking page যেখানে:
✅ Beautiful timeline design
✅ সব status change এর history
✅ Admin এর upload করা documents download করা যায়
✅ Location information দেখা যায়
✅ Animated পুরো timeline

**কোথায় পাবেন**:

1. My Orders → Track button
2. Order Details → Track Order button

#### Order Details Page Update

✅ Latest 4টি tracking update দেখায়
✅ Documents download করার option
✅ "View Full Timeline" button

## কিভাবে ব্যবহার করবেন?

### Admin হিসেবে:

#### ১. Order Status Update করতে:

```
1. Admin Panel login করুন
2. Orders menu তে click করুন
3. যে order update করবেন তাতে click করুন
4. "Update Order Status" section খুঁজুন
5. নিচের information দিন:
   - Order Status: একটি status select করুন
   - Current Location: (Optional) যেমন "Dhaka Distribution Center"
   - Upload Document: (Optional) delivery challan বা অন্য document
   - Status Notes: (Optional) কিছু লিখতে চাইলে
6. "Update Status" button এ click করুন
```

#### ২. Tracking History দেখতে:

```
1. যেকোনো order এর details page এ যান
2. নিচে scroll করুন
3. "Order Tracking History" section দেখুন
4. সব updates chronologically দেখাবে
```

### Customer হিসেবে:

#### ১. অর্ডার Track করতে:

```
Method 1:
1. "My Orders" page এ যান
2. যে order track করবেন তার "Track" button এ click করুন

Method 2:
1. Order Details দেখুন
2. "Track Order" button এ click করুন
```

#### ২. Document Download করতে:

```
1. Tracking page খুলুন
2. যে status update এ document icon দেখছেন
3. "Download" button এ click করুন
```

## Status গুলোর অর্থ:

| Status            | বাংলা                 | কখন ব্যবহার হয়               |
| ----------------- | --------------------- | ----------------------------- |
| **Pending**       | অপেক্ষমাণ             | Order নতুন placed হয়েছে      |
| **Confirmed**     | নিশ্চিত               | Admin order confirm করেছে     |
| **Processing**    | প্রক্রিয়াকরণ         | Order prepare করা হচ্ছে       |
| **Ready to Ship** | পাঠানোর জন্য প্রস্তুত | Pack করা হয়ে গেছে            |
| **Shipped**       | পাঠানো হয়েছে         | Courier এ দিয়ে দেওয়া হয়েছে |
| **Delivered**     | ডেলিভারি সম্পন্ন      | Customer কাছে পৌঁছেছে         |
| **Completed**     | সম্পূর্ণ              | সব কিছু শেষ                   |
| **Cancelled**     | বাতিল                 | Order বাতিল করা হয়েছে        |
| **Refunded**      | ফেরত                  | টাকা ফেরত দেওয়া হয়েছে       |

## গুরুত্বপূর্ণ পয়েন্ট:

### Admin এর জন্য:

⚠️ **Document Upload**:

- শুধু PDF, JPG, PNG file upload করতে পারবেন
- Maximum file size: 5MB
- প্রতিটি status change এ document optional

⚠️ **Notes**:

- Customer এই notes দেখতে পারবে
- তাই professional language এ লিখুন

⚠️ **Location**:

- যদি order shipped অবস্থায় থাকে, location দিন
- Example: "Dhaka Hub", "Chittagong Distribution Center"

### Customer এর জন্য:

✅ আপনি শুধু নিজের order track করতে পারবেন
✅ Document সবসময় পাবেন না, admin দিলে পাবেন
✅ Real-time timeline দেখতে পারবেন
✅ Mobile এও সুন্দর দেখাবে

## Example একটা Complete Flow:

```
Day 1, 10:00 AM: Customer order করল
→ Status: Pending

Day 1, 11:30 AM: Admin confirm করল
→ Status: Confirmed
→ Notes: "Order confirmed, will process within 24 hours"

Day 2, 2:00 PM: Admin processing শুরু করল
→ Status: Processing
→ Notes: "Preparing your items"
→ Location: "Dhaka Warehouse"

Day 2, 4:30 PM: Admin pack করে ফেলল
→ Status: Ready to Ship
→ Notes: "Order packed and ready for shipment"

Day 3, 9:00 AM: Courier এ দিয়ে দিল
→ Status: Shipped
→ Document: delivery_challan.pdf (uploaded)
→ Notes: "Handed over to Sundarban Courier"
→ Location: "Dhaka Hub"

Day 4, 3:00 PM: Customer এর কাছে পৌঁছাল
→ Status: Delivered
→ Document: delivery_receipt.pdf (uploaded)
→ Notes: "Successfully delivered to customer"

Day 4, 4:00 PM: সব confirm
→ Status: Completed
```

এই পুরো flow customer tracking page এ সুন্দর timeline হিসেবে দেখবে!

## Troubleshooting:

### যদি Document Upload না হয়:

- File size check করুন (5MB এর কম)
- File format check করুন (PDF/JPG/PNG)
- Internet connection check করুন

### যদি Tracking না দেখায়:

- Page refresh করুন
- Browser cache clear করুন
- আবার login করে try করুন

### যদি Document Download না হয়:

- File আসলেই upload করা হয়েছে কিনা check করুন
- Browser এ pop-up blocker off আছে কিনা check করুন

## Database:

Migration already run করা হয়েছে। New table created:

- `order_status_histories` - সব tracking data এখানে save হয়

## Files Modified/Created:

### Database:

- `database/migrations/2026_02_09_000001_create_order_status_histories_table.php`

### Models:

- `app/Models/OrderStatusHistory.php` (NEW)
- `app/Models/Order.php` (Updated with relationships)

### Controllers:

- `app/Http/Controllers/Admin/OrderController.php` (Updated)
- `app/Http/Controllers/Customer/OrderController.php` (Updated)

### Views:

- `resources/views/admin/orders/show.blade.php` (Updated with tracking)
- `resources/views/customer/orders/tracking.blade.php` (NEW)
- `resources/views/customer/orders/show.blade.php` (Updated)
- `resources/views/customer/orders/index.blade.php` (Updated)

### Routes:

- `routes/web.php` (Added tracking routes)

## Support:

যদি কোনো সমস্যা হয় বা question থাকে, documentation দেখুন:
📄 `ORDER_TRACKING_DOCUMENTATION.md`

---

🎉 এখন আপনার e-commerce site এ professional order tracking system আছে!
