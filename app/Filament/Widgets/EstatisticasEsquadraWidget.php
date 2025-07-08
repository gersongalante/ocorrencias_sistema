<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Ocorrencia;
use App\Models\User;

class EstatisticasEsquadraWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        
        if (!$user || !$user->esquadra) {
            return [
                Stat::make('Erro', 'Esquadra não atribuída')
                    ->description('Comandante não está associado a nenhuma esquadra')
                    ->color('danger'),
            ];
        }
        
        $esquadra = $user->esquadra;
        
        // Estatísticas do mês atual
        $inicioMes = now()->startOfMonth();
        $fimMes = now()->endOfMonth();
        
        $ocorrenciasMes = Ocorrencia::where('esquadra_id', $esquadra->id)
            ->whereBetween('data_hora', [$inicioMes, $fimMes])
            ->get();
        
        $totalOcorrencias = $ocorrenciasMes->count();
        $pendentes = $ocorrenciasMes->where('estado', 'Pendente')->count();
        $emAndamento = $ocorrenciasMes->where('estado', 'Em Andamento')->count();
        $concluidas = $ocorrenciasMes->where('estado', 'Concluído')->count();
        
        // Total de agentes na esquadra
        $totalAgentes = User::where('esquadra_id', $esquadra->id)
            ->where('role', 'Agente')
            ->count();
        
        return [
            Stat::make('Total de Ocorrências (Mês)', $totalOcorrencias)
                ->description('Ocorrências registadas este mês')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            
            Stat::make('Pendentes', $pendentes)
                ->description('Ocorrências pendentes')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Em Andamento', $emAndamento)
                ->description('Ocorrências em tratamento')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
            
            Stat::make('Concluídas', $concluidas)
                ->description('Ocorrências concluídas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('Agentes Ativos', $totalAgentes)
                ->description('Agentes na esquadra')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
    
    protected function getColumns(): int
    {
        return 5;
    }
} 