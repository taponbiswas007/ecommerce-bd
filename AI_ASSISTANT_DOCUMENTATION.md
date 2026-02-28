# AI Assistant Documentation

## Overview

AI Assistant হলো একটি powerful tool যা admin panel এ integrated। এটি Gemini এবং Groq API ব্যবহার করে বিভিন্ন AI-powered features প্রদান করে।

## Features

### 1. 💬 AI Chat Assistant

- Admin-কে business decisions নিতে সাহায্য করে
- বাংলা এবং English দুই ভাষাতেই কথা বলতে পারে
- Sales, products, marketing নিয়ে প্রশ্ন করা যায়
- Business context automatically include করা যায়

### 2. 📝 Product Description Generator

- Attractive product descriptions generate করে
- Bangla এবং English support
- Directly product এ apply করা যায়
- Short এবং Full description আলাদাভাবে generate করা যায়

### 3. 🏷️ Category Description Generator

- SEO-friendly category descriptions
- Subcategories include করে description generate করে
- Directly database এ save করা যায়

### 4. 📊 Sales Analysis

- AI-powered sales insights
- Period selection (week, month, quarter, year)
- Top products, category performance, stock recommendations
- Marketing suggestions

### 5. 💡 Product Recommendations

- কোন product promote করা উচিত
- Stock management advice
- Category focus suggestions
- Marketing strategy tips

### 6. 🔍 SEO Generator

- Meta title, description, keywords generate করে
- Google search preview
- Directly product এ apply করা যায়

## Configuration

### Environment Variables (.env)

```env
# AI Configuration
AI_DEFAULT_PROVIDER=gemini

# Google Gemini API
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-pro
GEMINI_MAX_TOKENS=2048
GEMINI_TEMPERATURE=0.7

# Groq API (Llama)
GROQ_API_KEY=your_groq_api_key
GROQ_MODEL=llama-3.3-70b-versatile
GROQ_MAX_TOKENS=2048
GROQ_TEMPERATURE=0.7
```

### Getting API Keys

1. **Gemini API Key**: https://makersuite.google.com/app/apikey
2. **Groq API Key**: https://console.groq.com/keys

## File Structure

```
app/
├── Http/Controllers/Admin/
│   └── AIController.php
├── Services/AI/
│   └── AIService.php
config/
└── ai.php
resources/views/admin/ai/
├── index.blade.php
├── chat.blade.php
├── product-description.blade.php
├── category-description.blade.php
├── sales-analysis.blade.php
├── recommendations.blade.php
├── seo-generator.blade.php
└── settings.blade.php
```

## Routes

| Route                                     | Method | Description                    |
| ----------------------------------------- | ------ | ------------------------------ |
| `/admin/ai`                               | GET    | AI Dashboard                   |
| `/admin/ai/chat`                          | GET    | AI Chat Interface              |
| `/admin/ai/chat/send`                     | POST   | Send chat message              |
| `/admin/ai/product-description`           | GET    | Product description generator  |
| `/admin/ai/product-description/generate`  | POST   | Generate description           |
| `/admin/ai/product-description/apply`     | POST   | Apply to product               |
| `/admin/ai/category-description`          | GET    | Category description generator |
| `/admin/ai/category-description/generate` | POST   | Generate description           |
| `/admin/ai/category-description/apply`    | POST   | Apply to category              |
| `/admin/ai/sales-analysis`                | GET    | Sales analysis page            |
| `/admin/ai/sales-analysis/analyze`        | POST   | Get AI analysis                |
| `/admin/ai/recommendations`               | GET    | Recommendations page           |
| `/admin/ai/recommendations/get`           | POST   | Get recommendations            |
| `/admin/ai/seo-generator`                 | GET    | SEO generator page             |
| `/admin/ai/seo-generator/generate`        | POST   | Generate SEO                   |
| `/admin/ai/seo-generator/apply`           | POST   | Apply SEO to product           |
| `/admin/ai/settings`                      | GET    | AI settings                    |
| `/admin/ai/test-connection`               | POST   | Test API connection            |

## Usage Guide

### 1. Access AI Assistant

- Login as admin
- Click "AI Assistant" in the sidebar
- Choose the feature you need

### 2. Generate Product Description

1. Go to AI Assistant > Product Description
2. Select a product or enter manually
3. Choose language (Bangla/English)
4. Choose AI provider (Gemini/Groq)
5. Click "Generate Description"
6. Review and apply to product

### 3. Analyze Sales

1. Go to AI Assistant > Sales Analysis
2. Select time period
3. Choose language and provider
4. Click "Analyze Sales"
5. Review AI insights

### 4. Chat with AI

1. Go to AI Assistant > AI Chat
2. Type your question (বাংলায় বা English এ)
3. Enable "Include Business Context" for better answers
4. Send and get response

## API Providers

### Gemini (Google)

- Model: gemini-pro
- Good for: General content generation
- Speed: Fast
- Quality: High

### Groq (Llama)

- Model: llama-3.3-70b-versatile
- Good for: Quick responses
- Speed: Very fast
- Quality: Good

## Tips

1. **Better Descriptions**: Include detailed product information for better AI-generated descriptions
2. **Language Selection**: Use Bangla for local customers, English for SEO
3. **Provider Selection**: Try both providers and choose the one that gives better results
4. **Context**: Enable business context in chat for more relevant answers
5. **Review**: Always review AI-generated content before applying

## Troubleshooting

### API Connection Failed

1. Check API key in .env file
2. Clear config cache: `php artisan config:clear`
3. Test connection in AI Settings

### No Results

1. Check internet connection
2. Verify API quota/limits
3. Try different provider

### Slow Response

1. Groq is faster than Gemini
2. Reduce max_tokens
3. Check server resources

## Support

For issues or feature requests, contact the development team.

---

**Version**: 1.0.0  
**Last Updated**: February 2026
