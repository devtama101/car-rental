<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('Car Rental') }}</title>
        @fonts
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
        @filamentStyles
        <link href="{{ asset('css/filament/filament/app.css') }}" rel="stylesheet">
        @livewireStyles
    </head>
    <body class="bg-gray-50 text-gray-900 min-h-screen antialiased">
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                <a href="/" class="text-xl font-bold text-accent-700">{{ __('CarRental') }}</a>
                <nav class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-1 text-xs border-r border-gray-200 pr-4 mr-1">
                        <a href="{{ route('language', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-accent-700' : 'text-gray-400 hover:text-gray-600' }} no-underline">EN</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('language', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'font-bold text-accent-700' : 'text-gray-400 hover:text-gray-600' }} no-underline">ID</a>
                    </div>
                    @auth
                        @if(auth()->user()->isCustomer() || auth()->user()->isEmployee())
                            <a href="{{ url(auth()->user()->isCustomer() ? '/dashboard' : '/employee') }}" class="text-gray-600 hover:text-gray-900">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ url('/admin') }}" class="text-gray-600 hover:text-gray-900">{{ __('Admin Panel') }}</a>
                        @endif
                    @else
                        <a href="{{ url('/dashboard/login') }}" class="text-gray-600 hover:text-gray-900">{{ __('Log in') }}</a>
                    @endauth
                </nav>
            </div>
        </header>
        <main>
            {{ $slot }}
        </main>
        @filamentScripts
        @livewireScripts
    </body>
</html>
