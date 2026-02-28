<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configure AI services for the admin panel. Supports Gemini and Groq APIs.
    |
    */

    'default_provider' => env('AI_DEFAULT_PROVIDER', 'gemini'),

    'providers' => [
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
            'endpoint' => 'https://generativelanguage.googleapis.com/v1/models/',
            'max_tokens' => env('GEMINI_MAX_TOKENS', 2048),
            'temperature' => env('GEMINI_TEMPERATURE', 0.7),
        ],
        'groq' => [
            'api_key' => env('GROQ_API_KEY'),
            'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
            'endpoint' => 'https://api.groq.com/openai/v1/chat/completions',
            'max_tokens' => env('GROQ_MAX_TOKENS', 2048),
            'temperature' => env('GROQ_TEMPERATURE', 0.7),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Features Configuration
    |--------------------------------------------------------------------------
    */

    'features' => [
        'product_description' => true,
        'category_description' => true,
        'sales_analysis' => true,
        'seo_optimization' => true,
        'chat_assistant' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Prompt Templates
    |--------------------------------------------------------------------------
    */

    'prompts' => [
        'product_description' => [
            'bn' => "তুমি একজন বাংলাদেশী ই-কমার্স এক্সপার্ট। নিচের প্রোডাক্টের জন্য একটি আকর্ষণীয় বাংলায় বিবরণ লেখ:\n\nপ্রোডাক্ট: {name}\nক্যাটাগরি: {category}\nমূল্য: ৳{price}\n\nবিবরণে নিচের বিষয়গুলো অন্তর্ভুক্ত কর:\n- প্রোডাক্টের সুবিধা\n- ব্যবহারের টিপস\n- কেন এটি কিনবেন",
            'en' => "You are an e-commerce expert. Write an attractive product description for:\n\nProduct: {name}\nCategory: {category}\nPrice: ৳{price}\n\nInclude:\n- Key benefits\n- Usage tips\n- Why customers should buy",
        ],
        'category_description' => [
            'bn' => "তুমি একজন বাংলাদেশী ই-কমার্স SEO এক্সপার্ট। নিচের ক্যাটাগরির জন্য একটি SEO-ফ্রেন্ডলি বাংলায় বিবরণ লেখ:\n\nক্যাটাগরি: {name}\nসাবক্যাটাগরি: {subcategories}\n\nবিবরণটি 150-200 শব্দের মধ্যে রাখ এবং কীওয়ার্ড অপ্টিমাইজড কর।",
            'en' => "You are an e-commerce SEO expert. Write an SEO-friendly description for:\n\nCategory: {name}\nSubcategories: {subcategories}\n\nKeep description between 150-200 words and keyword optimized.",
        ],
        'sales_analysis' => [
            'bn' => "তুমি একজন বিজনেস অ্যানালিস্ট। নিচের সেলস ডাটা বিশ্লেষণ কর এবং বাংলায় সুপারিশ দাও:\n\n{sales_data}\n\nবিশ্লেষণে অন্তর্ভুক্ত কর:\n- টপ সেলিং প্রোডাক্ট\n- ট্রেন্ড বিশ্লেষণ\n- কোন প্রোডাক্টে ফোকাস করা উচিত\n- স্টক ম্যানেজমেন্ট সুপারিশ",
            'en' => "You are a business analyst. Analyze the following sales data and provide recommendations:\n\n{sales_data}\n\nInclude:\n- Top selling products\n- Trend analysis\n- Which products to focus on\n- Stock management recommendations",
        ],
        'seo_meta' => [
            'bn' => "তুমি একজন SEO এক্সপার্ট। নিচের প্রোডাক্টের জন্য বাংলায় SEO মেটা ডাটা তৈরি কর:\n\nপ্রোডাক্ট: {name}\nবিবরণ: {description}\n\nJSON ফরম্যাটে দাও:\n- meta_title (60 অক্ষরের মধ্যে)\n- meta_description (160 অক্ষরের মধ্যে)\n- meta_keywords (5-10 কীওয়ার্ড)",
            'en' => "You are an SEO expert. Create SEO meta data for:\n\nProduct: {name}\nDescription: {description}\n\nReturn in JSON:\n- meta_title (max 60 chars)\n- meta_description (max 160 chars)\n- meta_keywords (5-10 keywords)",
        ],
    ],
];
