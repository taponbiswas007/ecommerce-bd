# Advanced Bulk Price Management - দ্রুত ব্যবহার নির্দেশিকা

## 🎯 প্রধান বৈশিষ্ট্য

### ✅ যা যা সমাধান হয়েছে

1. **Increase/Decrease Bug Fixed** - এখন percentage এ increase এবং decrease দুটোই কাজ করবে
2. **Multiple Price Fields** - Base price এবং Discount price আলাদাভাবে update করুন
3. **Tier Pricing** - Quantity অনুযায়ী different prices সেট করুন
4. **AI Assistant** - Smart pricing suggestions পান
5. **Live Preview** - Update করার আগে দেখুন কেমন দেখাবে

## 🚀 দ্রুত শুরু করুন

### ধাপ ১: Products Select করুন

```
☑️ Checkbox দিয়ে products select করুন
☑️ অথবা "Select All" button click করুন
☑️ Filters ব্যবহার করে specific products খুঁজুন
```

### ধাপ ২: Price Update Method বেছে নিন

#### Method A: Fixed Price (নির্দিষ্ট মূল্য)

- একটি নির্দিষ্ট price set করুন
- সকল selected products এ apply হবে
- উদাহরণ: সব products ৳500 করুন

#### Method B: Percentage Change (শতকরা পরিবর্তন) ✨ NEW

```
Increase দেখাতে:
1. "Percentage Change" select করুন
2. "Increase" select করুন
3. Percentage লিখুন (যেমন: 10)
4. Result: মূল্য 10% বৃদ্ধি পাবে

Decrease দেখাতে:
1. "Percentage Change" select করুন
2. "Decrease" select করুন
3. Percentage লিখুন (যেমন: 15)
4. Result: মূল্য 15% কমবে
```

#### Method C: Add/Subtract Amount (যোগ/বিয়োগ)

```
যোগ করতে:
- "Add (+)" select করুন
- Amount লিখুন (যেমন: ৳50)

বিয়োগ করতে:
- "Subtract (-)" select করুন
- Amount লিখুন (যেমন: ৳100)
```

### ধাপ ৩: Update করুন

```
1. "Preview Changes" click করুন (optional)
2. "Update Selected Products" click করুন
3. Confirm করুন
4. সম্পন্ন! ✅
```

## 💰 Discount Management

### Discount Apply করুন

```
Tab: Discount Pricing
├── Type বেছে নিন:
│   ├── Percentage Off (যেমন: 20% off)
│   ├── Fixed Discount (যেমন: ৳100 কম)
│   └── Absolute Price (যেমন: সরাসরি ৳450)
├── Value লিখুন
└── "Apply Discount" click করুন
```

### Discount Remove করুন

```
1. Products select করুন
2. "Discount Pricing" tab এ যান
3. "Remove Discounts" button click করুন
```

## 📊 Tier Pricing (Quantity-wise)

### একটি Tier যোগ করুন

```
1. "Tier Pricing" tab এ যান
2. Min Quantity: 10 (শুরু)
3. Max Quantity: 50 (শেষ, blank রাখলে unlimited)
4. Price: ৳90
5. "Add Tier" click করুন
6. আরো tier যোগ করুন (যদি দরকার হয়)
7. "Apply Tiers to Selected" click করুন
```

### উদাহরণ Tier Structure

```
Retail (1-9 units):     ৳100 প্রতি piece
Small Bulk (10-49):     ৳90 প্রতি piece
Medium Bulk (50-99):    ৳85 প্রতি piece
Large Bulk (100+):      ৳80 প্রতি piece
```

## 🤖 AI Features ব্যবহার

### 1. AI Suggestions পান

```
কখন ব্যবহার করবেন:
- নতুন products এর price set করার সময়
- দ্বিধান্বিত থাকলে
- Market research এর জন্য

কিভাবে:
1. Products select করুন
2. "Get AI Suggestions" click করুন
3. Wait করুন (5-10 seconds)
4. Suggestion পড়ুন
5. Decision নিন
```

