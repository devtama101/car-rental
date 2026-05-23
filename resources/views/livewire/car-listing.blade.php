<div class="max-w-7xl mx-auto px-4 py-8">
    @if ($selectedVehicleId)
        <livewire:booking-wizard :vehicleId="$selectedVehicleId" :key="$selectedVehicleId" />
    @else
        <div id="booking" class="flex gap-4 mb-8 justify-center">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">{{ __('Start Date & Time') }}</label>
                <input
                    type="datetime-local"
                    id="start_date"
                    wire:model.live="startDate"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-accent-500 focus:ring-accent-500"
                >
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">{{ __('End Date & Time') }}</label>
                <input
                    type="datetime-local"
                    id="end_date"
                    wire:model.live="endDate"
                    class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-accent-500 focus:ring-accent-500"
                >
            </div>
            @if ($startDate && $endDate)
                <div class="flex items-end">
                    <button
                        wire:click="$set('startDate', null); $set('endDate', null)"
                        class="py-2 px-4 text-sm text-gray-600 hover:text-gray-900"
                    >
                        {{ __('Clear') }}
                    </button>
                </div>
            @endif
        </div>
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
                        <span class="absolute top-2 right-2 px-2.5 py-1 bg-white/90 text-xs font-semibold text-gray-600 rounded-full shadow-sm capitalize border border-gray-100">
                            {{ __($vehicle->transmission) }}
                        </span>
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
                                <span class="text-xs text-gray-400"> {{ __('\/day') }}</span>
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
                    <p class="text-sm mt-1">{{ __('Try adjusting your date range.') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $vehicles->links() }}
        </div>
    @endif
</div>
