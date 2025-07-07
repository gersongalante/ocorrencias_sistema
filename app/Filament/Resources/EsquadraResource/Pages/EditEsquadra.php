<?php

namespace App\Filament\Resources\EsquadraResource\Pages;

use App\Filament\Resources\EsquadraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEsquadra extends EditRecord
{
    protected static string $resource = EsquadraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
