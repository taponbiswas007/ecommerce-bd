@extends('admin.layouts.master')

@section('title', 'AI Settings')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai.index') }}">AI Assistant</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 mt-2">⚙️ AI Settings</h1>
            </div>
        </div>

        <div class="row">
            <!-- API Status -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-plug me-2"></i>API Connection Status</h6>
                    </div>
                    <div class="card-body">
                        <!-- Gemini Status -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-robot text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Google Gemini</h6>
                                    <small class="text-muted">gemini-pro model</small>
                                </div>
                            </div>
                            <div>
                                @if ($config['gemini_configured'])
                                    <span class="badge bg-success me-2" id="geminiStatus">Configured</span>
                                    <button class="btn btn-sm btn-outline-primary" onclick="testConnection('gemini')">
                                        <i class="fas fa-check-circle"></i> Test
                                    </button>
                                @else
                                    <span class="badge bg-danger">Not Configured</span>
                                @endif
                            </div>
                        </div>

                        <!-- Groq Status -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                    <i class="fas fa-bolt text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Groq (Llama)</h6>
                                    <small class="text-muted">llama-3.3-70b-versatile model</small>
                                </div>
                            </div>
                            <div>
                                @if ($config['groq_configured'])
                                    <span class="badge bg-success me-2" id="groqStatus">Configured</span>
                                    <button class="btn btn-sm btn-outline-warning" onclick="testConnection('groq')">
                                        <i class="fas fa-check-circle"></i> Test
                                    </button>
                                @else
                                    <span class="badge bg-danger">Not Configured</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Default Provider -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-star me-2"></i>Default Provider</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Current default: <strong>{{ ucfirst($config['default_provider']) }}</strong>
                        </div>
                        <p class="text-muted small">
                            To change the default provider, update <code>AI_DEFAULT_PROVIDER</code> in your
                            <code>.env</code> file.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Configuration Guide -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-book me-2"></i>Configuration Guide</h6>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary mb-3">Environment Variables</h6>
                        <p class="text-muted small mb-3">Add these to your <code>.env</code> file:</p>

                        <div class="bg-dark text-light p-3 rounded mb-4" style="font-family: monospace; font-size: 13px;">
                            <div class="mb-2"># AI Configuration</div>
                            <div class="text-success">AI_DEFAULT_PROVIDER=gemini</div>
                            <div class="mb-3"></div>
                            <div class="text-info"># Gemini API</div>
                            <div class="text-warning">GEMINI_API_KEY=your_gemini_api_key</div>
                            <div>GEMINI_MODEL=gemini-pro</div>
                            <div class="mb-3"></div>
                            <div class="text-info"># Groq API</div>
                            <div class="text-warning">GROQ_API_KEY=your_groq_api_key</div>
                            <div>GROQ_MODEL=llama-3.3-70b-versatile</div>
                        </div>

                        <h6 class="text-primary mb-3">Getting API Keys</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-external-link-alt text-muted me-2"></i>
                                <strong>Gemini:</strong>
                                <a href="https://makersuite.google.com/app/apikey" target="_blank"
                                    class="text-primary">Google AI Studio</a>
                            </li>
                            <li>
                                <i class="fas fa-external-link-alt text-muted me-2"></i>
                                <strong>Groq:</strong>
                                <a href="https://console.groq.com/keys" target="_blank" class="text-primary">Groq
                                    Console</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Features -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-toggle-on me-2"></i>Enabled Features</h6>
                    </div>
                    <div class="card-body">
                        @foreach ($config['features'] as $feature => $enabled)
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                <span>{{ ucwords(str_replace('_', ' ', $feature)) }}</span>
                                @if ($enabled)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Enabled</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times"></i> Disabled</span>
                                @endif
                            </div>
                        @endforeach
                        <p class="text-muted small mt-3 mb-0">
                            Features can be enabled/disabled in <code>config/ai.php</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Results -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0"><i class="fas fa-terminal me-2"></i>Connection Test Results</h6>
                    </div>
                    <div class="card-body">
                        <div id="testResults" class="text-center text-muted py-3">
                            <i class="fas fa-info-circle me-2"></i>Click "Test" button to check API connection
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            async function testConnection(provider) {
                const resultsDiv = document.getElementById('testResults');
                const statusBadge = document.getElementById(provider + 'Status');

                resultsDiv.innerHTML = `
        <div class="d-flex align-items-center justify-content-center py-3">
            <div class="spinner-border spinner-border-sm me-2"></div>
            Testing ${provider} connection...
        </div>
    `;

                try {
                    const response = await fetch('{{ route('admin.ai.test-connection') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            provider: provider
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        resultsDiv.innerHTML = `
                <div class="alert alert-success mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                        <div>
                            <strong>${provider.charAt(0).toUpperCase() + provider.slice(1)} Connection Successful!</strong>
                            <p class="mb-0 mt-1 small">${data.response || 'API is working correctly'}</p>
                        </div>
                    </div>
                </div>
            `;
                        if (statusBadge) {
                            statusBadge.className = 'badge bg-success me-2';
                            statusBadge.textContent = 'Connected';
                        }
                    } else {
                        resultsDiv.innerHTML = `
                <div class="alert alert-danger mb-0">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-times-circle fa-2x me-3 text-danger"></i>
                        <div>
                            <strong>${provider.charAt(0).toUpperCase() + provider.slice(1)} Connection Failed</strong>
                            <p class="mb-0 mt-1 small">${data.message || 'Unknown error'}</p>
                        </div>
                    </div>
                </div>
            `;
                        if (statusBadge) {
                            statusBadge.className = 'badge bg-danger me-2';
                            statusBadge.textContent = 'Failed';
                        }
                    }
                } catch (error) {
                    resultsDiv.innerHTML = `
            <div class="alert alert-danger mb-0">
                <i class="fas fa-exclamation-circle me-2"></i>
                Network error: Could not connect to server
            </div>
        `;
                }
            }
        </script>
    @endpush
@endsection