### 2. Optimize Prices

```
কখন ব্যবহার করবেন:
- Sales কম হচ্ছে
- Stock বেশি জমে আছে
- Performance improve করতে চান

ভিত্তি:
- View count
- Sold count
- Stock level
- Conversion rate
```

### 3. Market Analysis

```
কখন ব্যবহার করবেন:
- Competitor research
- Market positioning
- Pricing strategy

পাবেন:
- Competitive analysis
- Market trends
- Strategic recommendations
```

## 🔍 Filters ব্যবহার করুন

### Search

- Product name দিয়ে খুঁজুন
- SKU দিয়ে খুঁজুন

### Category Filter

- Specific category select করুন
- সেই category এর সব products দেখুন

### Price Range

- Min Price: ৳100
- Max Price: ৳500
- এই range এর products দেখুন

### Status Filter

- Active products
- Inactive products

### Discount Filter ✨ NEW

- With Discount: যেগুলোতে discount আছে
- Without Discount: যেগুলোতে discount নেই

## 📥 Export করুন

### CSV Export

```
1. Filters apply করুন (optional)
2. "Export Prices" button click করুন
3. File download হবে
4. Excel/Google Sheets এ open করুন

Export এ থাকবে:
- Product ID, Name, SKU
- Category
- Base Price
- Discount Price
- All Tier Prices
```

## 💡 দরকারি Tips

### ✅ Do's (করবেন)

- Always preview করুন major updates এর আগে
- Small batch দিয়ে test করুন
- Regular market analysis করুন
- Export backup রাখুন

### ❌ Don'ts (করবেন না)

- ছাড়া verify thousands of products update করবেন না
- Discount price > base price করবেন না (system allow করবে না)
- AI suggestion blindly follow করবেন না
- Backup ছাড়া bulk operations করবেন না

## 🐛 Common Issues

### AI না কাজ করলে

```
সমাধান:
1. .env file এ API key check করুন
2. Internet connection verify করুন
3. Log file দেখুন: storage/logs/laravel.log
```

### Preview দেখা যাচ্ছে না

```
সমাধান:
1. Products select করেছেন কিনা check করুন
2. Update value দিয়েছেন কিনা check করুন
3. Browser console দেখুন (F12)
```

### Tier prices apply হচ্ছে না

```
সমাধান:
1. Tier list এ tiers add করেছেন কিনা check করুন
2. Products select করেছেন কিনা verify করুন
3. Min quantity valid কিনা check করুন
```

## 📱 Quick Actions

### দ্রুত সব products এ 10% discount

```
1. "Select All" click
2. "Discount Pricing" tab
3. Type: "Percentage Off"
4. Value: 10
5. "Apply Discount" ✅
```

### Category wise price increase

```
1. Category filter select করুন
2. "Select All" click
3. "Basic Pricing" → "Percentage Change"
4. "Increase" → 5 (%)
5. "Update Selected" ✅
```

### Bulk tier pricing setup

```
1. Products select করুন
2. "Tier Pricing" tab
3. Add tiers:
   - 10-49: ৳95
   - 50-99: ৳90
   - 100+: ৳85
4. "Apply Tiers" ✅
```

## 🎓 Video Tutorial Links

_(যদি available হয়)_

- Basic Price Update
- Discount Management
- Tier Pricing Setup
- AI Features Usage

## 📞 Support

### সমস্যা হলে check করুন:

1. এই documentation
2. Laravel logs: `storage/logs/laravel.log`
3. Browser console (F12)
4. Network tab (API responses)

---

**সর্বশেষ আপডেট:** March 2026  
**Version:** 2.0  
**Status:** ✅ Production Ready

**মনে রাখবেন:**

- প্রথমে test করুন
- তারপর main database এ apply করুন
- Regular backup রাখুন
- AI suggestions reference হিসেবে use করুন

**Happy Pricing! 🎉**
