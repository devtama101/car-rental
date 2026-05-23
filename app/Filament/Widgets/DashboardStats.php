<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -2;

    protected int|array|null $columns = 3;

    protected function getStats(): array
    {
        $revenue = Payment::whereNotNull('proof_file_path')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $previousRevenue = Payment::whereNotNull('proof_file_path')
            ->whereMonth('date', now()->subMonth()->month)
            ->whereYear('date', now()->subMonth()->year)
            ->sum('amount');

        $expense = Expense::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('amount');

        $previousExpense = Expense::whereMonth('date', now()->subMonth()->month)
            ->whereYear('date', now()->subMonth()->year)
            ->sum('amount');

        $profit = $revenue - $expense;
        $previousProfit = $previousRevenue - $previousExpense;

        $revenueChange = $previousRevenue > 0
            ? round((($revenue - $previousRevenue) / $previousRevenue) * 100)
            : 0;

        $expenseChange = $previousExpense > 0
            ? round((($expense - $previousExpense) / $previousExpense) * 100)
            : 0;

        $profitChange = $previousProfit > 0
            ? round((($profit - $previousProfit) / $previousProfit) * 100)
            : 0;

        return [
            Stat::make(__('Revenue'), 'Rp '.number_format($revenue, 0, ',', '.'))
                ->description(self::changeDescription($revenueChange, __('from last month')))
                ->descriptionIcon(self::changeIcon($revenueChange))
                ->color(self::changeColor($revenueChange)),

            Stat::make(__('Expense'), 'Rp '.number_format($expense, 0, ',', '.'))
                ->description(self::changeDescription($expenseChange, __('from last month')))
                ->descriptionIcon(self::changeIcon($expenseChange))
                ->color('danger'),

            Stat::make(__('Net Profit'), 'Rp '.number_format($profit, 0, ',', '.'))
                ->description(self::changeDescription($profitChange, __('from last month')))
                ->descriptionIcon(self::changeIcon($profitChange))
                ->color(self::changeColor($profitChange)),
        ];
    }

    private static function changeDescription(int $change, string $suffix): string
    {
        return $change > 0
            ? "+{$change}% {$suffix}"
            : ($change < 0 ? "{$change}% {$suffix}" : "0% {$suffix}");
    }

    private static function changeIcon(int $change): string
    {
        return $change >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
    }

    private static function changeColor(int $change): string
    {
        return $change >= 0 ? 'success' : 'danger';
    }
}
