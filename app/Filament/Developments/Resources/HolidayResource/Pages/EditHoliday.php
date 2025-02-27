<?php

namespace App\Filament\Developments\Resources\HolidayResource\Pages;

use App\Filament\Developments\Resources\HolidayResource;
use App\Mail\HolidayApproved;
use App\Mail\HolidayDecline;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
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

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     $record->update($data);
    //     if($record->type == 'approved'){
    //         $user = User::find($record->user_id);
    //         $data = array(
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'day' => $record->day
    //         );
    //         Mail::to($user)->send(new HolidayApproved($data));
    //     }
    //     else if($record->type == 'decline') {
    //         $user = User::find($record->user_id);
    //         $data = array(
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'day' => $record->day
    //         );
    //         Mail::to($user)->send(new HolidayDecline($data));
    //     }
    //     return $record;
    // }
}
