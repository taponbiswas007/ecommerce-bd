# Advanced Bulk Price Management System

## Overview

এই সিস্টেম আপনার সকল product এর price management সহজ করবে AI assistance সহ।

## Features Added

### 1. **Multiple Price Field Management**

- **Base Price**: মূল মূল্য আপডেট করুন
- **Discount Price**: ডিসকাউন্ট মূল্য সেট করুন
- দুটি price field আলাদাভাবে manage করতে পারবেন

### 2. **Price Update Methods (Fixed Issue)**

#### Fixed Price

- একটি নির্দিষ্ট মূল্য সেট করুন সকল selected products এ

#### Percentage Change (✅ Fixed - Increase & Decrease Both Work Now)

- **Increase**: মূল্য বৃদ্ধি করুন percentage এ
- **Decrease**: মূল্য কমান percentage এ
- উদাহরণ: 10% increase বা 15% decrease

#### Add/Subtract Amount

- **Add (+)**: একটি নির্দিষ্ট amount যোগ করুন
- **Subtract (-)**: একটি নির্দিষ্ট amount বিয়োগ করুন
- উদাহরণ: ৳50 যোগ বা ৳100 বিয়োগ

### 3. **Discount Management**

তিনটি discount type:

- **Percentage Off**: Base price থেকে percentage discount
- **Fixed Discount**: Base price থেকে fixed amount কমান
- **Absolute Price**: সরাসরি discount price set করুন

বৈশিষ্ট্য:

- Bulk discount apply করুন
- Bulk discount remove করুন
- Auto validation (discount price < base price)

### 4. **Quantity-wise Tier Pricing**

Bulk order এর জন্য different prices:

**Example:**

- 1-10 units: ৳100 each
- 11-50 units: ৳90 each
- 51+ units: ৳80 each

**Features:**

- Multiple tiers add করুন
- Bulk products এ apply করুন
- Preview tier structure
- Remove/clear tiers

### 5. **AI Assistant Features** 🤖

#### AI Price Suggestions

- Selected products এর জন্য optimal pricing strategy
- Market trends analysis
- Profit margin considerations

#### AI Price Optimization

- Performance data based optimization
- Conversion rate analysis
- Stock level considerations
- Sales velocity analysis

#### AI Market Analysis

- Competitive pricing analysis
- Market positioning suggestions
- Bangladesh market specific recommendations

### 6. **Enhanced Filters**

- Search by name/SKU
- Filter by category
- Price range filter
- Status filter (active/inactive)
- **NEW**: Discount status filter (with/without discount)

### 7. **Live Preview**

- Real-time price change preview
- Visual comparison (old ↔ new price)
- Color-coded changes:
    - 🟢 Green: Price increase
    - 🔴 Red: Price decrease
    - ⚪ Gray: No change

### 8. **Export Functionality**

- Export current filtered products
- CSV format
- Includes:
    - Basic info (ID, Name, SKU, Category)
    - All prices (base, discount, tiers)
    - Ready for Excel/Google Sheets

## How to Use

### Basic Price Update

1. Select products (checkbox)
2. Choose "Basic Pricing" tab
3. Select price field (base_price or discount_price)
4. Choose update method:
    - Fixed: Enter new price
    - Percentage: Select increase/decrease & enter %
    - Amount: Select add/subtract & enter amount
5. Click "Preview Changes" to see
6. Click "Update Selected Products"

### Apply Discounts

1. Select products
2. Go to "Discount Pricing" tab
3. Choose discount type
4. Enter discount value
5. Click "Apply Discount to Selected"

### Add Tier Pricing

1. Select products
2. Go to "Tier Pricing" tab
3. Add tiers:
    - Min quantity (e.g., 10)
    - Max quantity (e.g., 50 or leave blank for ∞)
    - Price per unit
4. Click "Add Tier"
5. Repeat for multiple tiers
6. Click "Apply Tiers to Selected"

### Use AI Assistant

