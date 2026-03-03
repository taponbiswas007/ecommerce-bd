@php
    $editing = isset($paymentAccount);
@endphp

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Method</label>
        <select name="method" class="form-select" required>
            <option value="bkash" {{ old('method', $paymentAccount->method ?? '') === 'bkash' ? 'selected' : '' }}>bKash
            </option>
            <option value="rocket" {{ old('method', $paymentAccount->method ?? '') === 'rocket' ? 'selected' : '' }}>
                Rocket</option>
            <option value="bank_transfer"
                {{ old('method', $paymentAccount->method ?? '') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer
            </option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Account Name</label>
        <input type="text" name="account_name" class="form-control"
            value="{{ old('account_name', $paymentAccount->account_name ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Account Number</label>
        <input type="text" name="account_number" class="form-control"
            value="{{ old('account_number', $paymentAccount->account_number ?? '') }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Account Holder</label>
        <input type="text" name="account_holder" class="form-control"
            value="{{ old('account_holder', $paymentAccount->account_holder ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Branch</label>
        <input type="text" name="branch" class="form-control"
            value="{{ old('branch', $paymentAccount->branch ?? '') }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Instructions</label>
    <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $paymentAccount->instructions ?? '') }}</textarea>
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $paymentAccount->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active for customer selection</label>
</div>

<button type="submit" class="btn btn-primary">{{ $editing ? 'Update Account' : 'Create Account' }}</button>
<a href="{{ route('admin.payment-accounts.index') }}" class="btn btn-outline-secondary">Cancel</a>
