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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
                    Tables\Actions\DeleteBulkAction::make(),
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
}
