<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class AvailabilityFilter extends Component
{
    public ?string $startDate = null;

    public ?string $endDate = null;

    public ?string $transmission = null;

    public ?string $rentalType = null;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->format('Y-m-d\TH:i');
        $this->endDate = Carbon::now()->addHours(24)->format('Y-m-d\TH:i');
    }

    public function updatedEndDate(): void
    {
        if ($this->startDate && $this->endDate && $this->endDate <= $this->startDate) {
            $this->endDate = Carbon::parse($this->startDate)->addHours(24)->format('Y-m-d\TH:i');
        }
    }

    public function search(): void
    {
        $params = array_filter([
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'transmission' => $this->transmission,
            'rentalType' => $this->rentalType,
        ], fn ($value) => $value !== null && $value !== '');

        $this->redirect(route('cars.index', $params));
    }

    public function render()
    {
        return view('livewire.availability-filter');
    }
}
