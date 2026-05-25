<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Car Rental</title>
        @fonts
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
        @filamentStyles
        <link href="{{ asset('css/filament/filament/app.css') }}" rel="stylesheet">
        @livewireStyles
        <style>[x-cloak] { display: none; }</style>
    </head>
    <body
        x-data="{ scrolled: false, isHome: {{ request()->is('/') ? 'true' : 'false' }} }"
        x-on:scroll.window="scrolled = window.scrollY > 100"
        class="bg-gray-50 text-gray-900 min-h-screen antialiased"
    >
        <header
            :class="scrolled || !isHome ? 'bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm' : 'bg-transparent'"
            class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        >
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
                <a href="/"
                    :class="scrolled || !isHome ? 'text-accent-700' : 'text-white'"
                    class="text-xl font-bold shrink-0 transition-colors duration-300"
                >CarRental</a>

                {{-- Nav links (desktop only) --}}
                <div class="hidden md:flex items-center gap-6 text-sm font-medium"
                    :class="scrolled || !isHome ? 'text-gray-600' : 'text-white/80'"
                >
                    <a href="{{ route('home') }}#how-it-works" class="hover:opacity-80 transition-opacity">{{ __('Cara Pesan') }}</a>
                    <a href="{{ route('home') }}#testimonials" class="hover:opacity-80 transition-opacity">{{ __('Testimoni') }}</a>
                    <a href="{{ route('cars.index') }}" class="hover:opacity-80 transition-opacity">{{ __('Pesan Mobil') }}</a>
                </div>

                {{-- Right side: auth --}}
                <div class="flex items-center gap-4 text-sm shrink-0">
                    @auth
                        @if(auth()->user()->isCustomer() || auth()->user()->isEmployee())
                            <a href="{{ url(auth()->user()->isCustomer() ? '/dashboard' : '/employee') }}"
                                :class="scrolled || !isHome ? 'text-gray-600 hover:text-gray-900' : 'text-white/80 hover:text-white'"
                                class="transition-colors duration-300"
                            >Dashboard</a>
                        @else
                            <a href="{{ url('/admin') }}"
                                :class="scrolled || !isHome ? 'text-gray-600 hover:text-gray-900' : 'text-white/80 hover:text-white'"
                                class="transition-colors duration-300"
                            >Admin Panel</a>
                        @endif
                    @else
                        <a href="{{ url('/dashboard/login') }}"
                            :class="scrolled || !isHome ? 'text-gray-600 hover:text-gray-900' : 'text-white/80 hover:text-white'"
                            class="transition-colors duration-300"
                        >Log in</a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- Spacer for fixed header --}}
        @if (request()->is('/'))
            <div x-show="scrolled" x-cloak class="h-14"></div>
        @else
            <div class="h-14"></div>
        @endif

        <main>
            {{ $slot }}
        </main>
        @filamentScripts
        @livewireScripts
        @if (request()->is('/'))
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener"
                class="fixed bottom-6 right-6 z-[9999] w-14 h-14 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-200"
                title="Chat via WhatsApp">
                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </a>
        @endif
    </body>
</html>
