<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Covoiturage') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary: #1e40af;
                --primary-dark: #1e3a8a;
                --radius: 12px;
                --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
            
            body {
                font-family: 'Figtree', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                margin: 0;
            }
            
            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 1rem;
            }
            
            .auth-header {
                text-align: center;
                margin-bottom: 2rem;
                color: white;
            }
            
            .auth-brand {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                margin-bottom: 1rem;
            }
            
            .auth-brand-logo {
                font-size: 2.5rem;
            }
            
            .auth-brand-text {
                font-size: 1.875rem;
                font-weight: 700;
                color: white;
            }
            
            .auth-subtitle {
                font-size: 1rem;
                color: rgba(255, 255, 255, 0.9);
                margin-top: 0.5rem;
            }
            
            .auth-card {
                width: 100%;
                max-width: 450px;
                background: white;
                padding: 2rem;
                border-radius: var(--radius);
                box-shadow: var(--shadow-md), 0 20px 25px rgba(0, 0, 0, 0.15);
            }
            
            .form-group {
                margin-bottom: 1.25rem;
            }
            
            .form-label {
                display: block;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.5rem;
                font-size: 0.95rem;
            }
            
            .form-control {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1.5px solid #e5e7eb;
                border-radius: 8px;
                font-size: 1rem;
                transition: all 0.3s ease;
                box-sizing: border-box;
            }
            
            .form-control:focus {
                outline: none;
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
            }
            
            .form-error {
                color: #dc2626;
                font-size: 0.875rem;
                margin-top: 0.375rem;
            }
            
            .btn {
                padding: 0.75rem 1.5rem;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 0.95rem;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                color: white;
                width: 100%;
                padding: 0.875rem 1.5rem;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(30, 64, 175, 0.3);
            }
            
            .form-footer {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                margin-top: 1.5rem;
            }
            
            .form-link {
                text-align: center;
            }
            
            .form-link a {
                color: var(--primary);
                text-decoration: none;
                font-weight: 500;
                font-size: 0.9rem;
            }
            
            .form-link a:hover {
                text-decoration: underline;
            }
            
            .checkbox-group {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin: 1rem 0;
            }
            
            .checkbox-group input {
                width: 1.125rem;
                height: 1.125rem;
                cursor: pointer;
                accent-color: var(--primary);
            }
            
            .checkbox-group label {
                cursor: pointer;
                color: #6b7280;
                font-size: 0.9rem;
            }
            
            .auth-status {
                background: #d1fae5;
                color: #065f46;
                padding: 0.75rem 1rem;
                border-radius: 8px;
                margin-bottom: 1rem;
                font-size: 0.9rem;
                border-left: 4px solid #10b981;
            }
            
            @media (max-width: 640px) {
                .auth-card {
                    padding: 1.5rem;
                }
                
                .auth-brand-text {
                    font-size: 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-header">
                <div class="auth-brand">
                    <div class="auth-brand-logo">🚗</div>
                    <div class="auth-brand-text">Covoiturage</div>
                </div>
                <div class="auth-subtitle">Partagez vos trajets, économisez ensemble</div>
            </div>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
