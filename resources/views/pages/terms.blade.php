@extends('layouts.app')

@section('title', 'Terms & Conditions - ElectroHub')
@section('description', 'Read ElectroHub terms and conditions for using our website and services.')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 mb-3">Terms & Conditions</h1>
                <p class="lead text-muted">Last Updated: {{ date('F d, Y') }}</p>
                <div class="border-bottom border-primary border-3 mx-auto" style="width: 100px;"></div>
            </div>
        </div>

        <!-- Terms Content -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border shadow-sm">
                    <div class="card-body p-4 p-md-5">

                        <!-- Introduction -->
                        <div class="mb-5">
                            <h2 class="mb-3">1. Introduction</h2>
                            <p class="text-muted">
                                Welcome to ElectroHub. These Terms and Conditions govern your use of our website located at
                                electrohub.com and our services. By accessing or using our website, you agree to be bound by
                                these Terms and Conditions.
                            </p>
                            <p class="text-muted">
                                If you disagree with any part of these terms, please do not use our website. These terms
                                apply to all visitors, users, and others who access or use the service.
                            </p>
                        </div>

                        <!-- Accounts -->
                        <div class="mb-5">
                            <h2 class="mb-3">2. User Accounts</h2>
                            <p class="text-muted">
                                When you create an account with us, you must provide accurate, complete, and current
                                information. Failure to do so constitutes a breach of the Terms, which may result in
                                immediate termination of your account.
                            </p>
                            <p class="text-muted">
                                You are responsible for safeguarding the password that you use to access the service and for
                                any activities or actions under your password. You agree not to disclose your password to
                                any third party.
                            </p>
                            <p class="text-muted">
                                You must notify us immediately upon becoming aware of any breach of security or unauthorized
                                use of your account.
                            </p>
                        </div>

                        <!-- Orders and Payments -->
                        <div class="mb-5">
                            <h2 class="mb-3">3. Orders and Payments</h2>
                            <h5 class="mb-2">3.1 Order Acceptance</h5>
                            <p class="text-muted mb-3">
                                All orders are subject to acceptance and availability. We reserve the right to refuse or
                                cancel any order for any reason, including but not limited to product availability, errors
                                in product or pricing information, or problems identified by our credit and fraud avoidance
                                department.
                            </p>

                            <h5 class="mb-2">3.2 Pricing</h5>
                            <p class="text-muted mb-3">
                                All prices are shown in US dollars and are exclusive of taxes unless otherwise stated. We
                                reserve the right to change prices at any time without prior notice.
                            </p>

                            <h5 class="mb-2">3.3 Payment Methods</h5>
                            <p class="text-muted mb-3">
                                We accept various payment methods including credit cards, debit cards, PayPal, and bank
                                transfers. All payments are processed securely through our payment gateway partners.
                            </p>

                            <h5 class="mb-2">3.4 Order Confirmation</h5>
                            <p class="text-muted">
                                You will receive an order confirmation email once your order is successfully placed. This
                                email will contain your order details and tracking information.
                            </p>
                        </div>

                        <!-- Shipping and Delivery -->
                        <div class="mb-5">
                            <h2 class="mb-3">4. Shipping and Delivery</h2>
                            <p class="text-muted mb-3">
                                We ship to most locations worldwide. Shipping costs and delivery times vary depending on
                                your location and the shipping method selected.
                            </p>
                            <p class="text-muted mb-3">
                                Delivery dates are estimates only and are not guaranteed. We are not responsible for delays
                                caused by shipping carriers or customs clearance.
                            </p>
                            <p class="text-muted">
                                Risk of loss and title for items purchased pass to you upon delivery of the items to the
                                carrier.
                            </p>
                        </div>

                        <!-- Returns and Refunds -->
                        <div class="mb-5">
                            <h2 class="mb-3">5. Returns and Refunds</h2>
                            <p class="text-muted mb-3">
                                We offer a 30-day return policy from the date of delivery for most items. Products must be
                                in original condition with all accessories and packaging.
                            </p>
                            <p class="text-muted mb-3">
                                Certain items are not eligible for return, including digital products, personalized items,
                                and perishable goods.
                            </p>
                            <p class="text-muted mb-3">
                                Refunds will be processed within 5-10 business days after we receive and inspect the
                                returned items.
                            </p>
                            <p class="text-muted">
                                For detailed information about our return policy, please visit our <a
                                    href="{{ route('returns') }}" class="text-decoration-none">Returns Policy</a> page.
                            </p>
                        </div>

                        <!-- Intellectual Property -->
                        <div class="mb-5">
                            <h2 class="mb-3">6. Intellectual Property</h2>
                            <p class="text-muted mb-3">
                                The service and its original content, features, and functionality are and will remain the
                                exclusive property of ElectroHub and its licensors. The service is protected by copyright,
                                trademark, and other laws of both the United States and foreign countries.
                            </p>
                            <p class="text-muted">
                                Our trademarks and trade dress may not be used in connection with any product or service
                                without the prior written consent of ElectroHub.
                            </p>
                        </div>

                        <!-- User Conduct -->
                        <div class="mb-5">
                            <h2 class="mb-3">7. User Conduct</h2>
                            <p class="text-muted mb-3">You agree not to:</p>
                            <ul class="text-muted">
                                <li class="mb-2">Use the service for any illegal purpose or in violation of any laws</li>
                                <li class="mb-2">Harass, abuse, or harm another person</li>
                                <li class="mb-2">Interfere with or disrupt the service or servers</li>
                                <li class="mb-2">Attempt to gain unauthorized access to the service</li>
                                <li class="mb-2">Use the service to transmit any viruses or malicious code</li>
                                <li class="mb-2">Impersonate any person or entity</li>
                                <li>Engage in any conduct that restricts or inhibits anyone's use of the service</li>
                            </ul>
                        </div>

                        <!-- Limitation of Liability -->
                        <div class="mb-5">
                            <h2 class="mb-3">8. Limitation of Liability</h2>
                            <p class="text-muted mb-3">
                                In no event shall ElectroHub, nor its directors, employees, partners, agents, suppliers, or
                                affiliates, be liable for any indirect, incidental, special, consequential or punitive
                                damages, including without limitation, loss of profits, data, use, goodwill, or other
                                intangible losses, resulting from:
                            </p>
                            <ul class="text-muted">
                                <li class="mb-2">Your access to or use of or inability to access or use the service</li>
                                <li class="mb-2">Any conduct or content of any third party on the service</li>
                                <li class="mb-2">Any content obtained from the service</li>
                                <li>Unauthorized access, use or alteration of your transmissions or content</li>
                            </ul>
                        </div>

                        <!-- Disclaimer -->
                        <div class="mb-5">
                            <h2 class="mb-3">9. Disclaimer</h2>
                            <p class="text-muted mb-3">
                                Your use of the service is at your sole risk. The service is provided on an "AS IS" and "AS
                                AVAILABLE" basis. The service is provided without warranties of any kind, whether express or
                                implied.
                            </p>
                            <p class="text-muted">
                                ElectroHub does not warrant that the service will function uninterrupted, secure, or
                                available at any particular time or location.
                            </p>
                        </div>

                        <!-- Governing Law -->
                        <div class="mb-5">
                            <h2 class="mb-3">10. Governing Law</h2>
                            <p class="text-muted">
                                These Terms shall be governed and construed in accordance with the laws of the State of
                                California, United States, without regard to its conflict of law provisions.
                            </p>
                        </div>

                        <!-- Changes to Terms -->
                        <div class="mb-5">
                            <h2 class="mb-3">11. Changes to Terms</h2>
                            <p class="text-muted mb-3">
                                We reserve the right, at our sole discretion, to modify or replace these Terms at any time.
                                If a revision is material, we will provide at least 30 days' notice prior to any new terms
                                taking effect.
                            </p>
                            <p class="text-muted">
                                By continuing to access or use our service after those revisions become effective, you agree
                                to be bound by the revised terms.
                            </p>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-5">
                            <h2 class="mb-3">12. Contact Us</h2>
                            <p class="text-muted mb-3">
                                If you have any questions about these Terms, please contact us:
                            </p>
                            <div class="bg-light rounded p-4">
                                <p class="mb-2"><strong>Email:</strong> legal@electrohub.com</p>
                                <p class="mb-2"><strong>Phone:</strong> (415) 123-4567</p>
                                <p class="mb-0"><strong>Address:</strong> 123 Tech Street, San Francisco, CA 94107</p>
                            </div>
                        </div>

                        <!-- Acceptance -->
                        <div class="mt-5 pt-4 border-top">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="alert-heading">Acceptance of Terms</h5>
                                        <p class="mb-0">By using our website and services, you acknowledge that you have
                                            read, understood, and agree to be bound by these Terms and Conditions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Quick Links -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card border shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-shield-alt text-primary me-2"></i> Privacy Policy
                                </h5>
                                <p class="card-text text-muted small">
                                    Learn how we collect, use, and protect your personal information.
                                </p>
                                <a href="{{ route('privacy') }}" class="btn btn-outline-primary btn-sm">
                                    Read Privacy Policy
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card border shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="card-title mb-3">
                                    <i class="fas fa-exchange-alt text-success me-2"></i> Returns Policy
                                </h5>
                                <p class="card-text text-muted small">
                                    Detailed information about returns, exchanges, and refunds.
                                </p>
                                <a href="{{ route('returns') }}" class="btn btn-outline-success btn-sm">
                                    View Returns Policy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
