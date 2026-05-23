<x-layouts.app>
    <section class="bg-gradient-to-br from-green-700 to-green-900 text-white">
        <div class="max-w-7xl mx-auto px-4 py-20 text-center">
            <h1 class="text-5xl font-extrabold tracking-tight">{{ __('CarRental') }}</h1>
            <p class="mt-4 text-xl text-green-100 max-w-2xl mx-auto">
                {{ __('Sewa Mobil Mudah, Aman, Terpercaya') }}
            </p>
            <div class="mt-8 flex justify-center gap-4 flex-wrap">
                <a href="#how-it-works" class="px-6 py-3 border-2 border-white rounded-lg font-semibold hover:bg-white hover:text-green-800 transition">
                    {{ __('Cara Pesan') }}
                </a>
                <a href="#booking" class="px-6 py-3 bg-white text-green-800 rounded-lg font-semibold hover:bg-green-50 transition">
                    {{ __('Pesan Sekarang') }}
                </a>
            </div>
        </div>
    </section>

    <section id="how-it-works" class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">{{ __('Cara Pesan') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                    <h3 class="font-semibold text-gray-900">{{ __('Pilih Mobil') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Pilih mobil dan tanggal sewa yang sesuai kebutuhan Anda.') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                    <h3 class="font-semibold text-gray-900">{{ __('Ajukan Booking') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Isi data diri dan pilih metode pembayaran.') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                    <h3 class="font-semibold text-gray-900">{{ __('Upload Pembayaran') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Transfer dan unggah bukti pembayaran melalui dashboard.') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mx-auto mb-4 text-xl font-bold">4</div>
                    <h3 class="font-semibold text-gray-900">{{ __('Mobil Siap Digunakan') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('Admin konfirmasi, mobil siap Anda bawa pulang.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <livewire:car-listing />
</x-layouts.app>
