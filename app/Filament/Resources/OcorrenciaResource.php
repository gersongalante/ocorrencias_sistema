<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OcorrenciaResource\Pages;
use App\Filament\Resources\OcorrenciaResource\RelationManagers;
use App\Models\Ocorrencia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class OcorrenciaResource extends Resource
{
    protected static ?string $model = Ocorrencia::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tipo')
                    ->label('Tipo de Ocorrência')
                    ->options([
                        'Roubo' => 'Roubo',
                        'Furto' => 'Furto',
                        'Agressão' => 'Agressão',
                        'Acidente de Trânsito' => 'Acidente de Trânsito',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\DateTimePicker::make('data_hora')
                    ->label('Data e Hora')
                    ->required(),
                Forms\Components\TextInput::make('provincia')->required(),
                Forms\Components\TextInput::make('municipio')->required(),
                Forms\Components\TextInput::make('bairro')->required(),
                Forms\Components\TextInput::make('rua')->nullable(),
                Forms\Components\Textarea::make('vitimas')->required(),
                Forms\Components\Textarea::make('descricao')->label('Descrição Detalhada')->required(),
                Forms\Components\FileUpload::make('anexos')
                    ->label('Anexos')
                    ->multiple()
                    ->directory('ocorrencias/anexos')
                    ->preserveFilenames()
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->nullable(),
                Forms\Components\Select::make('esquadra_id')
                    ->label('Esquadra')
                    ->relationship('esquadra', 'nome')
                    ->searchable()
                    ->required()
                    ->disabled(fn() => auth()->user()?->role === 'Agente')
                    ->default(fn() => auth()->user()?->esquadra_id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('tipo')->label('Tipo'),
                Tables\Columns\TextColumn::make('data_hora')->label('Data e Hora')->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('provincia'),
                Tables\Columns\TextColumn::make('municipio'),
                Tables\Columns\TextColumn::make('bairro'),
                Tables\Columns\TextColumn::make('rua')->label('Rua')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('estado')->label('Estado'),
                Tables\Columns\TextColumn::make('esquadra.nome')->label('Esquadra'),
                Tables\Columns\TextColumn::make('user.name')->label('Agente'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => static::canEdit($record)),
                Tables\Actions\ViewAction::make()
                    ->visible(fn ($record) => !static::canEdit($record)),
                Tables\Actions\Action::make('visualizar_anexos')
                    ->label('Visualizar Anexos')
                    ->icon('heroicon-o-eye')
                    ->visible(fn ($record) => !empty($record->anexos))
                    ->action(function ($record) {})
                    ->modalHeading('Anexos da Ocorrência')
                    ->modalContent(function ($record) {
                        if (empty($record->anexos)) {
                            return new HtmlString('Nenhum anexo disponível.');
                        }
                        $html = '<ul class="space-y-2">';
                        foreach ($record->anexos as $anexo) {
                            $url = Storage::url($anexo);
                            $nome = basename($anexo);
                            $html .= "<li><a href='{$url}' target='_blank' class='text-blue-600 underline'>{$nome}</a></li>";
                        }
                        $html .= '</ul>';
                        return new HtmlString($html);
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->role === 'Administrador'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOcorrencias::route('/'),
            'create' => Pages\CreateOcorrencia::route('/create'),
            'edit' => Pages\EditOcorrencia::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        
        // Administrador e Comandante veem todas as ocorrências
        if ($user->role === 'Administrador' || $user->role === 'Comandante') {
            return parent::getEloquentQuery();
        }
        
        // Agente só vê ocorrências da sua esquadra
        if ($user->role === 'Agente') {
            return parent::getEloquentQuery()->where('esquadra_id', $user->esquadra_id);
        }
        
        return parent::getEloquentQuery();
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        
        // Administrador pode editar tudo
        if ($user->role === 'Administrador') {
            return true;
        }
        
        // Comandante só pode editar ocorrências da sua esquadra
        if ($user->role === 'Comandante') {
            return $record->esquadra_id === $user->esquadra_id;
        }
        
        // Agente só pode editar suas próprias ocorrências
        if ($user->role === 'Agente') {
            return $record->user_id === $user->id;
        }
        
        return false;
    }

    public static function canDelete($record): bool
    {
        $user = auth()->user();
        
        // Apenas administrador pode deletar
        return $user->role === 'Administrador';
    }
}
