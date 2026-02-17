<?php

namespace App\Services;

use App\Models\DropshippingApiLog;
use App\Models\DropshippingProduct;
use App\Models\DropshippingSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Exception;

class CJDropshippingService
{
    private $apiUrl;
    private $apiKey;

    private const ACCESS_TOKEN_CACHE_KEY = 'cj_access_token';
    private const REFRESH_TOKEN_CACHE_KEY = 'cj_refresh_token';

    public function __construct()
    {
        $this->apiUrl = DropshippingSetting::getSetting('cj_api_url', 'https://api.cjdropshipping.com');
        $this->apiKey = DropshippingSetting::getSetting('cj_api_key');
    }

    /**
     * Check if CJ API is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Search products on CJ
     */
    public function searchProducts($keyword = null, $page = 1, $limit = 20, array $options = [])
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/product/listV2';
        $params = [
            'page' => $page,
            'size' => $limit,
        ];

        if (!empty($keyword)) {
            $params['keyWord'] = $keyword;
        }

        if (!empty($options)) {
            $params = array_merge($params, $options);
        }

        $raw = $this->makeApi2Request('GET', $endpoint, $params);
        return $this->normalizeSearchResults($raw);
    }

    public function getSuggestedProducts($page = 1, $limit = 20)
    {
        $results = $this->searchProducts(null, $page, $limit, [
            'orderBy' => 1,
            'sort' => 'desc',
            'productFlag' => 0,
        ]);

        if (!empty($results)) {
            return $results;
        }

        $fallback = $this->makeApi2Request('GET', '/product/list', [
            'pageNum' => $page,
            'pageSize' => $limit,
        ]);

        return $this->normalizeProductListResults($fallback);
    }

    /**
     * Get product details from CJ
     */
    public function getProductDetails($cjProductId)
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/product/query';
        $params = [
            'pid' => $cjProductId,
        ];

        $raw = $this->makeApi2Request('GET', $endpoint, $params);
        return $this->normalizeProductDetail($raw);
    }

    /**
     * Get product prices from CJ
     */
    public function getProductPrices($cjProductIds = [])
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/api/stock/price';
        $params = [
            'productIds' => $cjProductIds,
        ];

        return $this->makeRequest('POST', $endpoint, $params);
    }

    /**
     * Get product inventory from CJ
     */
    public function getProductInventory($cjProductId)
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/api/stock/inventory';
        $params = [
            'productId' => $cjProductId,
        ];

        return $this->makeRequest('POST', $endpoint, $params);
    }

    /**
     * Create order on CJ
     */
    public function createOrder(array $orderData)
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/api/order/add';

        // Format order data according to CJ API requirements
        $params = [
            'shopName' => $orderData['shop_name'] ?? config('app.name'),
            'orderNumber' => $orderData['order_number'],
            'buyerEmail' => $orderData['buyer_email'],
            'buyerPhone' => $orderData['shipping_phone'],
            'productList' => $this->formatOrderProducts($orderData['products']),
            'shippingAddress' => $this->formatShippingAddress($orderData['shipping_address']),
            'deliveryTimeType' => $orderData['delivery_time_type'] ?? 1, // Default: Regular
        ];

        return $this->makeRequest('POST', $endpoint, $params);
    }

    /**
     * Get order status from CJ
     */
    public function getOrderStatus($cjOrderNumber)
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/api/order/status';
        $params = [
            'orderNumber' => $cjOrderNumber,
        ];

        return $this->makeRequest('POST', $endpoint, $params);
    }

    /**
     * Get order tracking information from CJ
     */
    public function getOrderTracking($cjOrderNumber)
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/api/order/tracking';
        $params = [
            'orderNumber' => $cjOrderNumber,
        ];

        return $this->makeRequest('POST', $endpoint, $params);
    }

    /**
     * Cancel order on CJ
     */
    public function cancelOrder($cjOrderNumber, $reason = '')
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $endpoint = '/api/order/cancel';
        $params = [
            'orderNumber' => $cjOrderNumber,
            'reason' => $reason,
        ];

        return $this->makeRequest('POST', $endpoint, $params);
    }

    /**
     * Format products for order creation
     */
    private function formatOrderProducts($products): array
    {
        $formatted = [];
        foreach ($products as $product) {
            $formatted[] = [
                'productId' => $product['cj_product_id'],
                'quantity' => $product['quantity'],
                'sku' => $product['sku'] ?? '',
                'productPrice' => $product['unit_cost_price'],
            ];
        }
        return $formatted;
    }

    /**
     * Format shipping address for CJ API
     */
    private function formatShippingAddress($address): array
    {
        return [
            'name' => $address['name'] ?? $address['shipping_name'],
            'phone' => $address['phone'] ?? $address['shipping_phone'],
            'email' => $address['email'] ?? $address['shipping_email'] ?? '',
            'country' => $address['country'] ?? 'Bangladesh',
            'province' => $address['district'] ?? $address['shipping_district'] ?? '',
            'city' => $address['upazila'] ?? $address['shipping_upazila'] ?? '',
            'address' => $address['address'] ?? $address['shipping_address'] ?? '',
            'postalCode' => $address['postal_code'] ?? '',
        ];
    }

    /**
     * Make API request to CJ
     */
    private function makeRequest($method, $endpoint, $data = [])
    {
        try {
            $url = rtrim($this->getLegacyBaseUrl(), '/') . $endpoint;

            $accessToken = $this->getAccessToken();
            $client = Http::timeout(30)->withHeaders([
                'CJ-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ]);

            $response = match (strtoupper($method)) {
                'POST' => $client->post($url, $data),
                'GET' => $client->get($url, $data),
                'PUT' => $client->put($url, $data),
                'DELETE' => $client->delete($url, $data),
                default => throw new Exception('Invalid HTTP method'),
            };

            $responseData = $response->json();
            $statusCode = $response->status();
            $success = $response->ok();

            // Log the request
            $this->logApiCall($endpoint, $method, $data, $responseData, $statusCode, $success);

            // Check CJ API response
            if (isset($responseData['result']) && $responseData['result'] === false) {
                throw new Exception($responseData['message'] ?? 'CJ API returned an error');
            }

            if (isset($responseData['code']) && (int) $responseData['code'] !== 200) {
                throw new Exception($responseData['message'] ?? 'CJ API returned an error');
            }

            return $responseData['data'] ?? $responseData;
        } catch (Exception $e) {
            $this->logApiCall($endpoint, $method, $data, null, null, false, $e->getMessage());
            throw $e;
        }
    }

    private function makeApi2Request($method, $endpoint, $data = [])
    {
        try {
            $url = rtrim($this->getApi2BaseUrl(), '/') . $endpoint;

            $accessToken = $this->getAccessToken();
            $client = Http::timeout(30)->withHeaders([
                'CJ-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ]);

            $response = match (strtoupper($method)) {
                'POST' => $client->post($url, $data),
                'GET' => $client->get($url, $data),
                'PUT' => $client->put($url, $data),
                'DELETE' => $client->delete($url, $data),
                default => throw new Exception('Invalid HTTP method'),
            };

            $responseData = $response->json();
            $statusCode = $response->status();
            $success = $response->ok();

            $this->logApiCall($endpoint, $method, $data, $responseData, $statusCode, $success);

            if (isset($responseData['result']) && $responseData['result'] === false) {
                throw new Exception($responseData['message'] ?? 'CJ API returned an error');
            }

            if (isset($responseData['code']) && (int) $responseData['code'] !== 200) {
                throw new Exception($responseData['message'] ?? 'CJ API returned an error');
            }

            return $responseData['data'] ?? $responseData;
        } catch (Exception $e) {
            $this->logApiCall($endpoint, $method, $data, null, null, false, $e->getMessage());
            throw $e;
        }
    }

    private function getLegacyBaseUrl(): string
    {
        return rtrim($this->apiUrl, '/');
    }

    private function getApi2BaseUrl(): string
    {
        $base = rtrim($this->apiUrl, '/');

        if (str_contains($base, '/api2.0/v1')) {
            return $base;
        }

        if (str_contains($base, 'developers.cjdropshipping.com') || str_contains($base, 'developers.cjdropshipping.cn')) {
            return $base . '/api2.0/v1';
        }

        return 'https://developers.cjdropshipping.com/api2.0/v1';
    }

    private function normalizeSearchResults($raw): array
    {
        $results = [];
        $content = $raw['content'] ?? [];

        foreach ($content as $block) {
            $productList = $block['productList'] ?? [];
            foreach ($productList as $product) {
                $results[] = [
                    'id' => $product['id'] ?? null,
                    'name' => $product['nameEn'] ?? $product['name'] ?? '',
                    'imageUrl' => $this->extractImageUrl($product),
                    'price' => $this->toFloat($product['nowPrice'] ?? $product['sellPrice'] ?? 0),
                    'stock' => (int) ($product['warehouseInventoryNum'] ?? $product['totalVerifiedInventory'] ?? 0),
                    'sku' => $product['sku'] ?? null,
                ];
            }
        }

        return $results;
    }

    private function normalizeProductListResults($raw): array
    {
        $results = [];
        $list = $raw['list'] ?? [];

        foreach ($list as $product) {
            $results[] = [
                'id' => $product['pid'] ?? null,
                'name' => $product['productNameEn'] ?? $product['productName'] ?? '',
                'imageUrl' => $this->extractImageUrl($product),
                'price' => $this->toFloat($product['sellPrice'] ?? 0),
                'stock' => 0,
                'sku' => $product['productSku'] ?? null,
            ];
        }

        return $results;
    }

    private function normalizeProductDetail($raw): array
    {
        $stock = (int) ($raw['totalInventoryNum'] ?? $raw['warehouseInventoryNum'] ?? 0);
        if ($stock === 0 && !empty($raw['variants'])) {
            $stock = $this->calculateStockFromVariants($raw['variants']);
        }

        return [
            'name' => $raw['productNameEn'] ?? $raw['productName'] ?? '',
            'description' => $raw['description'] ?? '',
            'price' => $this->toFloat($raw['sellPrice'] ?? $raw['nowPrice'] ?? 0),
            'category' => $raw['categoryName'] ?? '',
            'sub_category' => $raw['threeCategoryName'] ?? '',
            'imageUrl' => $this->extractImageUrl($raw),
            'sku' => $raw['productSku'] ?? '',
            'stock' => $stock,
            'minQuantity' => (int) ($raw['directMinOrderNum'] ?? 1),
            'attributes' => $raw['variants'] ?? null,
        ];
    }

    private function extractImageUrl(array $product): ?string
    {
        return $product['bigImage']
            ?? $product['productImage']
            ?? $product['image']
            ?? $product['imageUrl']
            ?? null;
    }

    private function calculateStockFromVariants(array $variants): int
    {
        $stock = 0;

        foreach ($variants as $variant) {
            $inventories = $variant['inventories'] ?? [];
            foreach ($inventories as $inventory) {
                if (isset($inventory['totalInventory'])) {
                    $stock += (int) $inventory['totalInventory'];
                    continue;
                }

                if (isset($inventory['totalInventoryNum'])) {
                    $stock += (int) $inventory['totalInventoryNum'];
                    continue;
                }

                if (isset($inventory['cjInventory'])) {
                    $stock += (int) $inventory['cjInventory'];
                }
            }
        }

        return $stock;
    }

    private function toFloat($value): float
    {
        return (float) (is_numeric($value) ? $value : 0);
    }

    /**
     * Get access token from CJ API (key-only auth)
     */
    public function getAccessToken(): string
    {
        if (!$this->isConfigured()) {
            throw new Exception('CJ Dropshipping API is not configured');
        }

        $cachedToken = Cache::get(self::ACCESS_TOKEN_CACHE_KEY);
        if (!empty($cachedToken)) {
            return $cachedToken;
        }

        $authUrl = rtrim($this->getAuthBaseUrl(), '/') . '/api2.0/v1/authentication/getAccessToken';
        $response = Http::timeout(30)->post($authUrl, [
            'apiKey' => $this->apiKey,
        ]);

        $responseData = $response->json();
        if (!isset($responseData['result']) || $responseData['result'] !== true) {
            throw new Exception($responseData['message'] ?? 'Failed to get CJ access token');
        }

        $tokenData = $responseData['data'] ?? [];
        $accessToken = $tokenData['accessToken'] ?? null;
        if (empty($accessToken)) {
            throw new Exception('CJ access token missing in response');
        }

        $accessTokenExpiry = $tokenData['accessTokenExpiryDate'] ?? null;
        $accessTokenTtl = 60 * 60 * 24 * 14;
        if (!empty($accessTokenExpiry)) {
            $expiresAt = Carbon::parse($accessTokenExpiry);
            $accessTokenTtl = max($expiresAt->diffInSeconds(now()), 300);
        }

        Cache::put(self::ACCESS_TOKEN_CACHE_KEY, $accessToken, $accessTokenTtl);

        if (!empty($tokenData['refreshToken'])) {
            $refreshTtl = 60 * 60 * 24 * 180;
            if (!empty($tokenData['refreshTokenExpiryDate'])) {
                $refreshExpiresAt = Carbon::parse($tokenData['refreshTokenExpiryDate']);
                $refreshTtl = max($refreshExpiresAt->diffInSeconds(now()), 300);
            }
            Cache::put(self::REFRESH_TOKEN_CACHE_KEY, $tokenData['refreshToken'], $refreshTtl);
        }

        return $accessToken;
    }

    private function getAuthBaseUrl(): string
    {
        return 'https://developers.cjdropshipping.com';
    }

    /**
     * Log API call
     */
    private function logApiCall(
        $endpoint,
        $method,
        $requestData,
        $responseData = null,
        $responseCode = null,
        $success = true,
        $errorMessage = null
    ) {
        DropshippingApiLog::create([
            'endpoint' => $endpoint,
            'method' => $method,
            'request_data' => $requestData,
            'response_data' => $responseData,
            'response_code' => $responseCode,
            'success' => $success,
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Sync product from CJ to local database
     */
    public function syncProduct($cjProductId, $sellingPrice = null, $imageUrl = null)
    {
        try {
            $productData = $this->getProductDetails($cjProductId);

            if (!$productData) {
                throw new Exception('Product not found on CJ');
            }

            $unitPrice = (float) ($productData['price'] ?? 0);
            $stock = max(0, (int) ($productData['stock'] ?? 0));

            // Calculate selling price if not provided
            if (!$sellingPrice) {
                $marginPercent = DropshippingSetting::getSetting('default_profit_margin_percent', 20);
                $sellingPrice = $unitPrice * (1 + ($marginPercent / 100));
            }

            $resolvedImageUrl = $productData['imageUrl'] ?? '';
            if (empty($resolvedImageUrl) && !empty($imageUrl)) {
                $resolvedImageUrl = $imageUrl;
            }

            $product = DropshippingProduct::updateOrCreate(
                ['cj_product_id' => $cjProductId],
                [
                    'name' => $productData['name'] ?? '',
                    'description' => $productData['description'] ?? '',
                    'unit_price' => $unitPrice,
                    'selling_price' => $sellingPrice,
                    'profit_margin' => $sellingPrice - $unitPrice,
                    'category' => $productData['category'] ?? '',
                    'sub_category' => $productData['sub_category'] ?? '',
                    'image_url' => $resolvedImageUrl,
                    'sku' => $productData['sku'] ?? '',
                    'stock' => $stock,
                    'minimum_order_quantity' => $productData['minQuantity'] ?? 1,
                    'product_attributes' => isset($productData['attributes']) ? json_encode($productData['attributes']) : null,
                    'is_available' => $stock > 0,
                    'is_active' => true,
                    'cj_response_data' => $productData,
                ]
            );

            return $product;
        } catch (Exception $e) {
            \Log::error('CJ Product Sync Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
