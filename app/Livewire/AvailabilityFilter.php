<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class AvailabilityFilter extends Component
{
    public ?string $startDate = null;

    public ?string $startTime = null;

    public ?string $endDate = null;

    public ?string $endTime = null;

    public ?string $transmission = null;

    public ?string $requiresDriver = null;

    public function mount(): void
    {
        $start = Carbon::now()->ceilHour();
        $end = $start->copy()->addHour();

        $this->startDate = $start->format('Y-m-d');
        $this->startTime = $start->format('H:i');
        $this->endDate = $end->format('Y-m-d');
        $this->endTime = $end->format('H:i');

        $this->transmission = 'automatic';
        $this->requiresDriver = '0';
    }

    public function updated($property): void
    {
        if (in_array($property, ['startDate', 'startTime', 'endDate', 'endTime'], true)) {
            $this->validateEndAfterStart();
        }
    }

    public function search(): void
    {
        $startDatetime = $this->startDate && $this->startTime
            ? $this->startDate.'T'.$this->startTime
            : null;
        $endDatetime = $this->endDate && $this->endTime
            ? $this->endDate.'T'.$this->endTime
            : null;

        $params = array_filter([
            'startDate' => $startDatetime,
            'endDate' => $endDatetime,
            'transmission' => $this->transmission,
            'requiresDriver' => $this->requiresDriver,
        ], fn ($value) => $value !== null && $value !== '');

        $this->redirect(route('cars.index', $params));
    }

    public function render()
    {
        return view('livewire.availability-filter');
    }

    protected function validateEndAfterStart(): void
    {
        if (! $this->startDate || ! $this->startTime || ! $this->endDate || ! $this->endTime) {
            return;
        }

        $start = Carbon::parse($this->startDate.' '.$this->startTime);
        $end = Carbon::parse($this->endDate.' '.$this->endTime);

        if ($end->lte($start)) {
            $end = $start->copy()->addHours(24);
            $this->endDate = $end->format('Y-m-d');
            $this->endTime = $end->format('H:i');
        }
    }
}
