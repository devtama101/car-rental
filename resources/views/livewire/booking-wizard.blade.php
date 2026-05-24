<div class="max-w-6xl mx-auto" x-data="{
    showSuccess: false,
    redirectUrl: '/',
    init() {
        Livewire.on('booking-completed', (event) => {
            this.showSuccess = true;
            this.redirectUrl = event.redirectUrl || '/';
        });
    }
}">
    <div x-show="!showSuccess">
        <div class="mb-6">
            <button
                wire:click="$dispatch('back-to-listing')"
                class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                {{ __('Back to cars') }}
            </button>
        </div>

        <form wire:submit="submit">
            {{ $this->form }}
        </form>
    </div>

    <div x-show="showSuccess" class="text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-accent-100 mb-4">
            <svg class="w-8 h-8 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Booking Confirmed!') }}</h2>
        <p class="mt-2 text-gray-600">{{ __('Your booking has been submitted successfully.') }}</p>
        <div class="mt-6 flex justify-center gap-3">
            <button
                wire:click="$dispatch('back-to-listing')"
                class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors"
            >
                {{ __('Browse More Cars') }}
            </button>
            <a
                :href="redirectUrl"
                class="px-6 py-2.5 bg-accent-600 text-white text-sm font-medium rounded-lg hover:bg-accent-700 transition-colors inline-block"
            >
                {{ __('View My Bookings') }}
            </a>
        </div>
    </div>
</div>
