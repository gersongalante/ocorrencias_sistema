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
                ->form([
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('agente.relatorio.geral', [
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });
        }

        // Adicionar botões de impressão de relatório para comandantes
        if (auth()->user()?->role === 'Comandante') {
            $actions[] = Actions\Action::make('imprimir_relatorio_por_agente')
                ->label('Imprimir Relatório por Agente')
                ->icon('heroicon-o-printer')
                ->form([
                    \Filament\Forms\Components\Select::make('agente_id')
                        ->label('Agente')
                        ->options(function () {
                            $user = auth()->user();
                            if (!$user || !$user->esquadra) {
                                return [];
                            }
                            
                            return \App\Models\User::where('esquadra_id', $user->esquadra->id)
                                ->where('role', 'Agente')
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->searchable(),
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('comandante.relatorio.esquadra', [
                        'agente_id' => $data['agente_id'],
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });

            // Adicionar botão de impressão do relatório pessoal do comandante
            $actions[] = Actions\Action::make('imprimir_relatorio_pessoal')
                ->label('Imprimir Meu Relatório')
                ->icon('heroicon-o-document-text')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('comandante.relatorio.pessoal', [
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });

            // Adicionar botão de impressão do relatório geral da esquadra
            $actions[] = Actions\Action::make('imprimir_relatorio_geral_esquadra')
                ->label('Imprimir Relatório Geral da Esquadra')
                ->icon('heroicon-o-building-office')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('comandante.relatorio.esquadra', [
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });
        }

        // Adicionar botões de impressão de relatório para administradores
        if (auth()->user()?->role === 'Administrador') {
            // Botão para relatório por agente (todas as esquadras)
            $actions[] = Actions\Action::make('imprimir_relatorio_por_agente_admin')
                ->label('Imprimir Relatório por Agente')
                ->icon('heroicon-o-printer')
                ->form([
                    \Filament\Forms\Components\Select::make('agente_id')
                        ->label('Agente')
                        ->options(function () {
                            return \App\Models\User::where('role', 'Agente')
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->searchable(),
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('admin.relatorio.agente', [
                        'agente_id' => $data['agente_id'],
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });

            // Botão para relatório pessoal do administrador
            $actions[] = Actions\Action::make('imprimir_relatorio_pessoal_admin')
                ->label('Imprimir Meu Relatório')
                ->icon('heroicon-o-document-text')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('admin.relatorio.pessoal', [
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });

            // Botão para relatório por esquadra específica
            $actions[] = Actions\Action::make('imprimir_relatorio_por_esquadra')
                ->label('Imprimir Relatório por Esquadra')
                ->icon('heroicon-o-building-office')
                ->form([
                    \Filament\Forms\Components\Select::make('esquadra_id')
                        ->label('Esquadra')
                        ->options(function () {
                            return \App\Models\Esquadra::pluck('nome', 'id');
                        })
                        ->required()
                        ->searchable(),
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('admin.relatorio.esquadra', [
                        'esquadra_id' => $data['esquadra_id'],
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });

            // Botão para relatório geral de todas as esquadras
            $actions[] = Actions\Action::make('imprimir_relatorio_geral_todas_esquadras')
                ->label('Imprimir Relatório Geral (Todas Esquadras)')
                ->icon('heroicon-o-globe-alt')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('data_inicio')
                        ->label('Data de Início')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('data_fim')
                        ->label('Data de Fim')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('admin.relatorio.geral', [
                        'data_inicio' => $data['data_inicio'],
                        'data_fim' => $data['data_fim']
                    ]);
                    return redirect()->away($url);
                });
        }

        return $actions;
    }
}
