<x-layouts.app>
    <section class="bg-gradient-to-br from-accent-700 to-accent-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-32 text-center">
            <h1 class="text-5xl font-extrabold tracking-tight">{{ __('CarRental') }}</h1>
            <p class="mt-4 text-xl text-accent-100 max-w-2xl mx-auto">
                {{ __('Sewa Mobil Mudah, Aman, Terpercaya') }}
            </p>
            <div class="mt-8 flex justify-center gap-4 flex-wrap">
                <a href="#how-it-works" class="px-6 py-3 border-2 border-white rounded-lg font-semibold hover:bg-white hover:text-accent-800 transition">
                    {{ __('Cara Pesan') }}
                </a>
                <a href="#booking" class="px-6 py-3 bg-white text-accent-800 rounded-lg font-semibold hover:bg-accent-50 transition">
                    {{ __('Pesan Sekarang') }}
                </a>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="bg-[#1A2B4A] py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-white mb-4">{{ __('Cara Pesan') }}</h2>
            <div class="w-16 h-1 bg-accent-500 mx-auto mb-12 rounded-full"></div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-accent-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                    <h3 class="font-semibold text-white">{{ __('Pilih Mobil') }}</h3>
                    <p class="mt-2 text-sm text-gray-300">{{ __('Pilih mobil dan tanggal sewa yang sesuai kebutuhan Anda.') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-accent-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                    <h3 class="font-semibold text-white">{{ __('Ajukan Booking') }}</h3>
                    <p class="mt-2 text-sm text-gray-300">{{ __('Isi data diri dan pilih metode pembayaran.') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-accent-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                    <h3 class="font-semibold text-white">{{ __('Upload Pembayaran') }}</h3>
                    <p class="mt-2 text-sm text-gray-300">{{ __('Transfer dan unggah bukti pembayaran melalui dashboard.') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-accent-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">4</div>
                    <h3 class="font-semibold text-white">{{ __('Mobil Siap Digunakan') }}</h3>
                    <p class="mt-2 text-sm text-gray-300">{{ __('Admin konfirmasi, mobil siap Anda bawa pulang.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <livewire:car-listing />
</x-layouts.app>
