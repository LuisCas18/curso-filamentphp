<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Mail\HolidayApproved;
use App\Mail\HolidayDecline;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        //SEND EMAIL ONLY IF APPROVED
        if ($record->type == 'approved') {
            $user = User::find($record->user_id);
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'day' => $record->day
            ];
            Mail::to($user)->send(new HolidayApproved($data));
            $recipient = Auth::user();
            Notification::make()
            ->title('Solicitud de Vacaciones')
            ->body('El día '.$data['day'].' esta aprobado')
            ->sendToDatabase($recipient);
            //SEND EMAIL ONLY IF DECLINE
        } else if ($record->type == 'decline') {
            $user = User::find($record->user_id);
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'day' => $record->day
            ];
            Mail::to($user)->send(new HolidayDecline($data));
            $recipient = Auth::user();
            Notification::make()
            ->title('Solicitud de Vacaciones')
            ->body('El día '.$data['day'].' esta rechazado')
            ->sendToDatabase($recipient);
        }
        return $record;
    }
}
