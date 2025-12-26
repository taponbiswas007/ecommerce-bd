@extends('admin.layouts.master')

@section('title', 'VAT/AIT Settings History')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-0">
                    <i class="fas fa-history text-primary me-2"></i>VAT/AIT Settings History
                </h1>
                <small class="text-muted">View all historical VAT and AIT settings changes</small>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.vat-ait.index') }}" class="btn btn-secondary">
                    <i class="fas fa-cog me-1"></i>Current Settings
                </a>
            </div>
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="card-body">
                <div class="timeline">
                    @forelse($settings as $setting)
                        <div class="timeline-item mb-4" style="position: relative; padding-left: 40px;">
                            <!-- Timeline marker -->
                            <div
                                style="position: absolute; left: 0; top: 5px; width: 24px; height: 24px;
                            background: {{ $setting->deleted_at ? '#6c757d' : '#0d6efd' }};
                            border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check text-white" style="font-size: 12px;"></i>
                            </div>

                            <div class="card"
                                style="border: 1px solid {{ $setting->deleted_at ? '#dee2e6' : '#0d6efd' }};">
                                <div class="card-header"
                                    style="background-color: {{ $setting->deleted_at ? '#f8f9fa' : '#e7f1ff' }};">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="mb-0">
                                                @if ($loop->first && !$setting->deleted_at)
                                                    <span class="badge bg-success me-2">Current</span>
                                                @elseif($setting->deleted_at)
                                                    <span class="badge bg-secondary me-2">Superseded</span>
                                                @else
                                                    <span class="badge bg-info me-2">Scheduled</span>
                                                @endif
                                                Effective from {{ $setting->effective_from->format('M d, Y H:i') }}
                                            </h6>
                                        </div>
                                        <div class="col-auto text-muted small">
                                            Updated {{ $setting->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <!-- VAT Settings -->
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="fas fa-percent text-info me-2"></i>VAT Settings
                                            </h6>
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item px-0 py-2 border-0">
                                                    <strong>Status:</strong>
                                                    @if ($setting->vat_enabled)
                                                        <span class="badge bg-success">Enabled</span>
                                                    @else
                                                        <span class="badge bg-danger">Disabled</span>
                                                    @endif
                                                </div>
                                                <div class="list-group-item px-0 py-2 border-0">
                                                    <strong>Percentage:</strong> {{ $setting->default_vat_percentage }}%
                                                </div>
                                                <div class="list-group-item px-0 py-2 border-0">
                                                    <strong>Handling:</strong>
                                                    {{ $setting->vat_included_in_price ? 'Included in Price' : 'Added at Checkout' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- AIT Settings -->
                                        <div class="col-md-6">
                                            <h6 class="mb-3"><i class="fas fa-percent text-info me-2"></i>AIT Settings
                                            </h6>
                                            <div class="list-group list-group-flush">
                                                <div class="list-group-item px-0 py-2 border-0">
                                                    <strong>Status:</strong>
                                                    @if ($setting->ait_enabled)
                                                        <span class="badge bg-success">Enabled</span>
                                                    @else
                                                        <span class="badge bg-danger">Disabled</span>
                                                    @endif
                                                </div>
                                                <div class="list-group-item px-0 py-2 border-0">
                                                    <strong>Percentage:</strong> {{ $setting->default_ait_percentage }}%
                                                </div>
                                                <div class="list-group-item px-0 py-2 border-0">
                                                    <strong>Handling:</strong>
                                                    {{ $setting->ait_included_in_price ? 'Included in Price' : 'Added at Checkout' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($setting->ait_exempt_categories)
                                        <div class="mt-3">
                                            <strong>AIT Exempt Categories:</strong>
                                            <span class="badge bg-warning">{{ $setting->ait_exempt_categories }}</span>
                                        </div>
                                    @endif

                                    @if ($setting->notes)
                                        <div class="mt-3">
                                            <strong>Notes:</strong>
                                            <p class="mb-0 text-muted">{{ $setting->notes }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-footer bg-light small text-muted">
                                    Created: {{ $setting->created_at->format('M d, Y H:i:s') }} |
                                    Updated: {{ $setting->updated_at->format('M d, Y H:i:s') }}
                                    @if ($setting->deleted_at)
                                        | Deleted: {{ $setting->deleted_at->format('M d, Y H:i:s') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted">No settings history available.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @if ($settings->hasPages())
            <div class="mt-4">
                {{ $settings->links() }}
            </div>
        @endif
    </div>

    <style>
        .timeline-item .card {
            box-shadow: 0 0 0 3px white;
        }

        .timeline-item .card:hover {
            box-shadow: 0 0 0 3px white, 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
