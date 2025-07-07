<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Ocorrência - {{ $ocorrencia->id }}</title>
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
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RELATÓRIO DE OCORRÊNCIA</h1>
        <h2>Sistema de Gestão de Ocorrências</h2>
    </div>

    <div class="info-section">
        <h3>Informações da Ocorrência</h3>
        <div class="info-row">
            <span class="label">Número:</span>
            <span class="value">{{ $ocorrencia->id }}</span>
        </div>
        <div class="info-row">
            <span class="label">Tipo:</span>
            <span class="value">{{ $ocorrencia->tipo }}</span>
        </div>
        <div class="info-row">
            <span class="label">Data e Hora:</span>
            <span class="value">{{ $ocorrencia->data_hora->format('d/m/Y H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Estado:</span>
            <span class="value">{{ $ocorrencia->estado }}</span>
        </div>
    </div>

    <div class="info-section">
        <h3>Localização</h3>
        <div class="info-row">
            <span class="label">Província:</span>
            <span class="value">{{ $ocorrencia->provincia }}</span>
        </div>
        <div class="info-row">
            <span class="label">Município:</span>
            <span class="value">{{ $ocorrencia->municipio }}</span>
        </div>
        <div class="info-row">
            <span class="label">Bairro:</span>
            <span class="value">{{ $ocorrencia->bairro }}</span>
        </div>
        @if($ocorrencia->rua)
        <div class="info-row">
            <span class="label">Rua:</span>
            <span class="value">{{ $ocorrencia->rua }}</span>
        </div>
        @endif
    </div>

    <div class="info-section">
        <h3>Detalhes da Ocorrência</h3>
        <div class="info-row">
            <span class="label">Vítimas:</span>
            <span class="value">{{ $ocorrencia->vitimas }}</span>
        </div>
        <div class="info-row">
            <span class="label">Descrição:</span>
            <span class="value">{{ $ocorrencia->descricao }}</span>
        </div>
    </div>

    <div class="info-section">
        <h3>Informações da Esquadra</h3>
        @if($ocorrencia->esquadra)
        <div class="info-row">
            <span class="label">Esquadra:</span>
            <span class="value">{{ $ocorrencia->esquadra->nome }}</span>
        </div>
        <div class="info-row">
            <span class="label">Província:</span>
            <span class="value">{{ $ocorrencia->esquadra->provincia }}</span>
        </div>
        <div class="info-row">
            <span class="label">Município:</span>
            <span class="value">{{ $ocorrencia->esquadra->municipio }}</span>
        </div>
        <div class="info-row">
            <span class="label">Bairro:</span>
            <span class="value">{{ $ocorrencia->esquadra->bairro }}</span>
        </div>
        @if($ocorrencia->esquadra->rua)
        <div class="info-row">
            <span class="label">Rua:</span>
            <span class="value">{{ $ocorrencia->esquadra->rua }}</span>
        </div>
        @endif
        @if($ocorrencia->esquadra->telefone)
        <div class="info-row">
            <span class="label">Telefone:</span>
            <span class="value">{{ $ocorrencia->esquadra->telefone }}</span>
        </div>
        @endif
        @if($ocorrencia->esquadra->email)
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $ocorrencia->esquadra->email }}</span>
        </div>
        @endif
        @if($ocorrencia->esquadra->responsavel)
        <div class="info-row">
            <span class="label">Responsável:</span>
            <span class="value">{{ $ocorrencia->esquadra->responsavel }}</span>
        </div>
        @endif
        @else
        <div class="info-row">
            <span class="value">Esquadra não atribuída</span>
        </div>
        @endif
    </div>

    <div class="info-section">
        <h3>Agente Responsável</h3>
        <div class="info-row">
            <span class="label">Nome:</span>
            <span class="value">{{ $ocorrencia->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Nome de Utilizador:</span>
            <span class="value">{{ $ocorrencia->user->username }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Relatório gerado em: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistema de Gestão de Ocorrências</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Imprimir</button>
        <button onclick="window.close()">Fechar</button>
    </div>
</body>
</html> 