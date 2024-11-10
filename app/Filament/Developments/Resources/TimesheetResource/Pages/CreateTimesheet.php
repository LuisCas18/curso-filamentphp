<?php

namespace App\Filament\Developments\Resources\TimesheetResource\Pages;

use App\Filament\Developments\Resources\TimesheetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTimesheet extends CreateRecord
{
    protected static string $resource = TimesheetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;

        return $data;
    }
}
