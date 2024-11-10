<?php

namespace App\Filament\Developments\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DevelopmentsWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending Holidays', $this->getPendingHoliday(Auth::user())),
            Stat::make('Approved Holidays', $this->getApprovedHoliday(Auth::user())),
            Stat::make('Total Work', $this->getTotalWork(Auth::user())),
            Stat::make('Total Pause', $this->getTotalPause(Auth::user())),
        ];
    }

    protected function getPendingHoliday(User $user)
    {
        $totalPendingHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'pending')->get()->count();
        return $totalPendingHolidays;
    }

    protected function getApprovedHoliday(User $user)
    {
        $totalApprovedHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'approved')->get()->count();
        return $totalApprovedHolidays;
    }

    protected function getTotalWork(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')
            ->whereDate('created_at', Carbon::today())->get();

        $sumSeconds = 0;

        foreach ($timesheets as $timesheet) {
            $startTime = Carbon::parse($timesheet->day_in)->setTimezone('Etc/GMT+6');
            $finishTime = Carbon::parse($timesheet->day_out)->setTimezone('Etc/GMT+6');
            if ($finishTime->greaterThan($startTime)) {
                $totalDuration = $finishTime->diffInSeconds($startTime);
                $sumSeconds += $totalDuration;
            }
        }

        return gmdate("H:i:s", abs($sumSeconds));
    }

    protected function getTotalPause(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'pause')->whereDate('created_at', Carbon::today())->get();
        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            $startTime = Carbon::parse($timesheet->day_in)->setTimezone('Etc/GMT+6');
            $finishTime = Carbon::parse($timesheet->day_out)->setTimezone('Etc/GMT+6');
            if ($finishTime->greaterThan($startTime)) {
                $totalDuration = $finishTime->diffInSeconds($startTime);
                $sumSeconds += $totalDuration;
            }
            $tiempoFormato = gmdate("H:i:s", $sumSeconds);
        }

        return gmdate("H:i:s", abs($sumSeconds));
    }
}
