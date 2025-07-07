<?php

namespace App\Filament\Resources\OcorrenciaResource\Pages;

use App\Filament\Resources\OcorrenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOcorrencia extends EditRecord
{
    protected static string $resource = OcorrenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
