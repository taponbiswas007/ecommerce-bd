<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ecommerce BD') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary-blue: #0d6efd;
            --primary-dark-blue: #0b5ed7;
            --electric-blue: #0096ff;
            --electric-purple: #7b2cbf;
            --neon-green: #00ff88;
            --warning-yellow: #ffc107;
            --dark-bg: #1a1d29;
            --light-bg: #f8f9fa;
        }

        .bg-dark {
            background: var(--dark-bg) !important;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-image: url(noise.webp);
            background-repeat: repeat;
            background-color: var(--light-bg);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        /* Header Top Styles */
        .header-top {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #2d3748 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .marquee-container {
            overflow: hidden;
            position: relative;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            padding: 5px 10px;
        }

        .marquee-content {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 25s linear infinite;
            padding-left: 100%;
        }

        .marquee-content:hover {
            animation-play-state: paused;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .offer-badge {
            background: linear-gradient(90deg, var(--electric-blue), var(--electric-purple));
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 10px;
        }

        /* Language Selector */
        .language-selector {
            position: relative;
        }

        .language-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--electric-blue);
        }

        .language-flag {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
        }

        /* Main Header Styles */
        .main-header {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(90deg, var(--electric-blue), var(--electric-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            min-width: 92px;
            max-width: 110px;
            width: 100%;
        }

        .logo:hover {
            text-decoration: none;
        }

        .logo-icon {
            color: var(--electric-blue);
            font-size: 2rem;
            margin-right: 8px;
        }

        /* Search Bar */
        .search-container {
            position: relative;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 10px 20px 10px 45px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input:focus {
            border-color: var(--electric-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 150, 255, 0.25);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }

        /* Header Icons */
        .header-icon {
            position: relative;
            color: #495057;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 50%;
        }

        .header-icon:hover {
            color: var(--electric-blue);
            background-color: rgba(0, 150, 255, 0.1);
            transform: translateY(-2px);
        }

        .cart-count,
        .wishlist-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Navigation */
        .main-nav {
            background: linear-gradient(90deg, var(--dark-bg) 0%, #2d3748 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .nav-link-custom {
            color: white !important;
            font-weight: 400;
            padding: 12px 20px !important;
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover {
            color: var(--neon-green) !important;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link-custom.active {
            color: var(--neon-green) !important;
            background: rgba(0, 255, 136, 0.1);
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 20%;
            width: 60%;
            height: 3px;
            background: var(--neon-green);
            border-radius: 3px;
        }

        /* Dropdown Menu */
        .dropdown-menu-custom {
            background: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            margin-top: 10px;
            min-width: 200px;
        }

        .dropdown-item-custom {
            padding: 10px 20px;
            color: #495057;
            transition: all 0.2s ease;
        }

        .dropdown-item-custom:hover {
            background: linear-gradient(90deg, rgba(0, 150, 255, 0.1), rgba(123, 44, 191, 0.1));
            color: var(--electric-blue);
            padding-left: 25px;
        }

        .nav-link:hover {
            background: linear-gradient(90deg, rgba(0, 150, 255, 0.1), rgba(123, 44, 191, 0.1));
            color: var(--electric-blue);
            transition: all 0.3s ease;
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            position: relative;
            padding: 0;
        }

        .mobile-menu-btn span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--dark-bg);
            margin: 5px auto;
            transition: all 0.3s ease;
            position: absolute;
            left: 8px;
        }

        .mobile-menu-btn span:nth-child(1) {
            top: 10px;
            margin-top: 0;
        }

        .mobile-menu-btn span:nth-child(2) {
            top: 19px;
            margin-top: 0;
        }

        .mobile-menu-btn span:nth-child(3) {
            top: 28px;
            margin-top: 0;
        }

        .mobile-menu-btn.active span:nth-child(1) {
            transform: rotate(45deg);
            top: 19px;
        }

        .mobile-menu-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active span:nth-child(3) {
            transform: rotate(-45deg);
            top: 19px;
        }

        /* Mobile Menu */
        .mobile-menu {
            background: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            height: 100%;
            overflow-y: auto;
            z-index: 1050;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: none;
        }

        .mobile-menu.show {
            display: block;
        }

        /* Social Login Buttons */
        .btn-social {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-social:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-social i {
            font-size: 1.1rem;
        }

        /* Divider */
        .divider {
            position: relative;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .divider span {
            position: relative;
            top: -12px;
            background: white;
            padding: 0 15px;
        }

        /* Form Validation Styles */
        .was-validated .form-control:valid {
            border-color: #28a745;
        }

        .was-validated .form-control:invalid {
            border-color: #dc3545;
        }

        /* Modal Animation */
        .modal.fade .modal-dialog {
            transform: translate(0, -50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translate(0, 0);
        }

        /* Modal Content */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background: linear-gradient(135deg, var(--electric-blue), var(--electric-purple));
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-header .btn-close {
            filter: invert(1) brightness(100%);
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .search-container {
                order: 3;
                max-width: 100%;
                margin-top: 15px;
            }

            .nav-link-custom {
                padding: 10px 15px !important;
            }
        }

        .shopping-cart-header .active {
            background: linear-gradient(90deg, var(--electric-blue), var(--electric-purple));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .menuBtn:active {
            -webkit-background-clip: unset !important;
            background-clip: unset !important;
            -webkit-text-fill-color: unset !important;
        }

        @media (max-width: 767.98px) {
            .header-top .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .language-btn {
                flex-direction: row !important;
            }

            .marquee-container {
                width: 100%;
            }

            .authLoginBtn {
                outline: none;
                border: none;
                padding: 0;
                color: #9aa0a6;
                font-size: 1.5rem;
            }

            .authLoginBtn:hover {
                background: none;
                box-shadow: none;

            }

            /* .wishlistIcon,
            .cartIcon,
            .authArea,
            .profileArea,
            .authLoginBtn,
            .homeLink {
                font-size: 1.5rem;
                color: #6c757d;
            } */
            /* Bottom Navigation Wrapper */
            .shopping-cart-header {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                height: 64px;
                background: #ffffff;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: env(safe-area-inset-bottom);
                box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.08);
                border-top: 1px solid #eee;
                z-index: 999;
            }

            /* Common icon style */
            .wishlistIcon,
            .cartIcon,
            .authArea,
            .profileArea,
            .homeLink {
                font-size: 1.5rem;
                color: #333;
                position: relative;
                transition: all 0.25s ease;
                cursor: pointer;
                padding: 0 !important;
                margin: 0 !important;
            }

            .header-icon {
                font-size: 1.5rem;
                color: #333;
                position: relative;
                transition: all 0.25s ease;
                cursor: pointer;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* Click effect */
            .wishlistIcon:active,
            .cartIcon:active,
            .authArea:active,
            .profileArea:active,
            .homeLink:active {
                transform: scale(0.9);
            }

            /* Active state */
            /* .shopping-cart-header .active {
                color: #0d6efd;
            } */




            /* Middle menu button (FAB style) */
            .menuBtn {
                background: linear-gradient(90deg, var(--electric-blue), var(--electric-purple)) !important;
                margin-left: 0;
                color: #fff;
                width: 52px;
                height: 52px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 24px;
                /* margin-top: -30px; */

                /* HOLE EFFECT */
                box-shadow:
                    inset 0 6px 10px rgba(255, 255, 255, 0.25),
                    /* top inner light */
                    inset 0 -6px 10px rgba(0, 0, 0, 0.35),
                    /* bottom inner dark */
                    0 4px 8px rgba(0, 0, 0, 0.2);
                /* soft outer depth */
            }


            .mobile-menu-btn span {
                left: 13px;
                background: #ffffff;
                margin-top: 4px !important;
            }

            /* Disable dot for menu button */
            .menuBtn::after {
                display: none;
            }

            /* Auth button reset */
            .authLoginBtn {
                background: transparent;
                border: none;
                outline: none;
                padding: 0;
            }


            .wishlistIcon {
                order: 4;
            }

            .cartIcon {
                order: 2;
            }

            .authArea {
                order: 4;
            }

            .menuBtn {
                order: 3;
            }

            .profileArea {
                order: 5;
            }

            .homeLink {
                order: 1;
            }

            body {
                padding-bottom: 64px;
            }

            .mobile-menu {
                background: white;
                position: fixed;
                bottom: 64px;
                left: 0;
                right: 0;
                height: calc(100% - 64px);
                width: 100%;
                overflow-y: auto;
                z-index: 1050;
                box-shadow: none;
                display: none;
            }
        }

        @media (max-width: 540px) {
            .search-container {
                position: relative;
                margin-top: 0;
            }

            .search-container .search-input {
                display: none;
                position: absolute;
                top: -18px;
                right: 13px;
                left: 40px;
                width: calc(100% - 40px);
                z-index: 99999;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 6px 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                transition: opacity 0.2s;
            }

            .search-container.show-input .search-input {
                display: block;
            }

            .search-container .search-icon {
                font-size: 1.3rem;
                z-index: 10000;
                right: 15px;
                left: unset
            }

            /* Hide input by default, only show icon */
            .search-container .search-input {
                opacity: 1;
            }

            .section-header h2 {
                font-size: 1.25rem !important;
            }
        }

        /* Newsletter Section */
        .newsletter-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        .newsletter-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .newsletter-section .container-fluid {
            position: relative;
            z-index: 2;
        }

        .newsletter-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .newsletter-section h2::after {
            content: "";
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 60px;
            height: 4px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .newsletter-section p {
            font-size: 1.1rem;
            max-width: 500px;
            opacity: 0.9;
        }

        .newsletter-input {
            border: none;
            border-radius: 50px;
            padding: 0.9rem 1.5rem;
            font-size: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
        }

        .newsletter-input:focus {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            border-color: var(--accent-color);
        }

        .newsletter-btn {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.9rem 2rem;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .newsletter-btn:hover {
            background-color: #e11570;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Footer Section */

        footer h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
            color: white;
        }

        footer h5::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 3px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        footer .text-secondary {
            color: #b0b7c3 !important;
            transition: var(--transition);
        }

        footer a.text-secondary:hover {
            color: white !important;
            padding-left: 5px;
        }

        footer ul li {
            margin-bottom: 0.8rem;
        }

        /* Bangladesh-themed social icons */
        .bd-social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            transition: var(--transition);
            color: white !important;
        }

        .bd-social-icons a:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        footer hr {
            opacity: 0.2;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .copyright {
            font-size: 0.9rem;
        }

        /* Payment methods with local options */
        .payment-methods i {
            transition: var(--transition);
        }

        .payment-methods i:hover {
            transform: translateY(-3px);
            color: var(--accent-color) !important;
        }

        /* Bangladesh-specific section */
        .bd-info {
            background-color: rgba(0, 106, 78, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid var(--primary-color);
        }

        .bd-info h6 {
            color: var(--primary-color);
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .bd-footer::after {
                display: none;
            }

            .bd-footer .chittagong-marker {
                display: none;
            }

            footer .col-md-4,
            footer .col-md-3 {
                margin-bottom: 2.5rem;
            }
        }

        /* Floating national elements */
        .floating-symbol {
            position: absolute;
            z-index: 1;
            opacity: 0.1;
            font-size: 1.5rem;
        }

        .floating-symbol:nth-child(1) {
            top: 20%;
            left: 10%;
            animation: float 8s infinite ease-in-out;
        }

        .floating-symbol:nth-child(2) {
            top: 60%;
            right: 15%;
            animation: float 10s infinite ease-in-out 1s;
        }

        .floating-symbol:nth-child(3) {
            bottom: 40%;
            left: 20%;
            animation: float 12s infinite ease-in-out 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Success message for form submission */
        .success-message {
            display: none;
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #4ade80;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }
    </style>
    <!-- Chat CSS -->
    <style>
        .chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        @media (max-width: 767px) {
            .chat-widget {
                bottom: 80px;
            }
        }

        .chat-toggle-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue, #0d6efd), var(--electric-blue, #0096ff));
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
            transition: all 0.3s ease;
            justify-content: center;
            align-items: center;
        }

        .chat-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .chat-unread-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            border: 2px solid white;
        }

        .chat-dialog {
            position: absolute;
            bottom: 0px;
            right: 0;
            width: 380px;
            max-width: calc(100vw - 40px);
            height: 500px;
            max-height: calc(100vh - 120px);
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, var(--primary-blue, #0d6efd), var(--electric-blue, #0096ff));
            color: white;
            padding: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 16px;
        }

        .chat-close-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }

        .chat-body {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 16px;
            background: #f8f9fa;
            height: 400px;
            max-height: calc(100vh - 200px);
        }

        .chat-messages {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-height: min-content;
        }

        .chat-message {
            display: flex;
            gap: 8px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-message.sent {
            flex-direction: row-reverse;
        }

        .chat-message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-blue, #0d6efd);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .chat-message-content {
            max-width: 70%;
        }

        .chat-message-bubble {
            padding: 10px 14px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            word-wrap: break-word;
        }

        .chat-message.sent .chat-message-bubble {
            background: var(--primary-blue, #0d6efd);
            color: white;
        }

        .chat-message-time {
            font-size: 11px;
            color: #6c757d;
            margin-top: 4px;
            padding: 0 4px;
        }

        .chat-footer {
            padding: 12px;
            background: white;
            border-top: 1px solid #dee2e6;
        }

        .chat-footer .input-group {
            gap: 8px;
        }

        .chat-footer input {
            border-radius: 20px;
            border: 1px solid #dee2e6;
            padding: 8px 16px;
        }

        .chat-footer button {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 576px) {
            .chat-dialog {
                width: calc(100vw - 40px);
                height: calc(100vh - 120px);
            }
        }
    </style>
    <style>
        /* Google Translate Custom Styles */
        #google_translate_element {
            display: none;
        }

        .goog-te-banner-frame {
            display: none !important;
        }

        .goog-te-gadget {
            font-family: 'Roboto', sans-serif !important;
            font-size: 0 !important;
        }

        .goog-te-gadget-simple {
            background-color: transparent !important;
            border: none !important;
            padding: 0 !important;
        }

        .goog-te-menu-value {
            color: #333 !important;
            font-size: 14px !important;
        }

        .goog-te-menu-value span {
            color: #333 !important;
        }

        .goog-te-menu-value:hover {
            text-decoration: none !important;
        }

        .goog-te-gadget img {
            display: none !important;
        }

        .goog-te-combo {
            padding: 8px 12px !important;
            border-radius: 6px !important;
            border: 1px solid #ddd !important;
            font-family: 'Roboto', sans-serif !important;
            font-size: 14px !important;
            background: white !important;
            color: #333 !important;
        }

        .goog-te-combo:focus {
            outline: none !important;
            border-color: var(--electric-blue) !important;
            box-shadow: 0 0 0 2px rgba(0, 150, 255, 0.25) !important;
        }

        /* RTL Support */
        [dir="rtl"] {
            font-family: 'Arial', sans-serif !important;
        }

        [dir="rtl"] .text-start {
            text-align: right !important;
        }

        [dir="rtl"] .text-end {
            text-align: left !important;
        }

        [dir="rtl"] .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }

        /* Language Selector Enhancements */
        .language-selector {
            z-index: 1050;
        }

        .language-selector .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }

        .language-flag {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #dee2e6;
        }

        .language-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            min-width: 100px;
            justify-content: center;
        }

        .language-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--electric-blue);
        }

        .dropdown-item-custom[onclick*="en"] {
            font-weight: bold;
            background: rgba(0, 150, 255, 0.1);
        }

        .skiptranslate {
            display: none !important;
        }

        body {
            top: 0 !important;
        }
    </style>
    @yield('styles')
</head>

<body style="top: 0 !important">
    <!-- Loading Overlay -->
    <div id="global-loader"
        style="position:fixed;z-index:20000;top:0;left:0;width:100vw;height:100vh;background:rgba(255,255,255,0.95);display:flex;align-items:center;justify-content:center;transition:opacity 0.4s;">
        <div style="display:flex;flex-direction:column;align-items:center;">
            <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div style="margin-top:1rem;font-weight:500;color:#0d6efd;">Loading, please wait...</div>
        </div>
    </div>
    <!-- Header Top Section -->
    <div class="header-top py-2">
        <div class="container-fluid px-3">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Marquee Ads -->
                <div class="marquee-container flex-grow-1 me-3">
                    <div class="marquee-content">

                        @php
                            $ads = \App\Models\Ad::all();
                        @endphp

                        @foreach ($ads as $ad)
                            <span class="me-4">
                                <span class="offer-badge">{{ $ad['badge'] }}</span>
                                <span class="text-white">{{ $ad['text'] }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Language Selector -->
                <div class="language-selector">
                    <button class="language-btn d-flex align-items-center" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="https://flagcdn.com/w20/us.png" class="language-flag" alt="English">
                        <span>English</span>
                        <i class="fas fa-chevron-down ms-2" style="font-size: 0.75rem;"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom" style="min-width: 150px;">
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('en')">
                                <img src="https://flagcdn.com/w20/us.png" class="language-flag me-2" alt="English">
                                English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('bn')">
                                <img src="https://flagcdn.com/w20/bd.png" class="language-flag me-2" alt="Bangla">
                                বাংলা
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('es')">
                                <img src="https://flagcdn.com/w20/es.png" class="language-flag me-2" alt="Spanish">
                                Español
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('fr')">
                                <img src="https://flagcdn.com/w20/fr.png" class="language-flag me-2" alt="French">
                                Français
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('de')">
                                <img src="https://flagcdn.com/w20/de.png" class="language-flag me-2" alt="German">
                                Deutsch
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('zh-CN')">
                                <img src="https://flagcdn.com/w20/cn.png" class="language-flag me-2" alt="Chinese">
                                中文
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('hi')">
                                <img src="https://flagcdn.com/w20/in.png" class="language-flag me-2" alt="Hindi">
                                हिन्दी
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('ar')">
                                <img src="https://flagcdn.com/w20/sa.png" class="language-flag me-2" alt="Arabic">
                                العربية
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('ja')">
                                <img src="https://flagcdn.com/w20/jp.png" class="language-flag me-2" alt="Japanese">
                                日本語
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item dropdown-item-custom d-flex align-items-center" href="#"
                                onclick="changeGoogleTranslate('ko')">
                                <img src="https://flagcdn.com/w20/kr.png" class="language-flag me-2" alt="Korean">
                                한국어
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Google Translate Container - Add this where you want the widget to appear -->
                <div id="google_translate_element" style="display: none;"></div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header py-3">
        <div class="container-fluid px-3">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-md-3 col-2">
                    <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                        @php
                            $companyLogo = \App\Models\User::whereNotNull('company_logo')->value('company_logo');
                        @endphp
                        @if ($companyLogo)
                            <img class="img-fluid" src="{{ asset('storage/' . $companyLogo) }}" alt="Company Logo"
                                height="50">
                        @else
                            <img class="img-fluid" src="{{ asset('logo.webp') }}" alt="Ecommerce BD" height="50">
                        @endif
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-8  col-md-5 col-10 ">
                    <div class="search-container position-relative">
                        <i class="fas fa-search search-icon d-inline-block" id="responsiveSearchIcon"
                            style="cursor:pointer;"></i>
                        <form action="{{ route('shop') }}" method="GET" class="m-0 p-0" id="responsiveSearchForm">
                            <input type="search" name="search" class="search-input"
                                placeholder="Search for products, brands and more..." value="{{ request('search') }}">
                        </form>
                    </div>
                </div>


                <!-- Header Icons -->
                <div class="col-lg-2 col-md-4 col-9 order-md-1 shopping-cart-header">
                    <div class="d-flex justify-content-between justify-content-md-end align-items-center w-100 gap-3">
                        {{-- home --}}
                        <a class="homeLink d-md-none {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            <i class="fa-solid fa-house"></i>
                        </a>

                        <!-- Wishlist -->
                        <a href="{{ route('wishlist.index') }}"
                            class="header-icon wishlistIcon position-relative {{ request()->routeIs('wishlist.index') ? 'active' : '' }}">
                            <i class="far fa-heart"></i>
                            @auth
                                @php
                                    $wishlistCount = auth()->user()->wishlists()->count();
                                @endphp
                                @if ($wishlistCount > 0)
                                    <span class="wishlist-count">{{ $wishlistCount }}</span>
                                @endif
                            @endauth
                        </a>

                        <!-- Cart -->
                        <a href="{{ route('cart.index') }}"
                            class="header-icon cartIcon position-relative {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = \App\Models\Cart::count();
                            @endphp
                            @if ($cartCount > 0)
                                <span class="cart-count">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <!-- User Account -->
                        @auth
                            <div class="dropdown profileArea">
                                <a href="#"
                                    class="d-flex align-items-center text-decoration-none {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="header-icon">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom">
                                    @if (auth()->user()->role === 'admin')
                                        <li>
                                            <a class="dropdown-item dropdown-item-custom"
                                                href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom" href="{{ route('dashboard') }}">
                                            <i class="fas fa-user me-2"></i>My Account
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom" href="{{ route('orders.index') }}">
                                            <i class="fas fa-box me-2"></i>My Orders
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom"
                                            href="{{ route('wishlist.index') }}">
                                            <i class="fas fa-heart me-2"></i>Wishlist
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item dropdown-item-custom">
                                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <!-- Login and Register Buttons with Modals -->
                            <div class="d-flex gap-2 authArea">
                                <button type="button" class="btn btn-sm btn-outline-primary d-none "
                                    data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="fas fa-sign-in-alt me-1"></i> <span
                                        class="d-md-inline-block d-none">Login</span>
                                </button>
                                <a type="button" class=" authLoginBtn header-icon " data-bs-toggle="modal"
                                    data-bs-target="#registerModal">
                                    <i class="fas fa-user-plus"></i>
                                </a>
                            </div>
                        @endauth

                        <!-- Mobile Menu Toggle -->
                        <button class="mobile-menu-btn d-lg-none ms-2 menuBtn" id="mobileMenuToggle">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="main-nav d-none d-lg-block">
        <div class="container-fluid px-3">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        <i class="fas fa-home me-2"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('shop*') ? 'active' : '' }}"
                        href="{{ route('shop') }}">
                        <i class="fas fa-store me-2"></i> Shop
                    </a>
                </li>

                <!-- Categories Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link nav-link-custom dropdown-toggle {{ request()->routeIs('category.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-th-large me-2"></i> Categories
                    </a>
                    <ul class="dropdown-menu dropdown-menu-custom">
                        @php
                            $categories = \App\Models\Category::whereNull('parent_id')
                                ->where('is_active', true)
                                ->with('children')
                                ->limit(8)
                                ->get();
                        @endphp

                        @foreach ($categories as $category)
                            <li>
                                <a class="dropdown-item dropdown-item-custom"
                                    href="{{ route('category.show', $category->slug) }}">
                                    <i class="fas fa-folder me-2"></i> {{ $category->name }}
                                </a>
                            </li>
                            @if ($category->children->isNotEmpty())
                                @foreach ($category->children as $child)
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom ps-4"
                                            href="{{ route('category.show', $child->slug) }}">
                                            <i class="fas fa-angle-right me-2"></i> {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            @if (!$loop->last)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                        @endforeach

                        <li>
                            <a class="dropdown-item dropdown-item-custom text-primary" href="#">
                                <i class="fas fa-eye me-2"></i> View All Categories
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('about') ? 'active' : '' }}"
                        href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-2"></i> About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('services') ? 'active' : '' }}"
                        href="{{ route('services') }}">
                        <i class="fas fa-cogs me-2"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact') }}">
                        <i class="fas fa-phone-alt me-2"></i> Contact
                    </a>
                </li>

                <!-- Special Offers -->
                <li class="nav-item ms-auto">
                    <a class="nav-link nav-link-custom text-warning" href="{{ route('offers') }}">
                        <i class="fas fa-gift me-2"></i> Special Offers
                        <span class="badge bg-danger ms-1">Hot</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div class="mobile-menu d-lg-none" id="mobileMenu">
        <div class="container py-3">
            <a href="{{ route('home') }}" class="logo d-flex align-items-center">
                {{-- <i class="fas fa-bolt logo-icon"></i>
                        <span>Ecommerce BD</span> --}}
                <img class=" img-fluid" src="{{ asset('logo.webp') }}" alt="Ecommerce BD" height="50">
            </a>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        <i class="fas fa-home me-3"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('shop*') ? 'active' : '' }}"
                        href="{{ route('shop') }}">
                        <i class="fas fa-store me-3"></i> Shop
                    </a>
                </li>

                <!--Mobile Categories Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('category.*') ? 'active' : '' }}"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-th-large me-2"></i> Categories
                    </a>
                    <ul class="dropdown-menu dropdown-menu-custom">
                        @php
                            $categories = \App\Models\Category::whereNull('parent_id')
                                ->where('is_active', true)
                                ->with('children')
                                ->limit(8)
                                ->get();
                        @endphp

                        @foreach ($categories as $category)
                            <li>
                                <a class="dropdown-item dropdown-item-custom"
                                    href="{{ route('category.show', $category->slug) }}">
                                    <i class="fas fa-folder me-2"></i> {{ $category->name }}
                                </a>
                            </li>
                            @if ($category->children->isNotEmpty())
                                @foreach ($category->children as $child)
                                    <li>
                                        <a class="dropdown-item dropdown-item-custom ps-4"
                                            href="{{ route('category.show', $child->slug) }}">
                                            <i class="fas fa-angle-right me-2"></i> {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            @if (!$loop->last)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                        @endforeach

                        <li>
                            <a class="dropdown-item dropdown-item-custom text-primary" href="#">
                                <i class="fas fa-eye me-2"></i> View All Categories
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('about') ? 'active' : '' }}"
                        href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-3"></i> About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('services') ? 'active' : '' }}"
                        href="{{ route('services') }}">
                        <i class="fas fa-cogs me-3"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link py-2 {{ request()->routeIs('contact') ? 'active' : '' }}"
                        href="{{ route('contact') }}">
                        <i class="fas fa-phone-alt me-3"></i> Contact
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login to Ecommerce BD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Social Login Buttons -->
                    <div class="text-center mb-4">
                        <p class="text-muted mb-3">Login with social account</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('login.google') }}" class="btn btn-outline-danger btn-social">
                                <i class="fab fa-google me-2"></i> Google
                            </a>
                            <a href="{{ route('login.facebook') }}" class="btn btn-outline-primary btn-social">
                                <i class="fab fa-facebook me-2"></i> Facebook
                            </a>
                        </div>
                        <div class="divider my-4">
                            <span class="px-3 bg-white text-muted">or login with email</span>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <div class="mb-3">
                            <label for="login_email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="login_email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="login_password" class="form-label">Password *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="login_password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                Forgot your password?
                            </a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <p class="mb-0">Don't have an account?
                        <button class="text-primary bg-transparent border-0" data-bs-target="#registerModal"
                            data-bs-toggle="modal">Register
                            here</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Create Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Social Register Buttons -->
                    <div class="text-center mb-4">
                        <p class="text-muted mb-3">Register with social account</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('login.google') }}" class="btn btn-outline-danger btn-social">
                                <i class="fab fa-google me-2"></i> Google
                            </a>
                            <a href="{{ route('login.facebook') }}" class="btn btn-outline-primary btn-social">
                                <i class="fab fa-facebook me-2"></i> Facebook
                            </a>
                        </div>
                        <div class="divider my-4">
                            <span class="px-3 bg-white text-muted">or register with email</span>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank"
                                    class="text-decoration-none">Terms & Conditions</a>
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-center">
                    <p class="mb-0">Already have an account?
                        <button class="text-primary bg-transparent border-0" data-bs-target="#loginModal"
                            data-bs-toggle="modal">Login
                            here</button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="cartOffcanvasLabel">
                <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div id="cartOffcanvasContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas-footer border-top p-3" id="cartOffcanvasFooter" style="display: none;">
            <div class="d-flex justify-content-between mb-3">
                <span class="fw-bold">Subtotal:</span>
                <span class="fw-bold" id="cartOffcanvasSubtotal">৳0.00</span>
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                    <i class="fas fa-check me-2"></i>Proceed to Checkout
                </a>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-shopping-cart me-2"></i>View Cart
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    @include('layouts.footer')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Pusher & Laravel Echo for Real-Time Chat -->
    @auth
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
        <script>
            // Initialize Laravel Echo for real-time broadcasting
            if (typeof Pusher !== 'undefined' &&
                '{{ config('broadcasting.default') }}' === 'pusher') {
                window.Pusher = Pusher;

                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: '{{ config('broadcasting.connections.pusher.key ') }}',
                    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                    forceTLS: true,
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }
                });

                // Function to setup chat listener after chatId is available
                window.setupChatListener = function(chatId) {
                    if (!chatId || !window.Echo) {
                        return;
                    }

                    // Leave previous channel if exists
                    if (window.activeChatChannel) {
                        window.Echo.leave(window.activeChatChannel);
                    }

                    // Setup new chat channel listener
                    window.activeChatChannel = `chat.${chatId}`;

                    Echo.private(window.activeChatChannel)
                        .listen('.message.sent', (e) => {
                            // Check if message already exists
                            const exists = chatMessages.some(m => m.id === e.id);
                            if (!exists) {
                                // Add message to chat
                                chatMessages.push({
                                    id: e.id,
                                    chat_id: e.chat_id,
                                    user_id: e.user_id,
                                    message: e.message,
                                    created_at: e.created_at || new Date().toISOString(),
                                    user: {
                                        name: e.user_name
                                    }
                                });

                                // Render messages
                                renderMessages();
                            }

                            // Update unread count
                            fetchUnreadCount();

                            // Play notification sound
                            playNotificationSound();

                            // Show browser notification if available
                            if (Notification.permission === 'granted' && !isDialogOpen) {
                                new Notification('New Message', {
                                    body: e.message,
                                    icon: '/favicon.ico'
                                });
                            }
                        });
                };

                // Listen for user-specific notifications (fallback for all chats)
                Echo.private(`user.{{ auth()->id() }}`)
                    .listen('.message.sent', (e) => {
                        // If this is the current active chat, add message to UI
                        if (chatId && e.chat_id === chatId) {
                            // Check if message already exists (avoid duplicates)
                            const exists = chatMessages.some(m => m.id === e.id);
                            if (!exists) {
                                chatMessages.push({
                                    id: e.id,
                                    chat_id: e.chat_id,
                                    user_id: e.user_id,
                                    message: e.message,
                                    created_at: e.created_at || new Date().toISOString(),
                                    user: {
                                        name: e.user_name
                                    }
                                });

                                // Render messages
                                renderMessages();
                            }
                        }

                        // Always update unread count
                        fetchUnreadCount();
                    });
            }
        </script>
    @endauth

    @yield('scripts')
    <script>
        // Responsive search bar toggle for mobile (<=540px)
        document.addEventListener('DOMContentLoaded', function() {
            const searchIcon = document.getElementById('responsiveSearchIcon');
            const searchContainer = searchIcon ? searchIcon.closest('.search-container') : null;
            const searchInput = searchContainer ? searchContainer.querySelector('.search-input') : null;
            const searchForm = document.getElementById('responsiveSearchForm');

            function isMobile() {
                return window.innerWidth <= 540;
            }

            if (searchIcon && searchContainer && searchInput) {
                // Show input on icon click (mobile only)
                searchIcon.addEventListener('click', function(e) {
                    if (!isMobile()) return;
                    searchContainer.classList.toggle('show-input');
                    if (searchContainer.classList.contains('show-input')) {
                        searchInput.style.display = 'block';
                        searchInput.focus();
                    } else {
                        searchInput.style.display = 'none';
                    }
                    e.stopPropagation();
                });

                // Hide input when clicking outside
                document.addEventListener('click', function(e) {
                    if (!isMobile()) return;
                    if (searchContainer.classList.contains('show-input')) {
                        if (!searchContainer.contains(e.target)) {
                            searchContainer.classList.remove('show-input');
                            searchInput.style.display = 'none';
                        }
                    }
                });

                // Prevent form click from closing
                searchForm.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Hide input on resize if not mobile
                window.addEventListener('resize', function() {
                    if (!isMobile()) {
                        searchContainer.classList.remove('show-input');
                        searchInput.style.display = '';
                    } else {
                        searchInput.style.display = searchContainer.classList.contains('show-input') ?
                            'block' : 'none';
                    }
                });
            }
        });
    </script>
    <script>
        // Global auth enforcement across all pages
        (function() {
            const IS_AUTH = {
                auth: {{ auth()->check() ? 'true' : 'false' }}
            };
            const wishlistUrl = '{{ route('wishlist.index') }}';
            const cartUrl = '{{ route('cart.index') }}';

            function requireLoginPrompt() {
                const loginModalEl = document.getElementById('loginModal');
                if (loginModalEl) {
                    const loginModal = new bootstrap.Modal(loginModalEl);
                    loginModal.show();
                }
                if (window.Toast) {
                    window.Toast.fire({
                        icon: 'warning',
                        title: 'Please login to continue'
                    });
                }
            }

            // Capture-phase guard to block before page-specific handlers
            document.addEventListener('click', function(e) {
                if (IS_AUTH) return; // no-op if logged in

                // Guard header wishlist/cart links
                const headerLink = e.target.closest('a.header-icon');
                if (headerLink) {
                    const href = headerLink.getAttribute('href') || '';
                    if (href === wishlistUrl || href === cartUrl) {
                        e.preventDefault();
                        e.stopPropagation();
                        requireLoginPrompt();
                        return;
                    }
                }

                // Guard product actions
                if (e.target.closest('.add-to-cart-btn') || e.target.closest('.wishlist-btn')) {
                    e.preventDefault();
                    e.stopPropagation();
                    requireLoginPrompt();
                    return;
                }
            }, true);
        })();
    </script>
    <script>
        // Handle add to cart with login check
        // Handle add to cart with login check
        // Add to cart function (use this in all your blade files)
        function addToCart(productId, quantity = 1, attributes = {}) {
            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        attributes: attributes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        updateCartCount(data.cart_count);
                        // Show cart offcanvas
                        showCartOffcanvas();
                    } else if (data.requires_login) {
                        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                        loginModal.show();
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please login to add items to cart'
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to add to cart'
                        });
                    }
                })
                .catch(error => {
                    console.error('Add to cart error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Network error. Please try again.'
                    });
                });
        }

        // Show cart offcanvas and load cart items
        function showCartOffcanvas() {
            const offcanvas = new bootstrap.Offcanvas(document.getElementById('cartOffcanvas'));
            offcanvas.show();
            loadCartItems();
        }

        // Load cart items into offcanvas
        function loadCartItems() {
            const contentDiv = document.getElementById('cartOffcanvasContent');
            const footerDiv = document.getElementById('cartOffcanvasFooter');

            // Show loading
            contentDiv.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            fetch(
                    '{{ route('cart.data') }}'
                )
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.items.length > 0) {
                        let cartHTML = '<div class="list-group list-group-flush">';

                        data.items.forEach(item => {
                            const itemTotal = (item.price * item.quantity).toFixed(2);
                            const attributes = item.attributes && Object.keys(item.attributes).length > 0 ?
                                Object.entries(item.attributes).map(([key, value]) =>
                                    `<small class="text-muted">${key}: ${value}</small>`
                                ).join(' | ') :
                                '';

                            cartHTML += `
                                <div class="list-group-item" data-cart-item-id="${item.hashid}">
                                    <div class="d-flex gap-3 position-relative">
                                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                style="padding: 2px 6px; font-size: 12px; z-index: 10;"
                                                onclick="removeCartItem('${item.hashid}')"
                                                title="Remove item">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <img src="${item.image}" alt="${item.product_name}"
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;"
                                             onerror="this.src='{{ asset('assets/images/no-image.png') }}'">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 pe-4" style="font-size: 14px;">${item.product_name}</h6>
                                            ${attributes ? `<div class="mb-2">${attributes}</div>` : ''}
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            style="padding: 2px 8px;"
                                                            onclick="updateCartQuantity('${item.hashid}', ${item.quantity - 1})"
                                                            ${item.quantity <= 1 ? 'disabled' : ''}>
                                                        <i class="fas fa-minus" style="font-size: 10px;"></i>
                                                    </button>
                                                    <span class="fw-semibold" style="min-width: 30px; text-align: center;">${item.quantity}</span>
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                            style="padding: 2px 8px;"
                                                            onclick="updateCartQuantity('${item.hashid}', ${item.quantity + 1})">
                                                        <i class="fas fa-plus" style="font-size: 10px;"></i>
                                                    </button>
                                                </div>
                                                <span class="fw-bold text-primary" style="font-size: 14px;">৳${itemTotal}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        cartHTML += '</div>';
                        contentDiv.innerHTML = cartHTML;

                        // Show subtotal and footer
                        document.getElementById('cartOffcanvasSubtotal').textContent = '৳' + data.subtotal.toFixed(2);
                        footerDiv.style.display = 'block';
                    } else {
                        contentDiv.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-3">Your cart is empty</p>
                                <a href="{{ route('shop') }}" class="btn btn-primary">
                                    <i class="fas fa-store me-2"></i>Continue Shopping
                                </a>
                            </div>
                        `;
                        footerDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading cart:', error);
                    contentDiv.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <p class="text-danger mb-3">Failed to load cart</p>
                            <button class="btn btn-primary" onclick="loadCartItems()">
                                <i class="fas fa-redo me-2"></i>Retry
                            </button>
                        </div>
                    `;
                    footerDiv.style.display = 'none';
                });
        }

        // Update cart item quantity
        function updateCartQuantity(hashid, newQuantity) {
            if (newQuantity < 1) return;

            // Show loading on the specific item
            const itemElement = document.querySelector(`[data-cart-item-id="${hashid}"]`);
            if (itemElement) {
                itemElement.style.opacity = '0.5';
            }

            fetch(`/cart/update/${hashid}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Cart updated!',
                            timer: 1500
                        });
                        // Reload cart items
                        loadCartItems();
                        // Update header cart count
                        updateCartCount(data.cart_count);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to update cart'
                        });
                        if (itemElement) {
                            itemElement.style.opacity = '1';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating cart:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Network error. Please try again.'
                    });
                    if (itemElement) {
                        itemElement.style.opacity = '1';
                    }
                });
        }

        // Remove item from cart
        function removeCartItem(hashid) {
            if (!confirm('Remove this item from cart?')) return;

            // Show loading on the specific item
            const itemElement = document.querySelector(`[data-cart-item-id="${hashid}"]`);
            if (itemElement) {
                itemElement.style.opacity = '0.5';
            }

            fetch(`/cart/remove/${hashid}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Item removed from cart!',
                            timer: 1500
                        });
                        // Reload cart items
                        loadCartItems();
                        // Update header cart count
                        updateCartCount(data.cart_count);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Failed to remove item'
                        });
                        if (itemElement) {
                            itemElement.style.opacity = '1';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error removing item:', error);
                    Toast.fire({
                        icon: 'error',
                        title: 'Network error. Please try again.'
                    });
                    if (itemElement) {
                        itemElement.style.opacity = '1';
                    }
                });
        }

        // Update cart count in header
        function updateCartCount(count) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch cart count if user is logged in
            @if (auth()->check() && auth()->user()->role === 'customer')
                updateCartCount({
                    {
                        auth() - > user() - > cart() - > count()
                    }
                });
            @endif
        });
    </script>
    <script>
        // Google Translate functions
        let googleTranslateInitialized = false;
        let currentLanguage = 'en'; // Default to English

        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en', // Default page language to English
                includedLanguages: 'en,bn,es,fr,de,zh-CN,hi,ar,ja,ko', // Put English first
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL,
                autoDisplay: false,
                multilanguagePage: true,
                gaTrack: true,
                gaId: 'UA-XXXXXXXXX-X' // Add your Google Analytics ID if needed
            }, 'google_translate_element');

            googleTranslateInitialized = true;
            // Hide the Google Translate toolbar
            hideGoogleTranslateToolbar();
        }

        // Load Google Translate script
        function loadGoogleTranslate() {
            if (!window.googleTranslateElementInit) {
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
                document.head.appendChild(script);
            } else {
                // If already loaded, just initialize
                googleTranslateElementInit();
            }
        }

        // Change language using Google Translate
        function changeGoogleTranslate(lang) {
            // Show loading indicator
            Toast.fire({
                icon: 'info',
                title: 'Changing language...'
            });

            // Load Google Translate if not already loaded
            if (!googleTranslateInitialized) {
                loadGoogleTranslate();

                // Wait for Google Translate to load
                const checkInterval = setInterval(() => {
                    if (googleTranslateInitialized) {
                        clearInterval(checkInterval);
                        performLanguageChange(lang);
                    }
                }, 100);
            } else {
                performLanguageChange(lang);
            }
        }

        // Perform the actual language change
        function performLanguageChange(lang) {
            try {
                const select = document.querySelector('.goog-te-combo');
                if (select) {
                    select.value = lang;
                    select.dispatchEvent(new Event('change'));

                    // Update UI
                    updateLanguageButton(lang);

                    // Save preference to localStorage
                    localStorage.setItem('googleTranslateLanguage', lang);
                    currentLanguage = lang;

                    // Show success message
                    setTimeout(() => {
                        Toast.fire({
                            icon: 'success',
                            title: getLanguageName(lang) + ' selected'
                        });
                    }, 1000);
                }
            } catch (error) {
                console.error('Language change failed:', error);
                Toast.fire({
                    icon: 'error',
                    title: 'Language change failed'
                });
            }
        }

        // Update language button text and flag
        function updateLanguageButton(lang) {
            const languageData = {
                'en': { // Put English first
                    flag: 'https://flagcdn.com/w20/us.png',
                    name: 'English'
                },
                'bn': {
                    flag: 'https://flagcdn.com/w20/bd.png',
                    name: 'বাংলা'
                },
                'es': {
                    flag: 'https://flagcdn.com/w20/es.png',
                    name: 'Español'
                },
                'fr': {
                    flag: 'https://flagcdn.com/w20/fr.png',
                    name: 'Français'
                },
                'de': {
                    flag: 'https://flagcdn.com/w20/de.png',
                    name: 'Deutsch'
                },
                'zh-CN': {
                    flag: 'https://flagcdn.com/w20/cn.png',
                    name: '中文'
                },
                'hi': {
                    flag: 'https://flagcdn.com/w20/in.png',
                    name: 'हिन्दी'
                },
                'ar': {
                    flag: 'https://flagcdn.com/w20/sa.png',
                    name: 'العربية'
                },
                'ja': {
                    flag: 'https://flagcdn.com/w20/jp.png',
                    name: '日本語'
                },
                'ko': {
                    flag: 'https://flagcdn.com/w20/kr.png',
                    name: '한국어'
                }
            };

            const langData = languageData[lang] || languageData['en']; // Default to English
            const button = document.querySelector('.language-btn');

            if (button) {
                const img = button.querySelector('img');
                const span = button.querySelector('span');

                if (img) img.src = langData.flag;
                if (span) span.textContent = langData.name;
            }
        }

        // Get language name
        function getLanguageName(lang) {
            const languages = {
                'en': 'English', // Put English first
                'bn': 'Bangla',
                'es': 'Spanish',
                'fr': 'French',
                'de': 'German',
                'zh-CN': 'Chinese',
                'hi': 'Hindi',
                'ar': 'Arabic',
                'ja': 'Japanese',
                'ko': 'Korean'
            };
            return languages[lang] || 'English'; // Default to English
        }

        // Hide Google Translate toolbar
        function hideGoogleTranslateToolbar() {
            // Hide the default Google Translate toolbar
            const style = document.createElement('style');
            style.innerHTML = `
            .goog-te-banner-frame {
                display: none !important;
            }
            .goog-te-menu-value span {
                color: #333 !important;
            }
            .goog-te-menu-value {
                color: #333 !important;
            }
            .goog-te-gadget {
                font-family: 'Roboto', sans-serif !important;
            }
            .goog-te-combo {
                padding: 5px !important;
                border-radius: 5px !important;
                border: 1px solid #ddd !important;
            }
        `;
            document.head.appendChild(style);

            // Remove the "Powered by Google" text
            setTimeout(() => {
                const poweredBy = document.querySelector('.goog-logo-link');
                if (poweredBy) poweredBy.style.display = 'none';

                const text = document.querySelector('.goog-te-gadget');
                if (text) {
                    const child = text.querySelector('span');
                    if (child) child.style.display = 'none';
                }
            }, 500);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for saved language preference
            const savedLang = localStorage.getItem('googleTranslateLanguage') || 'en'; // Default to English
            currentLanguage = savedLang;

            // Update button to show saved language
            updateLanguageButton(savedLang);

            // Initialize Google Translate on first click
            document.querySelector('.language-btn').addEventListener('click', function(e) {
                // Only load Google Translate if not already initialized
                if (!googleTranslateInitialized) {
                    // Prevent dropdown from opening immediately
                    e.stopPropagation();

                    // Show loading message
                    Toast.fire({
                        icon: 'info',
                        title: 'Loading...'
                    });

                    loadGoogleTranslate();

                    // Wait a bit then open the dropdown
                    setTimeout(() => {
                        const dropdown = new bootstrap.Dropdown(this);
                        dropdown.show();
                    }, 500);
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.language-selector')) {
                    const dropdown = bootstrap.Dropdown.getInstance(document.querySelector(
                        '.language-btn'));
                    if (dropdown) dropdown.hide();
                }
            });

            // Add RTL support for Arabic
            function updatePageDirection(lang) {
                if (lang === 'ar') {
                    document.documentElement.dir = 'rtl';
                    document.documentElement.lang = 'ar';

                    // Add RTL styles
                    const rtlStyle = document.createElement('style');
                    rtlStyle.innerHTML = `
                    [dir="rtl"] .text-start { text-align: right !important; }
                    [dir="rtl"] .text-end { text-align: left !important; }
                    [dir="rtl"] .ms-1 { margin-left: 0 !important; margin-right: 0.25rem !important; }
                    [dir="rtl"] .ms-2 { margin-left: 0 !important; margin-right: 0.5rem !important; }
                    [dir="rtl"] .me-1 { margin-right: 0 !important; margin-left: 0.25rem !important; }
                    [dir="rtl"] .me-2 { margin-right: 0 !important; margin-left: 0.5rem !important; }
                    [dir="rtl"] .ps-1 { padding-left: 0 !important; padding-right: 0.25rem !important; }
                    [dir="rtl"] .pe-1 { padding-right: 0 !important; padding-left: 0.25rem !important; }
                `;
                    document.head.appendChild(rtlStyle);
                } else {
                    document.documentElement.dir = 'ltr';
                    document.documentElement.lang = lang;
                }
            }

            // Initialize page direction
            updatePageDirection(savedLang);

            // Listen for Google Translate language changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'lang') {
                        const newLang = document.documentElement.lang;
                        if (newLang !== currentLanguage) {
                            currentLanguage = newLang;
                            updateLanguageButton(newLang);
                            updatePageDirection(newLang);
                            localStorage.setItem('googleTranslateLanguage', newLang);
                        }
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['lang']
            });
        });

        // Handle Google Translate callback errors
        window.googleTranslateError = function(error) {
            console.error('Google Translate error:', error);
            Toast.fire({
                icon: 'error',
                title: 'Google Translate failed to load'
            });
        };

        // Alternative method for language change (if Google Translate widget fails)
        function translatePage(lang) {
            // Get all text nodes and translate them
            // This is a fallback method - not as good as Google Translate
            const textElements = document.querySelectorAll(
                'p, span, h1, h2, h3, h4, h5, h6, a, button, li, td, th, label, input[placeholder]');

            // In a real implementation, you would have translation data
            // For now, just show a message
            Toast.fire({
                icon: 'info',
                title: 'Language changing...'
            });

            // Save the language preference
            localStorage.setItem('preferredLanguage', lang);
            updateLanguageButton(lang);
        }
    </script>
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            this.classList.toggle('active');
            document.getElementById('mobileMenu').classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileToggle = document.getElementById('mobileMenuToggle');

            if (!mobileMenu.contains(event.target) && !mobileToggle.contains(event.target)) {
                mobileMenu.classList.remove('show');
                mobileToggle.classList.remove('active');
            }
        });

        // Update cart and wishlist counts (simulated)
        function updateCartCount(count) {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = count;
                cartCount.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        function updateWishlistCount(count) {
            const wishlistCount = document.querySelector('.wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = count;
                wishlistCount.style.display = count > 0 ? 'flex' : 'none';
            }
        }

        // Marquee pause on hover
        const marqueeContent = document.querySelector('.marquee-content');
        if (marqueeContent) {
            marqueeContent.addEventListener('mouseenter', function() {
                this.style.animationPlayState = 'paused';
            });

            marqueeContent.addEventListener('mouseleave', function() {
                this.style.animationPlayState = 'running';
            });
        }
    </script>

    <script>
        // Form Validation
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    this.classList.add('was-validated');
                });
            }

            if (registerForm) {
                registerForm.addEventListener('submit', function(e) {
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('password_confirmation').value;
                    const terms = document.getElementById('terms').checked;

                    if (!terms) {
                        e.preventDefault();
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please accept the terms and conditions'
                        });
                        return;
                    }

                    if (password !== confirmPassword) {
                        e.preventDefault();
                        Toast.fire({
                            icon: 'error',
                            title: 'Passwords do not match'
                        });
                        return;
                    }

                    if (!this.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    this.classList.add('was-validated');
                });
            }

            // Show modal on authentication errors
            @if ($errors->has('email') || $errors->has('password'))
                const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            @endif

            @if ($errors->has('name') || $errors->has('email') || $errors->has('password'))
                const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
                registerModal.show();
            @endif
        });
    </script>
    <!-- Initialize SweetAlert -->
    <script>
        window.addEventListener('load', function() {
            // Global SweetAlert configuration
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            // Make Toast globally available
            window.Toast = Toast;
        });
    </script>

    <!-- Flash Messages -->
    @if (session('success'))
        <script>
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timer = document.querySelector('[data-deal-end]');
            if (!timer) return;

            const endTimestamp = parseInt(timer.dataset.dealEnd) * 1000;

            function updateTimer() {
                const diff = endTimestamp - Date.now();

                if (diff <= 0) {
                    timer.querySelector('.hours').innerText = '00';
                    timer.querySelector('.minutes').innerText = '00';
                    timer.querySelector('.seconds').innerText = '00';
                    return;
                }

                timer.querySelector('.hours').innerText =
                    Math.floor(diff / 3600000);

                timer.querySelector('.minutes').innerText =
                    Math.floor((diff / 60000) % 60);

                timer.querySelector('.seconds').innerText =
                    Math.floor((diff / 1000) % 60);
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        });
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Loader Hide Script -->
    <script>
        // Hide loader when everything is ready
        window.addEventListener('load', function() {
            setTimeout(function() {
                var loader = document.getElementById('global-loader');
                if (loader) {
                    loader.style.opacity = '0';
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 400);
                }
            }, 300); // You can adjust the delay if needed
        });
    </script>

    <!-- Chat Widget (Only for authenticated users) -->
    @auth
        <div id="chatWidget" class="chat-widget">
            <!-- Chat Button -->
            <button class="chat-toggle-btn" id="chatToggleBtn" onclick="toggleChat()">
                <i class="fas fa-comments"></i>
                <span class="chat-unread-badge" id="chatUnreadBadge" style="display: none;">0</span>
            </button>

            <!-- Chat Dialog -->
            <div class="chat-dialog" id="chatDialog" style="display: none;">
                <div class="chat-header">
                    <div class="chat-header-title">
                        <i class="fas fa-headset"></i>
                        <span>Customer Support</span>
                    </div>
                    <button class="chat-close-btn" onclick="toggleChat()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="chat-body" id="chatBody">
                    <div class="chat-messages" id="chatMessages">
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>

                <div class="chat-footer">
                    <form id="chatMessageForm" onsubmit="sendMessage(event)">
                        <div class="input-group">
                            <input type="text" class="form-control" id="chatMessageInput"
                                placeholder="Type your message..." required maxlength="5000">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- Chat JavaScript -->
        <script>
            let chatId = null;
            let chatMessages = [];
            let isDialogOpen = false;

            // Global functions (accessible from onclick handlers)
            function toggleChat() {
                const dialog = document.getElementById('chatDialog');
                const btn = document.getElementById('chatToggleBtn');

                isDialogOpen = !isDialogOpen;

                if (isDialogOpen) {
                    dialog.style.display = 'flex';
                    btn.style.display = 'none';
                    if (chatId) {
                        loadMessages();
                    }
                } else {
                    dialog.style.display = 'none';
                    btn.style.display = 'flex';
                }
            }

            async function sendMessage(event) {
                event.preventDefault();

                if (!chatId) {
                    await initializeChat();
                }

                const input = document.getElementById('chatMessageInput');
                const message = input.value.trim();

                if (!message) return;

                try {
                    const response = await fetch(`/chat/${chatId}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            message
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        // Ensure created_at field exists
                        if (!data.message.created_at) {
                            data.message.created_at = new Date().toISOString();
                        }
                        chatMessages.push(data.message);
                        renderMessages();
                        input.value = '';
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    if (window.Toast) {
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to send message'
                        });
                    }
                }
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function updateUnreadBadge(count) {
                const badge = document.getElementById('chatUnreadBadge');
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }

            function playNotificationSound() {
                const audio = new Audio(
                    'data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZizcIG2m98OScTgwOUKXh8LJnHAU7ktjxzn0wAy+Fzvjf'
                );
                audio.play().catch(e => console.log('Audio play failed:', e));
            }

            async function initializeChat() {
                try {
                    const response = await fetch('/chat/get-or-create', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        chatId = data.chat.id;
                        chatMessages = data.chat.messages || [];
                        renderMessages();
                        updateUnreadBadge(data.unread_count);

                        // Setup real-time listener for this chat
                        if (typeof window.setupChatListener === 'function') {
                            window.setupChatListener(chatId);
                        }
                    }
                } catch (error) {
                    console.error('Error initializing chat:', error);
                }
            }

            async function loadMessages() {
                if (!chatId) return;

                try {
                    const response = await fetch(`/chat/${chatId}/messages`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        chatMessages = data.messages;
                        renderMessages();
                        updateUnreadBadge(0);
                    }
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            function renderMessages() {
                const container = document.getElementById('chatMessages');
                if (!container) {
                    console.error('❌ Chat messages container not found!');
                    return;
                }

                const currentUserId = {
                    {
                        auth() - > id()
                    }
                };

                if (chatMessages.length === 0) {
                    container.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-comments fa-2x mb-2"></i>
                        <p>No messages yet. Start a conversation!</p>
                    </div>
                `;
                    return;
                }

                container.innerHTML = chatMessages.map(msg => {
                    const isSent = msg.user_id === currentUserId;
                    const initials = msg.user.name.split(' ').map(n => n[0]).join('').toUpperCase();
                    const time = new Date(msg.created_at).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    return `
                    <div class="chat-message ${isSent ? 'sent' : ''}">
                        <div class="chat-message-avatar">${initials}</div>
                        <div class="chat-message-content">
                            <div class="chat-message-bubble">${escapeHtml(msg.message)}</div>
                            <div class="chat-message-time">${time}</div>
                        </div>
                    </div>
                `;
                }).join('');

                // Scroll the BODY container (not the messages container)
                const chatBody = document.getElementById('chatBody');
                if (chatBody) {
                    // Force scroll immediately
                    chatBody.scrollTop = chatBody.scrollHeight;
                    // Also with slight delay for DOM render
                    setTimeout(() => {
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }, 100);
                    // Extra safety scroll
                    setTimeout(() => {
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }, 300);
                } else {
                    console.error('❌ chatBody container not found!');
                }
            }

            async function fetchUnreadCount() {
                try {
                    const response = await fetch('/chat/unread-count', {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateUnreadBadge(data.unread_count);
                    }
                } catch (error) {
                    console.error('Error fetching unread count:', error);
                }
            }

            // Initialize chat on page load
            document.addEventListener('DOMContentLoaded', function() {
                initializeChat();
                fetchUnreadCount();

                // Poll for new messages every 5 seconds (fallback)
                setInterval(fetchUnbodyCount, 5000);
            });
        </script>
    @endauth

</body>

</html>
