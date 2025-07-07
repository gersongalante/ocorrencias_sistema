<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rotas para impressão de relatórios
Route::get('/ocorrencia/{ocorrencia}/print', function (\App\Models\Ocorrencia $ocorrencia, \Illuminate\Http\Request $request) {
    // Verificar se o usuário é o agente que registrou a ocorrência
    if (auth()->user()?->role !== 'Agente' || $ocorrencia->user_id !== auth()->id()) {
        abort(403);
    }
    
    $dataRelatorio = $request->get('data_relatorio', now()->format('Y-m-d'));
    
    return view('relatorios.ocorrencia', compact('ocorrencia', 'dataRelatorio'));
})->name('ocorrencia.print');

Route::get('/agente/relatorio-geral', function (\Illuminate\Http\Request $request) {
    // Verificar se o usuário é agente
    if (auth()->user()?->role !== 'Agente') {
        abort(403);
    }
    
    $user = auth()->user();
    
    // Obter parâmetros de data
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
    
    $ocorrencias = \App\Models\Ocorrencia::where('user_id', $user->id)
        ->where('esquadra_id', $user->esquadra_id)
        ->whereBetween('data_hora', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
        ->orderBy('data_hora', 'desc')
        ->get();
    
    return view('relatorios.agente-geral', compact('user', 'ocorrencias', 'dataInicio', 'dataFim'));
})->name('agente.relatorio.geral');
