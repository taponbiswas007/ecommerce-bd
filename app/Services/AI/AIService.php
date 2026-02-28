<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class AIService
{
    protected string $provider;
    protected array $config;

    public function __construct(?string $provider = null)
    {
        $this->provider = $provider ?? config('ai.default_provider', 'gemini');
        $this->config = config("ai.providers.{$this->provider}");
    }

    /**
     * Set AI provider
     */
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
        $this->config = config("ai.providers.{$provider}");
        return $this;
    }

    /**
     * Get current provider
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * Generate text using AI
     */
    public function generate(string $prompt, array $options = []): array
    {
        try {
            if ($this->provider === 'gemini') {
                return $this->generateWithGemini($prompt, $options);
            } elseif ($this->provider === 'groq') {
                return $this->generateWithGroq($prompt, $options);
            }

            throw new Exception("Unknown AI provider: {$this->provider}");
        } catch (Exception $e) {
            Log::error('AI Generation Error', [
                'provider' => $this->provider,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'content' => null,
            ];
        }
    }

    /**
     * Generate with Gemini API
     */
    protected function generateWithGemini(string $prompt, array $options = []): array
    {
        $model = $options['model'] ?? $this->config['model'];
        $maxTokens = $options['max_tokens'] ?? $this->config['max_tokens'];
        $temperature = $options['temperature'] ?? $this->config['temperature'];

        // Gemini 1.5 uses v1 endpoint and model name without :generateContent
        $url = $this->config['endpoint'] . $model . ':generateContent?key=' . $this->config['api_key'];
        // For v1, model name is gemini-1.5-flash (or gemini-1.5-pro)

        $response = Http::timeout(60)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $temperature,
                'maxOutputTokens' => $maxTokens,
                'topP' => 0.95,
                'topK' => 64,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            // Gemini 1.5 response format
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? ($data['candidates'][0]['content']['text'] ?? null);

            return [
                'success' => true,
                'content' => $content,
                'provider' => 'gemini',
                'model' => $model,
                'raw_response' => $data,
            ];
        }

        throw new Exception('Gemini API Error: ' . $response->body());
    }

    /**
     * Generate with Groq API
     */
    protected function generateWithGroq(string $prompt, array $options = []): array
    {
        $model = $options['model'] ?? $this->config['model'];
        $maxTokens = (int)($options['max_tokens'] ?? $this->config['max_tokens']);
        $temperature = $options['temperature'] ?? $this->config['temperature'];

        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->config['api_key'],
                'Content-Type' => 'application/json',
            ])
            ->post($this->config['endpoint'], [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ]
                ],
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            return [
                'success' => true,
                'content' => $content,
                'provider' => 'groq',
                'model' => $model,
                'raw_response' => $data,
            ];
        }

        throw new Exception('Groq API Error: ' . $response->body());
    }

    /**
     * Generate product description
     */
    public function generateProductDescription(array $product, string $language = 'bn'): array
    {
        $template = config("ai.prompts.product_description.{$language}");

        $prompt = str_replace(
            ['{name}', '{category}', '{price}'],
            [$product['name'], $product['category'] ?? 'General', $product['price'] ?? 0],
            $template
        );

        return $this->generate($prompt);
    }

    /**
     * Generate category description
     */
    public function generateCategoryDescription(array $category, string $language = 'bn'): array
    {
        $template = config("ai.prompts.category_description.{$language}");

        $subcategories = $category['subcategories'] ?? 'None';
        if (is_array($subcategories)) {
            $subcategories = implode(', ', $subcategories);
        }

        $prompt = str_replace(
            ['{name}', '{subcategories}'],
            [$category['name'], $subcategories],
            $template
        );

        return $this->generate($prompt);
    }

    /**
     * Analyze sales data
     */
    public function analyzeSales(array $salesData, string $language = 'bn'): array
    {
        $template = config("ai.prompts.sales_analysis.{$language}");

        // Format sales data for prompt
        $formattedData = $this->formatSalesDataForPrompt($salesData);

        $prompt = str_replace('{sales_data}', $formattedData, $template);

        return $this->generate($prompt);
    }

    /**
     * Generate SEO meta data
     */
    public function generateSeoMeta(array $product, string $language = 'bn'): array
    {
        $template = config("ai.prompts.seo_meta.{$language}");

        $prompt = str_replace(
            ['{name}', '{description}'],
            [$product['name'], $product['description'] ?? ''],
            $template
        );

        $result = $this->generate($prompt);

        if ($result['success'] && $result['content']) {
            // Try to parse JSON from response
            preg_match('/\{[^}]+\}/s', $result['content'], $matches);
            if (!empty($matches[0])) {
                $result['parsed_meta'] = json_decode($matches[0], true);
            }
        }

        return $result;
    }

    /**
     * Chat with AI assistant
     */
    public function chat(string $message, array $context = []): array
    {
        $systemPrompt = "তুমি একজন বাংলাদেশী ই-কমার্স বিজনেস এসিস্ট্যান্ট। তুমি বাংলা ও ইংরেজি দুই ভাষাতেই উত্তর দিতে পার। তোমার কাজ হলো অ্যাডমিনকে প্রোডাক্ট ম্যানেজমেন্ট, সেলস অ্যানালাইসিস, মার্কেটিং স্ট্র্যাটেজি এবং বিজনেস ডিসিশনে সাহায্য করা।";

        if (!empty($context)) {
            $systemPrompt .= "\n\nContext:\n" . json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        $fullPrompt = $systemPrompt . "\n\nUser: " . $message . "\n\nAssistant:";

        return $this->generate($fullPrompt);
    }

    /**
     * Format sales data for AI analysis
     */
    protected function formatSalesDataForPrompt(array $data): string
    {
        $formatted = [];

        if (isset($data['top_products'])) {
            $formatted[] = "Top Products:";
            foreach ($data['top_products'] as $index => $product) {
                $formatted[] = ($index + 1) . ". {$product['name']} - Sold: {$product['sold_count']}, Revenue: ৳{$product['revenue']}";
            }
        }

        if (isset($data['category_performance'])) {
            $formatted[] = "\nCategory Performance:";
            foreach ($data['category_performance'] as $category) {
                $formatted[] = "- {$category['name']}: {$category['total_sold']} items, ৳{$category['revenue']}";
            }
        }

        if (isset($data['monthly_sales'])) {
            $formatted[] = "\nMonthly Sales:";
            foreach ($data['monthly_sales'] as $month => $amount) {
                $formatted[] = "- {$month}: ৳{$amount}";
            }
        }

        if (isset($data['low_stock'])) {
            $formatted[] = "\nLow Stock Products:";
            foreach ($data['low_stock'] as $product) {
                $formatted[] = "- {$product['name']}: {$product['stock']} remaining";
            }
        }

        return implode("\n", $formatted);
    }

    /**
     * Get product recommendations based on sales data
     */
    public function getProductRecommendations(array $salesData, string $language = 'bn'): array
    {
        $prompt = $language === 'bn'
            ? "তুমি একজন ই-কমার্স এক্সপার্ট। নিচের সেলস ডাটা দেখে বিশ্লেষণ কর:\n\n{$this->formatSalesDataForPrompt($salesData)}\n\nএখন বলো:\n1. কোন প্রোডাক্টগুলো বেশি প্রমোট করা উচিত এবং কেন?\n2. কোন প্রোডাক্ট স্টক বাড়ানো উচিত?\n3. কোন ক্যাটাগরিতে বেশি ফোকাস করা উচিত?\n4. মার্কেটিং স্ট্র্যাটেজি কি হওয়া উচিত?"
            : "You are an e-commerce expert. Analyze the following sales data:\n\n{$this->formatSalesDataForPrompt($salesData)}\n\nProvide:\n1. Which products should be promoted more and why?\n2. Which products need stock increase?\n3. Which categories to focus on?\n4. Marketing strategy recommendations?";

        return $this->generate($prompt);
    }

    /**
     * Test API connection
     */
    public function testConnection(): array
    {
        try {
            $result = $this->generate("Say 'Hello, I am working!' in one line.");

            return [
                'success' => $result['success'],
                'provider' => $this->provider,
                'message' => $result['success'] ? 'Connection successful!' : 'Connection failed',
                'response' => $result['content'] ?? null,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'provider' => $this->provider,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }
}
