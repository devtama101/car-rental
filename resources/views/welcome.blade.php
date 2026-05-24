<x-layouts.app>
    {{-- Hero --}}
    <section class="relative bg-white overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-accent-50/60 via-white to-accent-100/30"></div>
        <div class="relative max-w-7xl mx-auto px-4 py-28 md:py-36 text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-accent-700">
                {{ __('CarRental') }}
            </h1>
            <p class="mt-5 text-lg md:text-xl text-gray-600 max-w-xl mx-auto leading-relaxed">
                {{ __('Sewa Mobil Mudah, Aman, Terpercaya') }}
            </p>
            <div class="mt-9 flex justify-center gap-4 flex-wrap">
                <a href="#how-it-works"
                    class="px-7 py-3.5 border-2 border-accent-600 text-accent-600 rounded-xl font-semibold hover:bg-accent-600 hover:text-white transition duration-200">
                    {{ __('Cara Pesan') }}
                </a>
                <a href="#booking"
                    class="px-7 py-3.5 bg-accent-600 text-white rounded-xl font-semibold hover:bg-accent-700 transition duration-200 shadow-lg shadow-accent-200">
                    {{ __('Pesan Sekarang') }}
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="bg-white border-t border-gray-100 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900">{{ __('Cara Pesan') }}</h2>
                <div class="w-14 h-1 bg-accent-500 mx-auto mt-4 rounded-full"></div>
                <p class="mt-4 text-gray-500 max-w-md mx-auto">{{ __('Proses mudah dalam 4 langkah untuk mendapatkan mobil impian Anda.') }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $steps = [
                        ['num' => 1, 'title' => __('Pilih Mobil'), 'desc' => __('Pilih mobil dan tanggal sewa yang sesuai kebutuhan Anda.')],
                        ['num' => 2, 'title' => __('Ajukan Booking'), 'desc' => __('Isi data diri dan pilih metode pembayaran yang tersedia.')],
                        ['num' => 3, 'title' => __('Upload Pembayaran'), 'desc' => __('Transfer ke rekening yang tersedia lalu unggah buktinya.')],
                        ['num' => 4, 'title' => __('Mobil Siap Digunakan'), 'desc' => __('Booking dikonfirmasi admin, mobil siap Anda bawa pulang.')],
                    ];
                @endphp
                @foreach ($steps as $step)
                    <div class="text-center group">
                        <div class="w-14 h-14 bg-accent-600 text-white rounded-2xl flex items-center justify-center mx-auto mb-5 text-xl font-bold shadow-md shadow-accent-200 group-hover:scale-110 transition-transform">
                            {{ $step['num'] }}
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section id="testimonials" class="bg-white border-t border-gray-100 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold text-gray-900">{{ __('Testimoni Pelanggan') }}</h2>
                <div class="w-14 h-1 bg-accent-500 mx-auto mt-4 rounded-full"></div>
                <p class="mt-4 text-gray-500 max-w-md mx-auto">{{ __('Apa kata mereka yang sudah menggunakan layanan kami.') }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $testimonials = [
                        [
                            'name' => 'Budi Santoso',
                            'role' => __('Pelanggan'),
                            'quote' => __('Proses sewanya mudah banget. Tinggal pilih mobil, booking, transfer, langsung dikonfirmasi. Mobilnya bersih dan terawat!'),
                            'avatar' => 'BS',
                        ],
                        [
                            'name' => 'Siti Nurhaliza',
                            'role' => __('Pelanggan'),
                            'quote' => __('Pertama kali sewa online dan ternyata gampang. Adminnya responsif, mobil sampai tepat waktu. Recommended!'),
                            'avatar' => 'SN',
                        ],
                        [
                            'name' => 'Ahmad Rizki',
                            'role' => __('Pelanggan Setia'),
                            'quote' => __('Harga bersahabat, armada lengkap. Sudah 3 kali sewa di sini, selalu puas. Top markotop!'),
                            'avatar' => 'AR',
                        ],
                    ];
                @endphp
                @foreach ($testimonials as $t)
                    <div class="bg-gray-50 rounded-2xl p-7 border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex gap-1 mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-600 leading-relaxed mb-5 text-sm">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-accent-100 text-accent-700 rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $t['avatar'] }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $t['name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $t['role'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Car Listing --}}
    <livewire:car-listing />

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10 mb-12">
                {{-- Brand --}}
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">{{ __('CarRental') }}</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">{{ __('Solusi sewa mobil terpercaya dengan proses mudah, armada terawat, dan harga bersahabat.') }}</p>
                </div>

                {{-- Navigation --}}
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">{{ __('Navigasi') }}</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-white transition">{{ __('Cara Pesan') }}</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-white transition">{{ __('Testimoni') }}</a></li>
                        <li><a href="#booking" class="text-gray-400 hover:text-white transition">{{ __('Pesan Mobil') }}</a></li>
                        <li><a href="{{ url('/dashboard/login') }}" class="text-gray-400 hover:text-white transition">{{ __('Masuk') }}</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">{{ __('Kontak') }}</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="text-gray-400">{{ __('Jl. Raya No. 123, Jakarta Selatan') }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span class="text-gray-400">+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-gray-400">info@carrental.id</span>
                        </li>
                    </ul>
                </div>

                {{-- Social --}}
                <div>
                    <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">{{ __('Ikuti Kami') }}</h4>
                    <div class="flex gap-3">
                        <a href="https://facebook.com" target="_blank" rel="noopener"
                            class="w-10 h-10 bg-gray-800 hover:bg-accent-600 rounded-lg flex items-center justify-center transition"
                            title="Facebook">
                            <svg class="w-5 h-5 text-gray-400 hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener"
                            class="w-10 h-10 bg-gray-800 hover:bg-accent-600 rounded-lg flex items-center justify-center transition"
                            title="Instagram">
                            <svg class="w-5 h-5 text-gray-400 hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener"
                            class="w-10 h-10 bg-gray-800 hover:bg-accent-600 rounded-lg flex items-center justify-center transition"
                            title="Twitter / X">
                            <svg class="w-4 h-4 text-gray-400 hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener"
                            class="w-10 h-10 bg-gray-800 hover:bg-accent-600 rounded-lg flex items-center justify-center transition"
                            title="WhatsApp">
                            <svg class="w-5 h-5 text-gray-400 hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} {{ __('CarRental') }}. {{ __('All rights reserved.') }}</p>
                <div class="flex items-center gap-2">
                    <span>{{ __('Bahasa') }}:</span>
                    <a href="{{ route('language', 'en') }}" class="hover:text-white transition">EN</a>
                    <span>|</span>
                    <a href="{{ route('language', 'id') }}" class="hover:text-white transition">ID</a>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.app>
