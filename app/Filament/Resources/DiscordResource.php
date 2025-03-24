<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscordResource\Pages;
use App\Filament\Resources\DiscordResource\RelationManagers;
use App\Models\Discord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class DiscordResource extends Resource
{
    protected static ?string $model = Discord::class;
    protected static ?string $navigationGroup = 'Webhooks';
    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter webhook name'),
                TextInput::make('url')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter webhook URL'),
                Select::make('type')
                    ->options([
                        'chall' => 'Challenge',
                        'hint' => 'Hint',
                        'submision' => 'Submission',
                    ])
                    ->required()
                    ->placeholder('Select webhook type'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required()
                    ->placeholder('Select webhook status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('url')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->searchable(),
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
            'index' => Pages\ListDiscords::route('/'),
            'create' => Pages\CreateDiscord::route('/create'),
            'edit' => Pages\EditDiscord::route('/{record}/edit'),
        ];
    }
}
