<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChallResource\Pages;
use App\Filament\Resources\ChallResource\RelationManagers;
use App\Models\Chall;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;

/**
 * Class ChallResource
 *
 * @package App\Filament\Resources
 */
class ChallResource extends Resource
{
    protected static ?string $model = Chall::class;
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter challenge title'),
                                TextInput::make('flag')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter flag format: CTF{flag}'),
                                TextInput::make('point')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('Enter point value'),
                                TextInput::make('author')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter author name'),
                            ])->columnSpan(1),
                        Grid::make()
                            ->schema([
                                Select::make('category_id')
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->required()
                                    ->placeholder('Select challenge category'),
                                Select::make('status')
                                    ->options([
                                        'open' => 'Open',
                                        'closed' => 'Closed',
                                    ])
                                    ->default('open')
                                    ->required()
                                    ->placeholder('Select challenge status'),
                                TextInput::make('attachment')
                                    ->maxLength(255)
                                    ->placeholder('Enter attachment URL or file path'),
                            ])->columnSpan(1),
                    ]),
                RichEditor::make('description')
                    ->required()
                    ->placeholder('Enter challenge description')
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles'
                    ]),
                Repeater::make('hints')
                    ->relationship('hints')
                    ->schema([
                        TextInput::make('hint')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter hint content'),
                    ])
                    ->columnSpanFull()
                    ->defaultItems(0)
                    ->reorderable()
                    ->collapsible()
                    ->cloneable()
                    ->itemLabel(fn(array $state): ?string => $state['hint'] ?? null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable()
                    ->limit(10)
                    ->tooltip(
                        fn(Chall $record) => $record->description
                    ),
                TextColumn::make('flag')
                    ->searchable(),
                TextColumn::make('point')
                    ->searchable(),
                TextColumn::make('author')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('attachment')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->searchable(),
                TextColumn::make('hints_count')
                    ->label('Hints')
                    ->counts('hints')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListChalls::route('/'),
            'create' => Pages\CreateChall::route('/create'),
            'edit' => Pages\EditChall::route('/{record}/edit'),
        ];
    }
}
