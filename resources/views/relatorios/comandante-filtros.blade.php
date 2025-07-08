<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório da Esquadra - Filtros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2c5aa0;
        }
        .header h1 {
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            margin: 5px 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2c5aa0;
            box-shadow: 0 0 5px rgba(44, 90, 160, 0.3);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .btn-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #2c5aa0;
            color: white;
        }
        .btn-primary:hover {
            background-color: #1e3f7a;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .info-box {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .info-box h3 {
            margin-top: 0;
            color: #2c5aa0;
        }
        .info-box p {
            margin: 5px 0;
            color: #666;
        }
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Relatório da Esquadra</h1>
            <p><strong>Comandante:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Esquadra:</strong> {{ $esquadra->nome }}</p>
        </div>

        <div class="info-box">
            <h3>Informações</h3>
            <p>Este relatório mostrará todas as ocorrências registadas pelos agentes da sua esquadra no período selecionado.</p>
            <p>O relatório incluirá um resumo estatístico e detalhes de cada ocorrência.</p>
        </div>

        <form method="GET" action="{{ route('comandante.relatorio.esquadra') }}">
            <div class="form-row">
                <div class="form-group">
                    <label for="data_inicio">Data de Início:</label>
                    <input type="date" id="data_inicio" name="data_inicio" 
                           value="{{ request('data_inicio', now()->startOfMonth()->format('Y-m-d')) }}" 
                           required>
                </div>
                <div class="form-group">
                    <label for="data_fim">Data de Fim:</label>
                    <input type="date" id="data_fim" name="data_fim" 
                           value="{{ request('data_fim', now()->format('Y-m-d')) }}" 
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="estado">Filtrar por Estado:</label>
                <select id="estado" name="estado">
                    <option value="">Todos os Estados</option>
                    <option value="Pendente" {{ request('estado') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                    <option value="Em Andamento" {{ request('estado') == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                    <option value="Concluído" {{ request('estado') == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                    <option value="Cancelado" {{ request('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tipo">Filtrar por Tipo:</label>
                <select id="tipo" name="tipo">
                    <option value="">Todos os Tipos</option>
                    <option value="Roubo" {{ request('tipo') == 'Roubo' ? 'selected' : '' }}>Roubo</option>
                    <option value="Assalto" {{ request('tipo') == 'Assalto' ? 'selected' : '' }}>Assalto</option>
                    <option value="Violência Doméstica" {{ request('tipo') == 'Violência Doméstica' ? 'selected' : '' }}>Violência Doméstica</option>
                    <option value="Acidente de Trânsito" {{ request('tipo') == 'Acidente de Trânsito' ? 'selected' : '' }}>Acidente de Trânsito</option>
                    <option value="Distúrbio Público" {{ request('tipo') == 'Distúrbio Público' ? 'selected' : '' }}>Distúrbio Público</option>
                    <option value="Outro" {{ request('tipo') == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="agente">Filtrar por Agente:</label>
                <select id="agente" name="agente_id">
                    <option value="">Todos os Agentes</option>
                    @foreach($agentes as $agente)
                    <option value="{{ $agente->id }}" {{ request('agente_id') == $agente->id ? 'selected' : '' }}>
                        {{ $agente->name }} ({{ $agente->username }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        // Validação de datas
        document.getElementById('data_inicio').addEventListener('change', function() {
            const dataInicio = this.value;
            const dataFim = document.getElementById('data_fim').value;
            
            if (dataFim && dataInicio > dataFim) {
                alert('A data de início não pode ser posterior à data de fim.');
                this.value = '';
            }
        });

        document.getElementById('data_fim').addEventListener('change', function() {
            const dataFim = this.value;
            const dataInicio = document.getElementById('data_inicio').value;
            
            if (dataInicio && dataFim < dataInicio) {
                alert('A data de fim não pode ser anterior à data de início.');
                this.value = '';
            }
        });
    </script>
</body>
</html> 