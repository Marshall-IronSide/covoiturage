<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Covoiturage') }}</title>

        <!-- CSS Professionnel -->
        <link rel="stylesheet" href="{{ asset('css/professional.css') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            html, body {
                height: 100%;
            }
            body {
                display: flex;
                flex-direction: column;
            }
            main {
                flex: 1;
            }
            footer {
                flex-shrink: 0;
            }
        </style>
    </head>
    <body>
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="container">
            @if ($errors->any())
                <div class="alert alert-danger" style="margin-top: 2rem;">
                    <strong>Erreurs:</strong>
                    <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="margin-top: 2rem;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white; padding: 3rem 0; text-align: center;">
            <p>&copy; 2026 Covoiturage. Tous droits réservés.</p>
        </footer>
    </body>
</html>
