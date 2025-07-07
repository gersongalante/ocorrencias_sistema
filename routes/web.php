<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rotas para impressão de relatórios
Route::get('/ocorrencia/{ocorrencia}/print', function (\App\Models\Ocorrencia $ocorrencia) {
    // Verificar se o usuário é o agente que registrou a ocorrência
    if (auth()->user()?->role !== 'Agente' || $ocorrencia->user_id !== auth()->id()) {
        abort(403);
    }
    
    return view('relatorios.ocorrencia', compact('ocorrencia'));
})->name('ocorrencia.print');

Route::get('/agente/relatorio-geral', function () {
    // Verificar se o usuário é agente
    if (auth()->user()?->role !== 'Agente') {
        abort(403);
    }
    
    $user = auth()->user();
    $ocorrencias = \App\Models\Ocorrencia::where('user_id', $user->id)
        ->where('esquadra_id', $user->esquadra_id)
        ->orderBy('data_hora', 'desc')
        ->get();
    
    return view('relatorios.agente-geral', compact('user', 'ocorrencias'));
})->name('agente.relatorio.geral');
