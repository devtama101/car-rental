<?php

namespace App\Filament\Customer\Pages;

use App\Enums\PersonType;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\DB;

class Dashboard extends BaseDashboard
{
    public function mount(): void
    {
        $type = DB::table('people')
            ->where('user_id', auth()->id())
            ->whereNull('deleted_at')
            ->value('type');

        if ($type === PersonType::SuperAdmin->value || $type === PersonType::Admin->value) {
            $this->redirect(Filament::getUrl('admin'));
        } elseif ($type === PersonType::Employee->value) {
            $this->redirect(Filament::getUrl('employee'));
        }
    }

    public function getColumns(): array|int
    {
        return 3;
    }
}