1. Select products আপনি analyze করতে চান
2. Click one of:
    - "Get AI Suggestions": General pricing advice
    - "Optimize Prices": Performance-based optimization
    - "Market Analysis": Competitive analysis
3. AI suggestion box এ result দেখুন

### Export Prices

1. Apply filters যদি দরকার হয়
2. Click "Export Prices" button
3. CSV file download হবে

## Technical Details

### Updated Files

1. **Blade View**: `resources/views/admin/products/bulk-price-update.blade.php`
    - Enhanced UI with tabs
    - AI assistant integration
    - Live preview system
    - Better filters

2. **Controller**: `app/Http/Controllers/Admin/BulkProductPriceController.php`
    - Fixed percentage decrease bug
    - Added price_field parameter
    - New methods:
        - `applyDiscount()`
        - `removeDiscount()`
        - `applyTiers()`
        - `aiSuggest()`
        - `aiOptimize()`
        - `aiMarket()`
        - `exportPrices()`

3. **Routes**: `routes/web.php`
    - Added 8 new routes
    - All under `admin.bulk-price.*` namespace

### Bug Fixes

✅ **Fixed: Percentage Decrease Not Working**

- Added `percentage_direction` parameter
- Separate handling for increase/decrease
- Formula:
    - Increase: `price * (1 + percentage/100)`
    - Decrease: `price * (1 - percentage/100)`

### New Database Interactions

- Updates `base_price` or `discount_price`
- Creates/updates `product_prices` table (tier pricing)
- Auto-validation for discount < base price

### AI Integration

- Uses existing `AIService` class
- Supports both Gemini and Groq
- Configurable in `config/ai.php`
- Prompts tailored for pricing analysis

## Requirements

### For AI Features

আপনার `.env` file এ একটি AI provider configure করতে হবে:

```env
# Option 1: Gemini (Recommended)
AI_DEFAULT_PROVIDER=gemini
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_MODEL=gemini-2.5-flash

# Option 2: Groq
AI_DEFAULT_PROVIDER=groq
GROQ_API_KEY=your_groq_api_key_here
GROQ_MODEL=llama-3.3-70b-versatile
```

### For Export

- Ensure `storage/app/exports` directory is writable
- Auto-created if not exists

## Benefits

### Time Saving

- ⏱️ Bulk update হাজারো products একসাথে
- 🎯 Filter করে specific products select করুন
- 👁️ Preview before final update

### Accuracy

- ✅ Real-time calculation
- ✅ Visual comparison
- ✅ Auto-validation rules
- ✅ No manual calculation errors

### Smart Pricing

- 🤖 AI-powered suggestions
- 📊 Data-driven decisions
- 💰 Optimize profit margins
- 🏆 Stay competitive

### Business Intelligence

- 📈 Market analysis
- 🎯 Performance-based optimization
- 💡 Strategic pricing recommendations

## Advanced Usage

### Combining Features

1. **AI + Bulk Update:**
    - Get AI suggestions first
    - Review recommendations
    - Apply to products using bulk update

2. **Discount + Tier Pricing:**
    - Set discount_price for retail
    - Add tier pricing for wholesale
    - Automatic best price selection for customers

3. **Filter + Export:**
    - Apply complex filters
    - Export for external analysis
    - Reimport after review

### Best Practices

1. Always preview changes before applying
2. Use AI suggestions for new categories
3. Regular market analysis (monthly)
4. Export backup before major updates
5. Start with small batches for testing

## Troubleshooting

### AI Features Not Working

- Check `.env` for API keys
- Verify AI service is enabled in config
- Check logs: `storage/logs/laravel.log`

### Tier Pricing Not Showing

- Ensure products have tier prices set
- Check `product_prices` table
- Verify relationship in Product model

### Export Failing

- Check storage permissions
- Ensure enough disk space
- Verify export directory exists

## Support

যদি কোনো সমস্যা হয় বা additional features চান:

- Check error logs
- Review documentation
- Test with small dataset first

---

**Version:** 2.0  
**Last Updated:** March 2026  
**Status:** ✅ Production Ready
