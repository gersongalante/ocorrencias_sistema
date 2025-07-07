<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EsquadraResource\Pages;
use App\Filament\Resources\EsquadraResource\RelationManagers;
use App\Models\Esquadra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class EsquadraResource extends Resource
{
    protected static ?string $model = Esquadra::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome')->label('Nome')->required(),
                Forms\Components\TextInput::make('provincia')->label('Província')->required(),
                Forms\Components\TextInput::make('municipio')->label('Município')->required(),
                Forms\Components\TextInput::make('bairro')->label('Bairro')->required(),
                Forms\Components\TextInput::make('rua')->label('Rua')->nullable(),
                Forms\Components\TextInput::make('telefone')->label('Telefone')->nullable(),
                Forms\Components\TextInput::make('email')->label('Email')->email()->nullable(),
                Forms\Components\TextInput::make('responsavel')->label('Responsável')->nullable(),
                Forms\Components\Textarea::make('observacoes')->label('Observações')->nullable(),
                Forms\Components\Toggle::make('ativa')->label('Esquadra Ativa')->required()->visible(fn() => auth()->user()?->role === 'Administrador'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->label('Nome'),
                Tables\Columns\TextColumn::make('provincia')->label('Província'),
                Tables\Columns\TextColumn::make('municipio')->label('Município'),
                Tables\Columns\TextColumn::make('bairro')->label('Bairro'),
                Tables\Columns\TextColumn::make('rua')->label('Rua')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('telefone')->label('Telefone')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email')->label('Email')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('responsavel')->label('Responsável')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('observacoes')->label('Observações')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('ativa')->label('Ativa')->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListEsquadras::route('/'),
            'create' => Pages\CreateEsquadra::route('/create'),
            'edit' => Pages\EditEsquadra::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $role = auth()->user()?->role;
        return in_array($role, ['Administrador', 'Comandante']);
    }
}
