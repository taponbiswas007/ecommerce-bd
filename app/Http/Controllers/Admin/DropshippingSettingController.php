<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropshippingSetting;
use App\Services\CJDropshippingService;
use Illuminate\Http\Request;

class DropshippingSettingController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $settings = DropshippingSetting::pluck('value', 'key')->toArray();
        return view('admin.dropshipping.settings', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'cj_api_key' => 'nullable|string',
            'cj_api_secret' => 'nullable|string',
            'cj_api_url' => 'required|url',
            'enable_dropshipping' => 'nullable|boolean',
            'auto_confirm_orders' => 'nullable|boolean',
            'default_profit_margin_percent' => 'required|numeric|min:0|max:500',
        ]);

        $validated['enable_dropshipping'] = $request->boolean('enable_dropshipping');
        $validated['auto_confirm_orders'] = $request->boolean('auto_confirm_orders');

        $apiKey = trim((string) ($validated['cj_api_key'] ?? ''));
        if ($apiKey === '') {
            if (!DropshippingSetting::getSetting('cj_api_key')) {
                return redirect()->back()->withInput()->with('error', 'CJ API Key is required.');
            }
            unset($validated['cj_api_key']);
        } else {
            $validated['cj_api_key'] = $apiKey;
        }

        if (empty($validated['cj_api_secret'])) {
            unset($validated['cj_api_secret']);
        }

        foreach ($validated as $key => $value) {
            DropshippingSetting::setSetting($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    /**
     * Test API connection
     */
    public function testConnection(Request $request)
    {
        try {
            $cjService = new CJDropshippingService();

            if (!$cjService->isConfigured()) {
                return response()->json([
                    'success' => false,
                    'message' => 'API credentials are not configured'
                ]);
            }

            $cjService->getAccessToken();

            return response()->json([
                'success' => true,
                'message' => 'API connection successful'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'API connection failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get dropshipping statistics
     */
    public function getStats()
    {
        try {
            $totalProducts = \App\Models\DropshippingProduct::active()->count();
            $totalOrders = \App\Models\DropshippingOrder::count();
            $totalProfit = \App\Models\DropshippingOrder::sum('profit') ?? 0;

            return response()->json([
                'products' => $totalProducts,
                'orders' => $totalOrders,
                'profit' => number_format($totalProfit, 2) . ' ৳'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'products' => 0,
                'orders' => 0,
                'profit' => '0 ৳'
            ], 500);
        }
    }
}
