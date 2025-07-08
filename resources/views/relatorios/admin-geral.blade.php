<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Geral - Todas as Esquadras</title>
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
        .ocorrencias-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .ocorrencias-table th,
        .ocorrencias-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .ocorrencias-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .ocorrencias-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .estado-pendente { color: #ff6b35; font-weight: bold; }
        .estado-em-andamento { color: #f39c12; font-weight: bold; }
        .estado-concluido { color: #27ae60; font-weight: bold; }
        .estado-cancelado { color: #e74c3c; font-weight: bold; }
        .resumo {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .resumo h3 {
            margin-top: 0;
            color: #2c5aa0;
        }
        .resumo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        .resumo-item {
            text-align: center;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .resumo-numero {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .resumo-label {
            font-size: 14px;
            color: #666;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
        }
        .no-print {
            margin-top: 20px;
            text-align: center;
        }
        .no-print button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-print {
            background-color: #28a745;
            color: white;
        }
        .btn-print:hover {
            background-color: #218838;
        }
        .btn-close {
            background-color: #6c757d;
            color: white;
        }
        .btn-close:hover {
            background-color: #545b62;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
            .ocorrencias-table { font-size: 10px; }
            .ocorrencias-table th, .ocorrencias-table td { padding: 4px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RELATÓRIO GERAL - TODAS AS ESQUADRAS</h1>
        <h2>Sistema de Gestão de Ocorrências</h2>
        <p><strong>Gerado por:</strong> {{ auth()->user()->name }} (Administrador)</p>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</p>
        <p><strong>Data do Relatório:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="resumo">
        <h3>Resumo Geral do Período</h3>
        <div class="resumo-grid">
            <div class="resumo-item">
                <div class="resumo-numero">{{ $ocorrencias->count() }}</div>
                <div class="resumo-label">Total de Ocorrências</div>
            </div>
            <div class="resumo-item">
                <div class="resumo-numero">{{ $ocorrencias->where('estado', 'Pendente')->count() }}</div>
                <div class="resumo-label">Pendentes</div>
            </div>
            <div class="resumo-item">
                <div class="resumo-numero">{{ $ocorrencias->where('estado', 'Em Andamento')->count() }}</div>
                <div class="resumo-label">Em Andamento</div>
            </div>
            <div class="resumo-item">
                <div class="resumo-numero">{{ $ocorrencias->where('estado', 'Concluído')->count() }}</div>
                <div class="resumo-label">Concluídas</div>
            </div>
            <div class="resumo-item">
                <div class="resumo-numero">{{ $ocorrencias->where('estado', 'Cancelado')->count() }}</div>
                <div class="resumo-label">Canceladas</div>
            </div>
            <div class="resumo-item">
                <div class="resumo-numero">{{ $esquadras->count() }}</div>
                <div class="resumo-label">Total de Esquadras</div>
            </div>
            <div class="resumo-item">
                <div class="resumo-numero">{{ $agentes->count() }}</div>
                <div class="resumo-label">Total de Agentes</div>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>Resumo por Esquadra</h3>
        @if($esquadras->count() > 0)
        <table class="ocorrencias-table">
            <thead>
                <tr>
                    <th>Esquadra</th>
                    <th>Província</th>
                    <th>Município</th>
                    <th>Total de Ocorrências</th>
                    <th>Pendentes</th>
                    <th>Em Andamento</th>
                    <th>Concluídas</th>
                    <th>Canceladas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($esquadras as $esquadra)
                @php
                    $esquadraOcorrencias = $ocorrencias->where('esquadra_id', $esquadra->id);
                @endphp
                <tr>
                    <td>{{ $esquadra->nome }}</td>
                    <td>{{ $esquadra->provincia }}</td>
                    <td>{{ $esquadra->municipio }}</td>
                    <td>{{ $esquadraOcorrencias->count() }}</td>
                    <td>{{ $esquadraOcorrencias->where('estado', 'Pendente')->count() }}</td>
                    <td>{{ $esquadraOcorrencias->where('estado', 'Em Andamento')->count() }}</td>
                    <td>{{ $esquadraOcorrencias->where('estado', 'Concluído')->count() }}</td>
                    <td>{{ $esquadraOcorrencias->where('estado', 'Cancelado')->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p><em>Nenhuma esquadra encontrada.</em></p>
        @endif
    </div>

    <div class="info-section">
        <h3>Resumo por Agente</h3>
        @if($agentes->count() > 0)
        <table class="ocorrencias-table">
            <thead>
                <tr>
                    <th>Agente</th>
                    <th>Esquadra</th>
                    <th>Email</th>
                    <th>Total de Ocorrências</th>
                    <th>Pendentes</th>
                    <th>Em Andamento</th>
                    <th>Concluídas</th>
                    <th>Canceladas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agentes as $agente)
                @php
                    $agenteOcorrencias = $ocorrencias->where('user_id', $agente->id);
                @endphp
                <tr>
                    <td>{{ $agente->name }}</td>
                    <td>{{ $agente->esquadra ? $agente->esquadra->nome : 'N/A' }}</td>
                    <td>{{ $agente->email }}</td>
                    <td>{{ $agenteOcorrencias->count() }}</td>
                    <td>{{ $agenteOcorrencias->where('estado', 'Pendente')->count() }}</td>
                    <td>{{ $agenteOcorrencias->where('estado', 'Em Andamento')->count() }}</td>
                    <td>{{ $agenteOcorrencias->where('estado', 'Concluído')->count() }}</td>
                    <td>{{ $agenteOcorrencias->where('estado', 'Cancelado')->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p><em>Nenhum agente encontrado.</em></p>
        @endif
    </div>

    <div class="info-section">
        <h3>Todas as Ocorrências do Período</h3>
        @if($ocorrencias->count() > 0)
        <table class="ocorrencias-table">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Data/Hora</th>
                    <th>Tipo</th>
                    <th>Localização</th>
                    <th>Esquadra</th>
                    <th>Agente</th>
                    <th>Estado</th>
                    <th>Vítimas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ocorrencias as $ocorrencia)
                <tr>
                    <td>{{ $ocorrencia->id }}</td>
                    <td>{{ $ocorrencia->data_hora->format('d/m/Y H:i') }}</td>
                    <td>{{ $ocorrencia->tipo }}</td>
                    <td>
                        {{ $ocorrencia->bairro }}, {{ $ocorrencia->municipio }}
                        @if($ocorrencia->rua)
                        <br><small>{{ $ocorrencia->rua }}</small>
                        @endif
                    </td>
                    <td>{{ $ocorrencia->esquadra ? $ocorrencia->esquadra->nome : 'N/A' }}</td>
                    <td>{{ $ocorrencia->user->name }}</td>
                    <td class="estado-{{ strtolower(str_replace(' ', '-', $ocorrencia->estado)) }}">
                        {{ $ocorrencia->estado }}
                    </td>
                    <td>{{ $ocorrencia->vitimas }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p><em>Nenhuma ocorrência encontrada para o período selecionado.</em></p>
        @endif
    </div>

    <div class="footer">
        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Administrador: {{ auth()->user()->name }}</p>
        <p>Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}</p>
        <p>Sistema de Gestão de Ocorrências</p>
    </div>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Imprimir Relatório</button>
        <button class="btn-close" onclick="window.close()">Fechar</button>
    </div>
</body>
</html> 