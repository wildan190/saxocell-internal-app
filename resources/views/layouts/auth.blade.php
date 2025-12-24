<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Authentication')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(139, 92, 246, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            min-height: 600px;
            width: 100%;
            max-width: 1200px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            animation: slideIn 0.6s ease-out;
            z-index: 1;
        }

        .auth-left {
            background-image: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            border-radius: 24px 0 0 24px;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
            border-radius: 24px 0 0 24px;
        }

        .auth-left-content {
            position: relative;
            z-index: 1;
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
            text-align: center;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 24px 0 0 24px;
        }

        .auth-branding {
            margin-bottom: 2rem;
        }

        .auth-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .auth-logo::before {
            content: 'S';
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
        }

        .auth-left-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .auth-left-subtitle {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .auth-features {
            margin-top: 2rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .feature-icon::before {
            content: '✓';
            color: white;
            font-weight: 600;
        }

        .feature-text {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .auth-right {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-form-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .auth-form-subtitle {
            color: #64748b;
            font-size: 1rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        .auth-logo::before {
            content: 'S';
            font-size: 2rem;
            font-weight: 700;
            color: white;
        }

        .auth-title {
            color: #1e293b;
            font-size: 1.875rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            letter-spacing: -0.025em;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 1rem;
            margin: 0;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            color: #1e293b;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-check-input {
            width: 1.125rem;
            height: 1.125rem;
            border: 2px solid #d1d5db;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-check-input:checked {
            background: #3b82f6;
            border-color: #3b82f6;
        }

        .form-check-label {
            color: #374151;
            font-size: 0.875rem;
            cursor: pointer;
            margin: 0;
        }

        .btn-primary {
            width: 100%;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .auth-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }

        .auth-links a {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-block;
            margin: 0.25rem 0;
        }

        .auth-links a:hover {
            color: #1d4ed8;
            transform: translateY(-1px);
        }

        .auth-links .divider {
            color: #9ca3af;
            margin: 0 0.5rem;
            font-size: 0.75rem;
        }

        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }

        .form-description {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        @media (max-width: 1024px) {
            .auth-container {
                grid-template-columns: 1fr;
                max-width: 420px;
                min-height: auto;
            }

            .auth-left {
                border-radius: 24px 24px 0 0;
                padding: 2rem;
                text-align: center;
            }

            .auth-left-title {
                font-size: 2rem;
            }

            .auth-right {
                padding: 2rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-container,
            .btn-primary,
            .form-control {
                animation: none;
                transition: none;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-left-content">
                <div class="auth-branding">
                    <div class="auth-logo"></div>
                    <h1 class="auth-left-title">@yield('left-title', 'Welcome to Our Platform')</h1>
                    <p class="auth-left-subtitle">@yield('left-subtitle', 'Experience the future of digital solutions with our cutting-edge platform designed for modern businesses.')</p>
                </div>

                <div class="auth-features">
                    <div class="feature-item">
                        <div class="feature-icon"></div>
                        <span class="feature-text">Secure & Reliable Authentication</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"></div>
                        <span class="feature-text">Advanced User Management</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon"></div>
                        <span class="feature-text">Enterprise-Grade Security</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-header">
                <h2 class="auth-form-title">@yield('title', 'Welcome Back')</h2>
                <p class="auth-form-subtitle">@yield('subtitle', 'Sign in to your account')</p>
            </div>

            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-message">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
