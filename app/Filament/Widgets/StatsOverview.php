<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEmployees = User::all()->count();
        $totalHolidays = Holiday::all()->count();
        $totalTimesheets = Timesheet::all()->count();
        return [
            Stat::make('Employees', $totalEmployees),
            Stat::make('Holidays', $totalHolidays),
            Stat::make('Timesheets', $totalTimesheets),
        ];
    }
}
