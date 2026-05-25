<div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl border border-white/20 overflow-hidden">
    {{-- Header --}}
    <div class="px-6 pt-6 pb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">{{ __('Check Availability') }}</h3>
                <p class="text-sm text-white/70">{{ __('Find the perfect car for your trip') }}</p>
            </div>
        </div>
    </div>

    {{-- Filter inputs --}}
    <form wire:submit.prevent="search" class="px-6 pb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Start Date --}}
            <div>
                <label for="filter-start-date" class="block text-sm font-medium text-white/80 mb-1.5">
                    {{ __('Start Date & Time') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input
                        type="datetime-local"
                        id="filter-start-date"
                        wire:model.live="startDate"
                        class="block w-full rounded-xl border-white/20 bg-white/10 shadow-sm pl-10 pr-4 py-3 text-sm text-white placeholder-white/40 focus:border-white/50 focus:ring-white/30 focus:bg-white/20 transition-colors"
                    >
                </div>
            </div>

            {{-- End Date --}}
            <div>
                <label for="filter-end-date" class="block text-sm font-medium text-white/80 mb-1.5">
                    {{ __('End Date & Time') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input
                        type="datetime-local"
                        id="filter-end-date"
                        wire:model.live="endDate"
                        class="block w-full rounded-xl border-white/20 bg-white/10 shadow-sm pl-10 pr-4 py-3 text-sm text-white placeholder-white/40 focus:border-white/50 focus:ring-white/30 focus:bg-white/20 transition-colors"
                    >
                </div>
            </div>

            {{-- Transmission --}}
            <div>
                <label for="filter-transmission" class="block text-sm font-medium text-white/80 mb-1.5">
                    {{ __('Transmission') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <select
                        id="filter-transmission"
                        wire:model.live="transmission"
                        class="block w-full rounded-xl border-white/20 bg-white/10 shadow-sm pl-10 pr-10 py-3 text-sm text-white focus:border-white/50 focus:ring-white/30 focus:bg-white/20 transition-colors appearance-none"
                    >
                        <option value="" class="text-gray-900 bg-white">{{ __('All Types') }}</option>
                        <option value="automatic" class="text-gray-900 bg-white">{{ __('Automatic') }}</option>
                        <option value="manual" class="text-gray-900 bg-white">{{ __('Manual') }}</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Rental Type --}}
            <div>
                <label for="filter-rental-type" class="block text-sm font-medium text-white/80 mb-1.5">
                    {{ __('Rental Type') }}
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <select
                        id="filter-rental-type"
                        wire:model.live="rentalType"
                        class="block w-full rounded-xl border-white/20 bg-white/10 shadow-sm pl-10 pr-10 py-3 text-sm text-white focus:border-white/50 focus:ring-white/30 focus:bg-white/20 transition-colors appearance-none"
                    >
                        <option value="" class="text-gray-900 bg-white">{{ __('All Types') }}</option>
                        <option value="self-drive" class="text-gray-900 bg-white">{{ __('Self Drive') }}</option>
                        <option value="with-driver" class="text-gray-900 bg-white">{{ __('With Driver') }}</option>
                        <option value="both" class="text-gray-900 bg-white">{{ __('Both') }}</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Search button --}}
            <div class="flex items-end">
                <button
                    type="submit"
                    class="w-full bg-accent-600 text-white rounded-xl px-6 py-3 text-sm font-semibold hover:bg-accent-500 active:bg-accent-700 transition-colors shadow-lg shadow-black/20 flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    {{ __('Search Available Cars') }}
                </button>
            </div>
        </div>
    </form>
</div>