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

    #[Url]
    public ?string $requiresDriver = null;

    public ?string $startDateDisplay = null;

    public ?string $startTime = null;

    public ?string $endDateDisplay = null;

    public ?string $endTime = null;

    public ?int $selectedVehicleId = null;

    public function mount(): void
    {
        if (! $this->startDate) {
            $this->startDate = Carbon::now()->ceilHour()->format('Y-m-d\TH:i');
        }

        if (! $this->endDate) {
            $this->endDate = Carbon::parse($this->startDate)->addHour()->format('Y-m-d\TH:i');
        }

        $start = Carbon::parse($this->startDate);
        $this->startDateDisplay = $start->format('Y-m-d');
        $this->startTime = $start->format('H:i');

        $end = Carbon::parse($this->endDate);
        $this->endDateDisplay = $end->format('Y-m-d');
        $this->endTime = $end->format('H:i');
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDateDisplay', 'startTime', 'endDateDisplay', 'endTime'], true)) {
            $this->syncDatetimes();
            $this->validateEndAfterStart();
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

        if ($this->requiresDriver === '0') {
            $query->where('requires_driver', false);
        } elseif ($this->requiresDriver === '1') {
            $query->where('requires_driver', true);
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

    protected function syncDatetimes(): void
    {
        if ($this->startDateDisplay && $this->startTime) {
            $this->startDate = $this->startDateDisplay.'T'.$this->startTime;
        }

        if ($this->endDateDisplay && $this->endTime) {
            $this->endDate = $this->endDateDisplay.'T'.$this->endTime;
        }
    }

    protected function validateEndAfterStart(): void
    {
        if (! $this->startDate || ! $this->endDate) {
            return;
        }

        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        if ($end->lte($start)) {
            $end = $start->copy()->addHours(24);
            $this->endDate = $end->format('Y-m-d\TH:i');
            $this->endDateDisplay = $end->format('Y-m-d');
            $this->endTime = $end->format('H:i');
        }
    }
}
