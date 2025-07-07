<?php

namespace App\Filament\Resources\OcorrenciaResource\Pages;

use App\Filament\Resources\OcorrenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOcorrencia extends CreateRecord
{
    protected static string $resource = OcorrenciaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        if (auth()->user()?->role === 'Agente') {
            $data['esquadra_id'] = auth()->user()->esquadra_id;
        }
        return $data;
    }
}
