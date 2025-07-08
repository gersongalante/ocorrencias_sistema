# Sistema de Relatórios do Comandante

## Visão Geral

O sistema de relatórios do comandante permite que comandantes de esquadra visualizem e imprimam relatórios detalhados das ocorrências registadas pelos agentes da sua esquadra.

## Funcionalidades

### 1. Acesso ao Relatório
- **Via Filament**: Menu "Relatórios" > "Relatório da Esquadra"
- **Via URL Direta**: `/comandante/relatorio-filtros`

### 2. Filtros Disponíveis
- **Período**: Data de início e fim
- **Estado**: Pendente, Em Andamento, Concluído, Cancelado
- **Tipo**: Roubo, Assalto, Violência Doméstica, Acidente de Trânsito, Distúrbio Público, Outro
- **Agente**: Filtrar por agente específico ou todos os agentes

### 3. Conteúdo do Relatório
- **Informações da Esquadra**: Dados completos da esquadra
- **Resumo Estatístico**: Total de ocorrências por estado
- **Lista de Ocorrências**: Detalhes de todas as ocorrências do período
- **Lista de Agentes**: Agentes da esquadra com contagem de ocorrências

### 4. Estatísticas em Tempo Real
- Total de ocorrências do mês atual
- Ocorrências por estado (Pendentes, Em Andamento, Concluídas)
- Número de agentes ativos na esquadra

## Como Usar

### Passo 1: Acessar o Sistema
1. Faça login como comandante
2. Navegue para "Relatórios" > "Relatório da Esquadra"

### Passo 2: Configurar Filtros
1. Selecione o período desejado (data início e fim)
2. Opcionalmente, filtre por estado, tipo ou agente específico
3. Clique em "Gerar Relatório"

### Passo 3: Visualizar e Imprimir
1. O relatório abrirá em uma nova janela
2. Revise as informações
3. Clique em "Imprimir Relatório" para imprimir
4. Use "Fechar" para voltar ao sistema

## Rotas Disponíveis

- `/comandante/relatorio-filtros` - Página de filtros
- `/comandante/relatorio-esquadra` - Relatório gerado (com parâmetros)

## Segurança

- Apenas usuários com role "Comandante" podem acessar
- Comandantes só veem dados da sua própria esquadra
- Verificação automática de associação com esquadra

## Estrutura de Arquivos

```
resources/views/relatorios/
├── comandante-filtros.blade.php    # Página de filtros
└── comandante-esquadra.blade.php   # Template do relatório

app/Filament/Resources/
└── RelatorioComandanteResource.php # Recurso Filament

app/Filament/Widgets/
└── EstatisticasEsquadraWidget.php  # Widget de estatísticas

routes/web.php                      # Rotas do relatório
```

## Personalização

### Adicionar Novos Filtros
1. Edite `RelatorioComandanteResource.php`
2. Adicione novos campos no método `form()`
3. Atualize a rota em `web.php` para processar os novos filtros

### Modificar Layout do Relatório
1. Edite `comandante-esquadra.blade.php`
2. Ajuste CSS conforme necessário
3. Adicione ou remova seções conforme necessário

### Adicionar Novas Estatísticas
1. Edite `EstatisticasEsquadraWidget.php`
2. Adicione novos cálculos no método `getStats()`

## Suporte

Para dúvidas ou problemas, consulte a documentação do sistema ou entre em contacto com a equipa de desenvolvimento. 