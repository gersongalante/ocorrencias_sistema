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

// Rotas para relatório do comandante
Route::get('/comandante/relatorio-filtros', function () {
    // Verificar se o usuário é comandante
    if (auth()->user()?->role !== 'Comandante') {
        abort(403);
    }
    
    $user = auth()->user();
    $esquadra = $user->esquadra;
    
    if (!$esquadra) {
        abort(403, 'Comandante não está associado a nenhuma esquadra.');
    }
    
    // Obter todos os agentes da esquadra
    $agentes = \App\Models\User::where('esquadra_id', $esquadra->id)
        ->where('role', 'Agente')
        ->orderBy('name')
        ->get();
    
    return view('relatorios.comandante-filtros', compact('esquadra', 'agentes'));
})->name('comandante.relatorio.filtros');

Route::get('/comandante/relatorio-esquadra', function (\Illuminate\Http\Request $request) {
    // Verificar se o usuário é comandante
    if (auth()->user()?->role !== 'Comandante') {
        abort(403);
    }
    
    $user = auth()->user();
    $esquadra = $user->esquadra;
    
    if (!$esquadra) {
        abort(403, 'Comandante não está associado a nenhuma esquadra.');
    }
    
    // Obter parâmetros de filtro
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
    $estado = $request->get('estado');
    $tipo = $request->get('tipo');
    $agenteId = $request->get('agente_id');
    
    // Construir query base
    $query = \App\Models\Ocorrencia::where('esquadra_id', $esquadra->id)
        ->whereBetween('data_hora', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
        ->with(['user', 'esquadra']);
    
    // Aplicar filtros adicionais
    if ($estado) {
        $query->where('estado', $estado);
    }
    
    if ($tipo) {
        $query->where('tipo', $tipo);
    }
    
    if ($agenteId) {
        $query->where('user_id', $agenteId);
    }
    
    $ocorrencias = $query->orderBy('data_hora', 'desc')->get();
    
    // Obter todos os agentes da esquadra para o relatório
    $agentes = \App\Models\User::where('esquadra_id', $esquadra->id)
        ->where('role', 'Agente')
        ->orderBy('name')
        ->get();
    
    return view('relatorios.comandante-esquadra', compact('esquadra', 'ocorrencias', 'agentes', 'dataInicio', 'dataFim'));
})->name('comandante.relatorio.esquadra');

Route::get('/comandante/relatorio-pessoal', function (\Illuminate\Http\Request $request) {
    // Verificar se o usuário é comandante
    if (auth()->user()?->role !== 'Comandante') {
        abort(403);
    }
    
    $user = auth()->user();
    
    // Obter parâmetros de data
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
    
    // Buscar apenas as ocorrências registadas pelo comandante
    $ocorrencias = \App\Models\Ocorrencia::where('user_id', $user->id)
        ->whereBetween('data_hora', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
        ->orderBy('data_hora', 'desc')
        ->get();
    
    return view('relatorios.comandante-pessoal', compact('user', 'ocorrencias', 'dataInicio', 'dataFim'));
})->name('comandante.relatorio.pessoal');

// Rotas para relatórios do administrador
Route::get('/admin/relatorio-agente', function (\Illuminate\Http\Request $request) {
    // Verificar se o usuário é administrador
    if (auth()->user()?->role !== 'Administrador') {
        abort(403);
    }
    
    // Obter parâmetros
    $agenteId = $request->get('agente_id');
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
    
    // Buscar agente e suas ocorrências
    $agente = \App\Models\User::findOrFail($agenteId);
    $ocorrencias = \App\Models\Ocorrencia::where('user_id', $agenteId)
        ->whereBetween('data_hora', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
        ->orderBy('data_hora', 'desc')
        ->get();
    
    return view('relatorios.admin-agente', compact('agente', 'ocorrencias', 'dataInicio', 'dataFim'));
})->name('admin.relatorio.agente');

Route::get('/admin/relatorio-pessoal', function (\Illuminate\Http\Request $request) {
    // Verificar se o usuário é administrador
    if (auth()->user()?->role !== 'Administrador') {
        abort(403);
    }
    
    $user = auth()->user();
    
    // Obter parâmetros de data
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
    
    // Buscar apenas as ocorrências registadas pelo administrador
    $ocorrencias = \App\Models\Ocorrencia::where('user_id', $user->id)
        ->whereBetween('data_hora', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
        ->orderBy('data_hora', 'desc')
        ->get();
    
    return view('relatorios.admin-pessoal', compact('user', 'ocorrencias', 'dataInicio', 'dataFim'));
})->name('admin.relatorio.pessoal');

Route::get('/admin/relatorio-esquadra', function (\Illuminate\Http\Request $request) {
    // Verificar se o usuário é administrador
    if (auth()->user()?->role !== 'Administrador') {
        abort(403);
    }
    
    // Obter parâmetros
    $esquadraId = $request->get('esquadra_id');
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
    
    // Buscar esquadra e suas ocorrências
    $esquadra = \App\Models\Esquadra::findOrFail($esquadraId);
    $ocorrencias = \App\Models\Ocorrencia::where('esquadra_id', $esquadraId)
        ->whereBetween('data_hora', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
        ->with(['user', 'esquadra'])
        ->orderBy('data_hora', 'desc')
        ->get();
    
    // Obter agentes da esquadra
    $agentes = \App\Models\User::where('esquadra_id', $esquadraId)
        ->where('role', 'Agente')
        ->orderBy('name')
        ->get();
    
    return view('relatorios.admin-esquadra', compact('esquadra', 'ocorrencias', 'agentes', 'dataInicio', 'dataFim'));
})->name('admin.relatorio.esquadra');

Route::get('/admin/relatorio-geral', function (\Illuminate\Http\Request $request) {
    // Verificar se o usuário é administrador
    if (auth()->user()?->role !== 'Administrador') {
        abort(403);
    }
    
    // Obter parâmetros de data
    $dataInicio = $request->get('data_inicio', now()->startOfMonth()->format('Y-m-d'));
    $dataFim = $request->get('data_fim', now()->format('Y-m-d'));
    
    // Buscar todas as ocorrências do período
    $ocorrencias = \App\Models\Ocorrencia::whereBetween('data_hora', [$dataInicio . ' 00:00:00', $dataFim . ' 23:59:59'])
        ->with(['user', 'esquadra'])
        ->orderBy('data_hora', 'desc')
        ->get();
    
    // Obter todas as esquadras
    $esquadras = \App\Models\Esquadra::orderBy('nome')->get();
    
    // Obter todos os agentes
    $agentes = \App\Models\User::where('role', 'Agente')->orderBy('name')->get();
    
    return view('relatorios.admin-geral', compact('ocorrencias', 'esquadras', 'agentes', 'dataInicio', 'dataFim'));
})->name('admin.relatorio.geral');
