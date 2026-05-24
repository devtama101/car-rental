<?php

namespace App\Livewire;

use App\Enums\RentalStatus;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CarListing extends Component
{
    use WithPagination;

    #[Url]
    public ?string $startDate = null;

    #[Url]
    public ?string $endDate = null;

    #[Url]
    public ?string $transmission = null;

    public ?int $selectedVehicleId = null;

    public function mount(): void
    {
        if (! $this->startDate) {
            $this->startDate = Carbon::now()->format('Y-m-d\TH:i');
        }

        if (! $this->endDate) {
            $this->endDate = Carbon::now()->addHours(24)->format('Y-m-d\TH:i');
        }
    }

    public function updatedEndDate(): void
    {
        if ($this->startDate && $this->endDate && $this->endDate <= $this->startDate) {
            $this->endDate = Carbon::parse($this->startDate)->addHours(24)->format('Y-m-d\TH:i');
        }
    }

    public function selectVehicle(int $vehicleId): void
    {
        $this->selectedVehicleId = $vehicleId;
    }

    #[On('back-to-listing')]
    public function clearSelection(): void
    {
        $this->selectedVehicleId = null;
    }

    public function resetFilters(): void
    {
        $this->redirect(route('home'));
    }

    public function getAvailableVehiclesProperty()
    {
        $query = Vehicle::query()->orderBy('name');

        if ($this->transmission) {
            $query->where('transmission', $this->transmission);
        }

        if ($this->startDate && $this->endDate) {
            $unavailableIds = Rental::query()
                ->whereIn('status', [
                    RentalStatus::Confirmed->value,
                    RentalStatus::Active->value,
                ])
                ->where(function ($query) {
                    $query->whereBetween('start_date', [$this->startDate, $this->endDate])
                        ->orWhereBetween('end_date', [$this->startDate, $this->endDate])
                        ->orWhere(function ($q) {
                            $q->where('start_date', '<=', $this->startDate)
                                ->where('end_date', '>=', $this->endDate);
                        });
                })
                ->pluck('vehicle_id');

            $query->whereNotIn('id', $unavailableIds);
        }

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.car-listing', [
            'vehicles' => $this->availableVehicles,
        ]);
    }
}
