<?php

namespace App\Filament\Developments\Resources\HolidayResource\Pages;

use App\Filament\Developments\Resources\HolidayResource;
use App\Mail\HolidayPending;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $data['type'] = 'pending';
        $userAdmin = User::find(1);
        $dataToSend = [
            'day' => $data['day'],
            'name' => User::find($data['user_id'])->name,
            'email' => User::find($data['user_id'])->email,
        ];
        Mail::to($userAdmin)->send(new HolidayPending($dataToSend));
        $recipient = Auth::user();
        Notification::make()
        ->title('Solicitud de Vacaciones')
        ->body('El día '.$data['day'].' esta pendiente de aprobar')
        ->sendToDatabase($recipient);
        return $data;
    }
}
