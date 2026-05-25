<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pemesanan - {{ $rental->booking_reference }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { accent: { '50': '#fef2f2', '100': '#fee2e2', '200': '#fecaca', '300': '#fca5a5', '400': '#f94b4b', '500': '#ef4444', '600': '#dc2626', '700': '#b91c1c', '800': '#991b1b', '900': '#7f1d1d' } } } } }
    </script>
    <style>
        @media print {
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-lg mx-auto mt-8 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="text-center border-b border-gray-200 pb-6">
                <h1 class="text-xl font-bold text-gray-900">CAR RENTAL INDONESIA</h1>
                <p class="text-sm text-gray-500 mt-1">Jl. Merdeka No. 123, Jakarta</p>
                <p class="text-sm text-gray-500">Telp: 6281234567890</p>
            </div>

            <div class="text-center mt-6">
                <h2 class="text-lg font-semibold text-gray-900 uppercase tracking-wide">Bukti Pemesanan</h2>
            </div>

            <div class="mt-6 bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">No. Referensi</p>
                        <p class="text-lg font-bold text-gray-900">{{ $rental->booking_reference }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-blue-100 text-blue-800',
                                'active' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-gray-100 text-gray-600',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $statusColor = $statusColors[$rental->status] ?? 'bg-gray-100 text-gray-600';
                            $statusLabels = [
                                'pending' => 'Pending',
                                'confirmed' => 'Dikonfirmasi',
                                'active' => 'Aktif',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ];
                            $statusLabel = $statusLabels[$rental->status] ?? ucfirst($rental->status);
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">{{ $statusLabel }}</span>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">{{ $rental->created_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</p>
            </div>

            <div class="mt-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Data Pelanggan</h3>
                <div class="text-sm space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama</span>
                        <span class="text-gray-900 font-medium">{{ $rental->user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="text-gray-900">{{ $rental->user->email }}</span>
                    </div>
                    @if ($customerPerson && $customerPerson->phone)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telepon</span>
                        <span class="text-gray-900">{{ $customerPerson->phone }}</span>
                    </div>
                    @endif
                    @if ($customerPerson && $customerPerson->address)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Alamat</span>
                        <span class="text-gray-900 text-right max-w-[60%]">{{ $customerPerson->address }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Data Kendaraan</h3>
                <div class="text-sm space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kendaraan</span>
                        <span class="text-gray-900 font-medium">{{ $rental->vehicle->name }} ({{ $rental->vehicle->year }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Plat Nomor</span>
                        <span class="text-gray-900">{{ $rental->vehicle->license_plate }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Transmisi</span>
                        <span class="text-gray-900">{{ __($rental->vehicle->transmission) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Opsi Sopir</span>
                        <span class="text-gray-900">{{ $rental->vehicle->requires_driver ? 'Dengan Sopir' : 'Tanpa Sopir' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Detail Sewa</h3>
                <div class="text-sm space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ambil</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($rental->start_date)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kembali</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($rental->end_date)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Durasi</span>
                        @php
                            $totalHours = (int) abs(\Carbon\Carbon::parse($rental->end_date)->diffInHours(\Carbon\Carbon::parse($rental->start_date)));
                            $totalBlocks = (int) ceil($totalHours / 12);
                        @endphp
                        <span class="text-gray-900">{{ $totalHours }} jam ({{ $totalBlocks }} blok)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Antar</span>
                        <span class="text-gray-900">{{ $rental->delivery_method === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</span>
                    </div>
                    @if ($rental->delivery_method === 'delivery' && $rental->delivery_address)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Alamat Antar</span>
                        <span class="text-gray-900 text-right max-w-[60%]">{{ $rental->delivery_address }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-500">Supir</span>
                        <span class="text-gray-900">{{ $rental->driver_id ? $rental->driver?->user?->name : 'Tanpa Supir' }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Rincian Biaya</h3>
                <div class="text-sm space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sewa ({{ $totalBlocks }} blok &times; Rp {{ number_format($rental->ratePerBlock ?? (int) ceil($rental->rental_rate_per_day / 2), 0, ',', '.') }})</span>
                        <span class="text-gray-900">Rp {{ number_format($totalBlocks * (int) ceil($rental->rental_rate_per_day / 2), 0, ',', '.') }}</span>
                    </div>
                    @if ($rental->driver_fee_per_day)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Biaya Supir</span>
                        <span class="text-gray-900">Rp {{ number_format($rental->driver_fee_per_day * ($totalHours >= 24 ? ($totalHours / 24) : 0.5), 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                <div class="flex justify-between font-bold text-base pt-3 mt-3 border-t border-gray-200">
                    <span class="text-gray-900">Total</span>
                    <span class="text-gray-900">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-5">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Pembayaran</h3>
                <div class="text-sm space-y-1">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Metode</span>
                        <span class="text-gray-900">{{ $payment?->method === 'cash' ? 'Tunai (bayar di tempat)' : 'Transfer Bank' }}</span>
                    </div>
                    @if ($payment && $payment->method === 'transfer' && $payment->bank)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Bank</span>
                        <span class="text-gray-900">{{ $payment->bank->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">No. Rekening</span>
                        <span class="text-gray-900">{{ $payment->bank->number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Atas Nama</span>
                        <span class="text-gray-900">{{ $payment->bank->account_holder }}</span>
                    </div>
                    @endif
                    @if ($payment)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($payment->date)->locale('id')->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 text-center text-sm text-gray-400 border-t border-gray-200 pt-6">
                <p class="text-gray-600 font-medium">Terima kasih telah melakukan pemesanan!</p>
                <p class="mt-1">Ada pertanyaan? Hubungi kami via WhatsApp</p>
                <p class="font-medium text-gray-500">6281234567890</p>
            </div>
        </div>

        <div class="flex justify-center gap-3 mt-4 no-print">
            <button onclick="window.print()" class="px-6 py-2.5 bg-accent-600 text-white text-sm font-medium rounded-lg hover:bg-accent-700 transition-colors shadow-sm">
                Cetak / Simpan PDF
            </button>
            <a href="{{ url('/cars') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                Kembali
            </a>
        </div>
    </div>
</body>
</html>
