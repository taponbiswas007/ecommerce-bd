<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">VAT (Value Added Tax) Settings</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">Configure the default VAT percentage and behavior for all products.</p>

                <form action="{{ route('admin.vat-ait.update-settings') }}" method="POST">
                    @csrf

                    <!-- VAT Configuration -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="vat_enabled" class="form-label fw-bold">Enable VAT</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="vat_enabled" name="vat_enabled"
                                        value="1" {{ $settings->vat_enabled ? 'checked' : '' }}
                                        onchange="updateVatFields()">
                                    <label class="form-check-label" for="vat_enabled">
                                        Activate VAT for all products
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">Enable or disable VAT globally across the
                                    store.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="default_vat_percentage" class="form-label fw-bold">Default VAT Percentage
                                    (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="default_vat_percentage"
                                        name="default_vat_percentage" step="0.01" min="0" max="100"
                                        value="{{ $settings->default_vat_percentage }}" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted d-block mt-2">Standard VAT rate (e.g., 15% in
                                    Bangladesh)</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="vat_included_in_price" class="form-label fw-bold">VAT Handling</label>
                                <select class="form-select" id="vat_included_in_price" name="vat_included_in_price"
                                    required>
                                    <option value="1" {{ $settings->vat_included_in_price ? 'selected' : '' }}>
                                        VAT Included in Price
                                    </option>
                                    <option value="0" {{ !$settings->vat_included_in_price ? 'selected' : '' }}>
                                        VAT Added at Checkout
                                    </option>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <strong>Included:</strong> Price shown already contains VAT<br>
                                    <strong>Added:</strong> VAT is calculated and added during checkout
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- AIT Configuration -->
                    <h6 class="mb-3">AIT (Advance Income Tax) Settings</h6>
                    <p class="text-muted small mb-3">Configure the default AIT percentage and behavior for all products.
                    </p>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="ait_enabled" class="form-label fw-bold">Enable AIT</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="ait_enabled" name="ait_enabled"
                                        value="1" {{ $settings->ait_enabled ? 'checked' : '' }}
                                        onchange="updateAitFields()">
                                    <label class="form-check-label" for="ait_enabled">
                                        Activate AIT for all products
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-2">Enable or disable AIT globally across the
                                    store.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="default_ait_percentage" class="form-label fw-bold">Default AIT Percentage
                                    (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="default_ait_percentage"
                                        name="default_ait_percentage" step="0.01" min="0" max="100"
                                        value="{{ $settings->default_ait_percentage }}" required>
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted d-block mt-2">Standard AIT rate (e.g., 2% in
                                    Bangladesh)</small>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ait_included_in_price" class="form-label fw-bold">AIT Handling</label>
                                <select class="form-select" id="ait_included_in_price" name="ait_included_in_price"
                                    required>
                                    <option value="1" {{ $settings->ait_included_in_price ? 'selected' : '' }}>
                                        AIT Included in Price
                                    </option>
                                    <option value="0" {{ !$settings->ait_included_in_price ? 'selected' : '' }}>
                                        AIT Added at Checkout
                                    </option>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <strong>Included:</strong> Price shown already contains AIT<br>
                                    <strong>Added:</strong> AIT is calculated and added during checkout
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Exemptions -->
                    <h6 class="mb-3">Tax Exemptions</h6>
                    <p class="text-muted small mb-3">Specify categories exempt from AIT by category ID
                        (comma-separated).</p>

                    <div class="form-group mb-3">
                        <label for="ait_exempt_categories" class="form-label fw-bold">AIT Exempt Categories</label>
                        <textarea class="form-control" id="ait_exempt_categories" name="ait_exempt_categories" rows="3"
                            placeholder="e.g., 1, 2, 5">{{ $settings->ait_exempt_categories }}</textarea>
                        <small class="text-muted d-block mt-2">
                            Enter category IDs that are exempt from AIT. These are typically essential commodities.
                        </small>
                        <div class="mt-3">
                            <strong>Available Categories:</strong>
                            <div class="list-group list-group-sm mt-2" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($categories as $category)
                                    <a href="#"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                        onclick="event.preventDefault(); addCategoryId({{ $category->id }})">
                                        <span>{{ $category->name }}</span>
                                        <span class="badge bg-secondary">ID: {{ $category->id }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Metadata -->
                    <div class="form-group mb-3">
                        <label for="notes" class="form-label fw-bold">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                            placeholder="e.g., Updated as per government notification...">{{ $settings->notes }}</textarea>
                        <small class="text-muted d-block mt-2">Internal notes about these settings for your
                            reference.</small>
                    </div>

                    <div class="form-group">
                        <label for="effective_from" class="form-label fw-bold">Effective From</label>
                        <input type="datetime-local" class="form-control" id="effective_from" name="effective_from"
                            value="{{ $settings->effective_from ? $settings->effective_from->format('Y-m-d\TH:i') : '' }}">
                        <small class="text-muted d-block mt-2">
                            Leave empty to apply immediately. Set a future date to schedule changes.
                        </small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="mb-0">Current Settings Summary</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>VAT Status:</strong><br>
                    @if ($settings->vat_enabled)
                        <span class="badge bg-success">Enabled</span>
                    @else
                        <span class="badge bg-danger">Disabled</span>
                    @endif
                    <div class="small text-muted mt-1">
                        Rate: {{ $settings->default_vat_percentage }}%<br>
                        Handling: {{ $settings->vat_included_in_price ? 'Included' : 'Added' }}
                    </div>
                </div>

                <div class="mb-3">
                    <strong>AIT Status:</strong><br>
                    @if ($settings->ait_enabled)
                        <span class="badge bg-success">Enabled</span>
                    @else
                        <span class="badge bg-danger">Disabled</span>
                    @endif
                    <div class="small text-muted mt-1">
                        Rate: {{ $settings->default_ait_percentage }}%<br>
                        Handling: {{ $settings->ait_included_in_price ? 'Included' : 'Added' }}
                    </div>
                </div>

                <hr>

                <div class="small">
                    <strong>Effective From:</strong><br>
                    {{ $settings->effective_from ? $settings->effective_from->format('M d, Y H:i') : 'Immediately' }}
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Quick Info</h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">
                    <strong>What is VAT?</strong><br>
                    Value Added Tax is a consumption tax collected at each stage of production.
                </p>
                <p class="small mb-2">
                    <strong>What is AIT?</strong><br>
                    Advance Income Tax is a withholding tax on domestic purchases.
                </p>
                <p class="small">
                    <strong>Product Override?</strong><br>
                    Set different tax rates for specific products or exempt them entirely.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function addCategoryId(categoryId) {
        const textarea = document.getElementById('ait_exempt_categories');
        const currentValue = textarea.value.trim();

        if (currentValue) {
            textarea.value = currentValue + ', ' + categoryId;
        } else {
            textarea.value = categoryId;
        }
    }

    function updateVatFields() {
        const enabled = document.getElementById('vat_enabled').checked;
        document.getElementById('default_vat_percentage').disabled = !enabled;
        document.getElementById('vat_included_in_price').disabled = !enabled;
    }

    function updateAitFields() {
        const enabled = document.getElementById('ait_enabled').checked;
        document.getElementById('default_ait_percentage').disabled = !enabled;
        document.getElementById('ait_included_in_price').disabled = !enabled;
        document.getElementById('ait_exempt_categories').disabled = !enabled;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateVatFields();
        updateAitFields();
    });
</script>
