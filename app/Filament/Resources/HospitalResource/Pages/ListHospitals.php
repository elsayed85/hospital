<?php

namespace App\Filament\Resources\HospitalResource\Pages;

use App\Filament\Resources\HospitalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHospitals extends ListRecords
{
    protected static string $resource = HospitalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
