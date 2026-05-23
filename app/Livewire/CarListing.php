<?php

namespace App\Livewire;

use App\Enums\RentalStatus;
use App\Models\Rental;
use App\Models\Vehicle;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class CarListing extends Component
{
    use WithPagination;

    public ?string $startDate = null;

    public ?string $endDate = null;

    public ?int $selectedVehicleId = null;

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

    public function selectVehicle(int $vehicleId): void
    {
        $this->selectedVehicleId = $vehicleId;
    }

    #[On('back-to-listing')]
    public function clearSelection(): void
    {
        $this->selectedVehicleId = null;
    }

    public function getAvailableVehiclesProperty()
    {
        if ($this->startDate && $this->endDate) {
            $unavailableIds = Rental::query()
                ->whereIn('status', [
                    RentalStatus::Pending->value,
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

            return Vehicle::query()
                ->whereNotIn('id', $unavailableIds)
                ->orderBy('name')
                ->paginate(12);
        }

        return Vehicle::query()->orderBy('name')->paginate(12);
    }

    public function render()
    {
        return view('livewire.car-listing', [
            'vehicles' => $this->availableVehicles,
        ]);
    }
}
