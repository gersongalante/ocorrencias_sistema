<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome Completo')
                    ->required(),
                Forms\Components\TextInput::make('username')
                    ->label('Nome de Utilizador')
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->required(fn($context) => $context === 'create')
                    ->dehydrated(fn($state) => filled($state)),
                Forms\Components\Select::make('role')
                    ->label('Papel')
                    ->options([
                        'Agente' => 'Agente',
                        'Comandante' => 'Comandante',
                        'Administrador' => 'Administrador',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nome'),
                Tables\Columns\TextColumn::make('username')->label('Nome de Utilizador'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('role')->label('Papel'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'Administrador';
    }
}
