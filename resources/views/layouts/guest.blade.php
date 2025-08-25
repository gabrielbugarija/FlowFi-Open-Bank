<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col bg-gray-100">
            <header class="flex justify-between items-center p-6">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <nav class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700">
                            {{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700">
                            {{ __('Log in') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm text-gray-700">
                                {{ __('Register') }}
                            </a>
                        @endif
                    @endauth
                </nav>
            </header>

            <div class="flex-grow flex flex-col sm:justify-center items-center">
                <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
