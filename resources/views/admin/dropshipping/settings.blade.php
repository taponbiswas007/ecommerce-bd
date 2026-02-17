@extends('admin.layouts.master')

@section('title', 'Dropshipping Settings')
@section('page-title', 'Dropshipping Settings')
@section('page-subtitle', 'Configure CJ Dropshipping API credentials')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Dropshipping Settings</li>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">CJ API Configuration</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.dropshipping.settings.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">CJ API Key <span class="text-danger">*</span></label>
                            <input type="password" name="cj_api_key" class="form-control"
                                value="{{ $settings['cj_api_key'] ?? '' }}" required>
                            <small class="text-muted">Your CJ Dropshipping API Key</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CJ API Secret <span class="text-muted">(optional)</span></label>
                            <input type="password" name="cj_api_secret" class="form-control"
                                value="{{ $settings['cj_api_secret'] ?? '' }}">
                            <small class="text-muted">Only required if your CJ account provides a secret</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CJ API URL <span class="text-danger">*</span></label>
                            <input type="url" name="cj_api_url" class="form-control"
                                value="{{ $settings['cj_api_url'] ?? 'https://api.cjdropshipping.com' }}" required>
                            <small class="text-muted">Use https://developers.cjdropshipping.com for API2</small>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Enable Dropshipping</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="enable_dropshipping"
                                    id="enableDropshipping" value="1"
                                    {{ ($settings['enable_dropshipping'] ?? '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="enableDropshipping">
                                    Enable dropshipping on frontend
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Auto-Confirm Orders</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="auto_confirm_orders" id="autoConfirm"
                                    value="1" {{ ($settings['auto_confirm_orders'] ?? '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoConfirm">
                                    Automatically confirm orders on CJ
                                </label>
                            </div>
                            <small class="text-muted">If enabled, submitted orders will be auto-confirmed</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Default Profit Margin <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="default_profit_margin_percent" class="form-control"
                                    value="{{ $settings['default_profit_margin_percent'] ?? '20' }}" min="0"
                                    max="500" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Default profit margin percentage when importing new
                                products</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <button type="button" class="btn btn-outline-info" id="testConnection">
                                <i class="fas fa-plug"></i> Test Connection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Section -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Getting Started</h6>
                </div>
                <div class="card-body small">
                    <h6>How to get API Credentials:</h6>
                    <ol class="ps-3">
                        <li>Log in to your CJ Dropshipping account</li>
                        <li>Go to Settings → API Settings</li>
                        <li>Generate API Key</li>
                        <li>Copy the API Key here</li>
                        <li>Click "Test Connection" to verify</li>
                    </ol>
                </div>
            </div>

            <!-- API Status -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">API Status</h6>
                </div>
                <div class="card-body">
                    <div id="apiStatus" class="alert alert-info text-center">
                        <i class="fas fa-spinner fa-spin"></i> Checking...
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body small">
                    <div class="mb-2">
                        <strong>Total Products:</strong> <span id="totalProducts">-</span>
                    </div>
                    <div class="mb-2">
                        <strong>Total Orders:</strong> <span id="totalOrders">-</span>
                    </div>
                    <div class="mb-2">
                        <strong>Total Profit:</strong> <span id="totalProfit">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content ||
                document.querySelector('input[name="_token"]')?.value || '';
        }

        function setStatus(statusClass, message) {
            const statusDiv = document.getElementById('apiStatus');
            if (!statusDiv) {
                return;
            }

            statusDiv.className = statusClass;
            statusDiv.innerHTML = message;
        }

        async function testConnection() {
            const btn = document.getElementById('testConnection');
            if (!btn) {
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';

            try {
                const response = await fetch('{{ route('admin.dropshipping.settings.test-connection') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': getCsrfToken()
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    setStatus('alert alert-success', '<i class="fas fa-check-circle"></i> ' + data.message);
                } else {
                    setStatus('alert alert-danger', '<i class="fas fa-exclamation-circle"></i> ' + (data.message ||
                        'Connection failed'));
                }
            } catch (error) {
                setStatus('alert alert-danger', '<i class="fas fa-exclamation-circle"></i> Connection error');
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plug"></i> Test Connection';
        }

        // Load stats
        async function loadStats() {
            try {
                const response = await fetch('{{ route('admin.api.dropshipping-stats') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error('Stats request failed');
                }

                const data = await response.json();
                document.getElementById('totalProducts').textContent = data.products || 0;
                document.getElementById('totalOrders').textContent = data.orders || 0;
                document.getElementById('totalProfit').textContent = data.profit || '0 ৳';
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Initial check and load stats
        window.addEventListener('load', () => {
            testConnection();
            loadStats();
        });

        document.getElementById('testConnection')?.addEventListener('click', testConnection);
    </script>
@endsection
