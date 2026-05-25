<div class="max-w-7xl mx-auto px-4 py-8">
    @if ($selectedVehicleId)
        <livewire:booking-wizard :vehicleId="$selectedVehicleId" :key="$selectedVehicleId" />
    @else
        {{-- Filter bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <h3 class="text-sm font-semibold text-gray-900">{{ __('Filters') }}</h3>
                </div>
                <button
                    wire:click="resetFilters"
                    class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-accent-600 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('Reset') }}
                </button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Pengambilan/Pengantaran --}}
                <div>
                    <label for="listing-start-date" class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('Pengambilan/Pengantaran') }}</label>
                    <input
                        type="date"
                        id="listing-start-date"
                        wire:model.live="startDateDisplay"
                        onfocus="this.showPicker()"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-accent-500 focus:bg-white transition-colors"
                    >
                </div>

                {{-- Jam Pengambilan --}}
                <div>
                    <label for="listing-start-time" class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('Jam Pengambilan') }}</label>
                    <input
                        type="time"
                        id="listing-start-time"
                        wire:model.live="startTime"
                        onfocus="this.showPicker()"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-accent-500 focus:bg-white transition-colors"
                    >
                </div>

                {{-- Tanggal Pengembalian --}}
                <div>
                    <label for="listing-end-date" class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('Tanggal Pengembalian') }}</label>
                    <input
                        type="date"
                        id="listing-end-date"
                        wire:model.live="endDateDisplay"
                        onfocus="this.showPicker()"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-accent-500 focus:bg-white transition-colors"
                    >
                </div>

                {{-- Jam Pengembalian --}}
                <div>
                    <label for="listing-end-time" class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('Jam Pengembalian') }}</label>
                    <input
                        type="time"
                        id="listing-end-time"
                        wire:model.live="endTime"
                        onfocus="this.showPicker()"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm px-4 py-2.5 text-sm focus:border-accent-500 focus:ring-accent-500 focus:bg-white transition-colors"
                    >
                </div>

                {{-- Jenis Transmisi --}}
                <div>
                    <label for="listing-transmission" class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('Jenis Transmisi') }}</label>
                    <div class="relative">
                        <select
                            id="listing-transmission"
                            wire:model.live="transmission"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm pl-4 pr-10 py-2.5 text-sm focus:border-accent-500 focus:ring-accent-500 focus:bg-white transition-colors appearance-none"
                        >
                            <option value="">{{ __('Semua') }}</option>
                            <option value="automatic">{{ __('Automatic') }}</option>
                            <option value="manual">{{ __('Manual') }}</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Opsi Sopir --}}
                <div>
                    <label for="listing-driver-option" class="block text-xs font-medium text-gray-500 mb-1.5">{{ __('Opsi Sopir') }}</label>
                    <div class="relative">
                        <select
                            id="listing-driver-option"
                            wire:model.live="requiresDriver"
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 shadow-sm pl-4 pr-10 py-2.5 text-sm focus:border-accent-500 focus:ring-accent-500 focus:bg-white transition-colors appearance-none"
                        >
                            <option value="">{{ __('Semua') }}</option>
                            <option value="0">{{ __('Tanpa Sopir') }}</option>
                            <option value="1">{{ __('Dengan Sopir') }}</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Search button -- full width --}}
                <div class="sm:col-span-2">
                    <button
                        wire:click="$refresh"
                        class="w-full bg-accent-600 text-white rounded-xl px-6 py-2.5 text-sm font-semibold hover:bg-accent-700 active:bg-accent-800 transition-colors shadow-sm flex items-center justify-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('Search') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Results count --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">
                {{ __('Showing') }} <span class="font-semibold text-gray-900">{{ $vehicles->total() }}</span> {{ __('available vehicles') }}
            </p>
            @if($transmission)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-accent-50 text-accent-700 border border-accent-200">
                    {{ __($transmission) }}
                    <button wire:click="$set('transmission', '')" class="hover:text-accent-900 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
            @endif
            @if($requiresDriver !== null && $requiresDriver !== '')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-accent-50 text-accent-700 border border-accent-200">
                    {{ $requiresDriver === '1' ? __('Dengan Sopir') : __('Tanpa Sopir') }}
                    <button wire:click="$set('requiresDriver', '')" class="hover:text-accent-900 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
            @endif
        </div>

        {{-- Vehicle grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($vehicles as $vehicle)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg hover:border-accent-200 transition-all duration-200">
                    <div class="relative h-48 bg-gradient-to-br from-accent-50 via-accent-100 to-accent-200">
                        @if ($vehicle->image)
                            <img src="{{ Storage::disk('public')->url($vehicle->image) }}" alt="{{ $vehicle->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                                <svg class="w-20 h-20 text-accent-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M5 11l1.5-4.5h11l1.5 4.5M5 11h14M5 11l-1.15 3.45M19 11l1.15 3.45M5 14.45V17a2 2 0 002 2h10a2 2 0 002-2v-2.55M8 14h.01M16 14h.01M3 11l1.15-3.45A2 2 0 016.07 6h11.86a2 2 0 011.92 1.55L21 11M3 11h18"/>
                                </svg>
                                <span class="text-xs text-accent-400 font-medium uppercase tracking-wider">{{ __('No Image') }}</span>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2 flex flex-col gap-1.5">
                            <span class="px-2.5 py-1 bg-white/90 text-xs font-semibold text-gray-600 rounded-full shadow-sm capitalize border border-gray-100">
                                {{ __($vehicle->transmission) }}
                            </span>
                            @if($vehicle->requires_driver)
                                <span class="px-2.5 py-1 bg-amber-100/90 text-xs font-semibold text-amber-800 rounded-full shadow-sm border border-amber-200">
                                    {{ __('Dengan Sopir') }}
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-sky-100/90 text-xs font-semibold text-sky-800 rounded-full shadow-sm border border-sky-200">
                                    {{ __('Tanpa Sopir') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 text-base">{{ $vehicle->name }}</h3>
                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 rounded-full">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $vehicle->year }}
                            </span>
                            @if ($vehicle->license_plate)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 rounded-full font-mono">{{ $vehicle->license_plate }}</span>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-accent-600 font-bold text-lg">Rp {{ number_format($vehicle->rental_rate_per_day ?? 0, 0, ',', '.') }}</span>
                                <span class="text-xs text-gray-400">/{{ __('day') }}</span>
                                <br>
                                <span class="text-xs text-gray-400">Rp {{ number_format((int) ceil(($vehicle->rental_rate_per_day ?? 0) / 2), 0, ',', '.') }}/12h</span>
                            </div>
                            <button
                                wire:click="selectVehicle({{ $vehicle->id }})"
                                class="px-4 py-2.5 bg-accent-600 text-white text-sm font-semibold rounded-lg hover:bg-accent-700 active:bg-accent-800 transition-colors shadow-sm"
                            >
                                {{ __('Book Now') }}
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16 text-gray-500">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 11l1.5-4.5h11l1.5 4.5M5 11h14M5 11l-1.15 3.45M19 11l1.15 3.45M5 14.45V17a2 2 0 002 2h10a2 2 0 002-2v-2.55M8 14h.01M16 14h.01M3 11l1.15-3.45A2 2 0 016.07 6h11.86a2 2 0 011.92 1.55L21 11M3 11h18"/>
                    </svg>
                    <p class="text-lg font-medium">{{ __('No vehicles available') }}</p>
                    <p class="text-sm mt-1">{{ __('Try adjusting your date range or filters.') }}</p>
                    <button
                        wire:click="resetFilters"
                        class="mt-4 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-accent-600 bg-accent-50 rounded-lg hover:bg-accent-100 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Back to Home') }}
                    </button>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $vehicles->links() }}
        </div>
    @endif
</div>
