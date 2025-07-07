<?php

namespace App\Filament\Resources\OcorrenciaResource\Pages;

use App\Filament\Resources\OcorrenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOcorrencias extends ListRecords
{
    protected static string $resource = OcorrenciaResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
        ];

        // Adicionar botão de impressão de relatório para agentes
        if (auth()->user()?->role === 'Agente') {
            $actions[] = Actions\Action::make('imprimir_relatorio_geral')
                ->label('Imprimir Relatório Geral')
                ->icon('heroicon-o-printer')
                ->url(route('agente.relatorio.geral'))
                ->openUrlInNewTab();
        }

        return $actions;
    }
}
