<?php

namespace App\Filament\Developments\Resources\TimesheetResource\Pages;

use App\Filament\Developments\Resources\TimesheetResource;
use App\Imports\MyTimesheetImport;
use App\Models\Timesheet;
use Carbon\Carbon;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheets = Timesheet::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        if($lastTimesheets == null){
            return [
                Action::make('inWork')
                ->label('Entrar a trabajar')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (){
                    $user = Auth::user();
                    $timesheets = new Timesheet();
                    $timesheets->calendar_id = 1;
                    $timesheets->user_id = $user->id;
                    $timesheets->day_in = Carbon::now('America/Mexico_City');
                    $timesheets->type = 'work';
                    $timesheets->created_at = Carbon::now('America/Mexico_City');
                    $timesheets->save();
                }),
                Actions\CreateAction::make(),

            ];
        }
        return [
            Action::make('inWork')
            ->label('Registrar Actividad')
            ->color('success')
            ->visible(!$lastTimesheets->day_out == null)
            ->disabled($lastTimesheets->day_out == null)
            ->requiresConfirmation()
            ->action(function (){
                $user = Auth::user();
                $timesheets = new Timesheet();
                $timesheets->calendar_id = 1;
                $timesheets->user_id = $user->id;
                $timesheets->day_in = Carbon::now('America/Mexico_City');
                $timesheets->type = 'work';
                $timesheets->created_at = Carbon::now('America/Mexico_City');
                $timesheets->save();

                Notification::make()
                ->title('Haz iniciado tu jornada de trabajo')
                ->success()
                ->duration(5000)
                ->send();
            }),
            Action::make('stopWork')
            ->label('Registrar Salida')
            ->color('success')
            ->visible($lastTimesheets->day_out == null && $lastTimesheets->type != 'pause')
            ->disabled(!$lastTimesheets->day_out == null)
            ->requiresConfirmation()
            ->action(function () use($lastTimesheets){
                $lastTimesheets->day_out = Carbon::now('America/Mexico_City');
                $lastTimesheets->save();

                Notification::make()
                ->title('Haz terminado tu jornada de trabajo')
                ->danger()
                ->duration(5000)
                ->send();
            }),
            Action::make('inPause')
            ->label('Registrar Pausa')
            ->color('info')
            ->visible($lastTimesheets->day_out == null && $lastTimesheets->type != 'pause')
            ->disabled(!$lastTimesheets->day_out == null)
            ->requiresConfirmation()
            ->action(function () use($lastTimesheets) {
                $lastTimesheets->day_out = Carbon::now('America/Mexico_City');
                $lastTimesheets->save();
                $timesheets = new Timesheet();
                $timesheets->calendar_id = 1;
                $timesheets->user_id = Auth::user()->id;
                $timesheets->day_in = Carbon::now('America/Mexico_City');
                $timesheets->type = 'pause';
                $timesheets->created_at = Carbon::now('America/Mexico_City');
                $timesheets->save();
                Notification::make()
                ->title('Haz realizado una pausa')
                ->info()
                ->duration(5000)
                ->send();
            }),
            Action::make('stopPause')
            ->label('Parar Pausa')
            ->color('info')
            ->visible($lastTimesheets->day_out == null && $lastTimesheets->type == 'pause')
            ->disabled(!$lastTimesheets->day_out == null)
            ->requiresConfirmation()
            ->action(function () use($lastTimesheets) {
                $lastTimesheets->day_out = Carbon::now('America/Mexico_City');
                $lastTimesheets->save();
                $timesheets = new Timesheet();
                $timesheets->calendar_id = 1;
                $timesheets->user_id = Auth::user()->id;
                $timesheets->day_in = Carbon::now('America/Mexico_City');
                $timesheets->type = 'work';
                $timesheets->created_at = Carbon::now('America/Mexico_City');
                $timesheets->save();
                Notification::make()
                ->title('Haz regresado a tu jornada de trabajo')
                ->info()
                ->duration(5000)
                ->send();
            }),
            Actions\CreateAction::make(),
            ExcelImportAction::make()
            ->color("primary")
            ->use(MyTimesheetImport::class),
            Action::make('CreatePDF')
            ->label('Crear PDF')
            ->color('warning')
            ->requiresConfirmation()
            ->url(
                fn (): string => route('pdf.example', ['user' => Auth::user()]),
                shouldOpenInNewTab: true
            ),
        ];
    }
}
