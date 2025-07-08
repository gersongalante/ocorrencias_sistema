<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório do Agente - {{ $agente->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
            width: 150px;
        }
        .value {
            flex: 1;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
        }
        .estado-pendente { color: #ff6b35; font-weight: bold; }
        .estado-em-andamento { color: #f39c12; font-weight: bold; }
        .estado-concluido { color: #27ae60; font-weight: bold; }
        .estado-cancelado { color: #e74c3c; font-weight: bold; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RELATÓRIO DO AGENTE</h1>
        <h2>Sistema de Gestão de Ocorrências</h2>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</p>
        <p><strong>Gerado por:</strong> {{ auth()->user()->name }} (Administrador)</p>
    </div>

    <div class="info-section">
        <h3>Informações do Agente</h3>
        <div class="info-row">
            <span class="label">Nome:</span>
            <span class="value">{{ $agente->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Nome de Utilizador:</span>
            <span class="value">{{ $agente->username }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $agente->email }}</span>
        </div>
        <div class="info-row">
            <span class="label">Papel:</span>
            <span class="value">{{ $agente->role }}</span>
        </div>
    </div>

    @if($agente->esquadra)
    <div class="info-section">
        <h3>Informações da Esquadra</h3>
        <div class="info-row">
            <span class="label">Esquadra:</span>
            <span class="value">{{ $agente->esquadra->nome }}</span>
        </div>
        <div class="info-row">
            <span class="label">Província:</span>
            <span class="value">{{ $agente->esquadra->provincia }}</span>
        </div>
        <div class="info-row">
            <span class="label">Município:</span>
            <span class="value">{{ $agente->esquadra->municipio }}</span>
        </div>
        <div class="info-row">
            <span class="label">Bairro:</span>
            <span class="value">{{ $agente->esquadra->bairro }}</span>
        </div>
        @if($agente->esquadra->rua)
        <div class="info-row">
            <span class="label">Rua:</span>
            <span class="value">{{ $agente->esquadra->rua }}</span>
        </div>
        @endif
    </div>
    @endif

    <div class="stats">
        <div class="stat-item">
            <div class="stat-number">{{ $ocorrencias->count() }}</div>
            <div class="stat-label">Total no Período</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $ocorrencias->where('estado', 'Pendente')->count() }}</div>
            <div class="stat-label">Pendentes</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $ocorrencias->where('estado', 'Em Andamento')->count() }}</div>
            <div class="stat-label">Em Andamento</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $ocorrencias->where('estado', 'Concluído')->count() }}</div>
            <div class="stat-label">Concluídas</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $ocorrencias->where('data_hora', '>=', now()->startOfDay())->count() }}</div>
            <div class="stat-label">Hoje</div>
        </div>
    </div>

    <div class="info-section">
        <h3>Lista de Ocorrências Registadas</h3>
        @if($ocorrencias->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Tipo</th>
                    <th>Data/Hora</th>
                    <th>Localização</th>
                    <th>Estado</th>
                    <th>Vítimas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ocorrencias as $ocorrencia)
                <tr>
                    <td>{{ $ocorrencia->id }}</td>
                    <td>{{ $ocorrencia->tipo }}</td>
                    <td>{{ $ocorrencia->data_hora->format('d/m/Y H:i') }}</td>
                    <td>{{ $ocorrencia->bairro }}, {{ $ocorrencia->municipio }}</td>
                    <td class="estado-{{ strtolower(str_replace(' ', '-', $ocorrencia->estado)) }}">
                        {{ $ocorrencia->estado }}
                    </td>
                    <td>{{ Str::limit($ocorrencia->vitimas, 30) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Nenhuma ocorrência registada.</p>
        @endif
    </div>

    <div class="info-section">
        <h3>Resumo por Tipo de Ocorrência</h3>
        @php
            $tipos = $ocorrencias->groupBy('tipo')->map->count();
        @endphp
        @if($tipos->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tipos as $tipo => $quantidade)
                <tr>
                    <td>{{ $tipo }}</td>
                    <td>{{ $quantidade }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Nenhuma ocorrência registada.</p>
        @endif
    </div>

    <div class="footer">
        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</p>
        <p>Agente: {{ $agente->name }}</p>
        <p>Administrador: {{ auth()->user()->name }}</p>
        <p>Sistema de Gestão de Ocorrências</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Fechar</button>
    </div>
</body>
</html> 