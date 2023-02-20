<?php

namespace App\Filament\Resources\HospitalResource\Pages;

use App\Filament\Resources\HospitalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHospital extends EditRecord
{
    protected static string $resource = HospitalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
