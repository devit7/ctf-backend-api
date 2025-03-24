<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubmissionResource\Pages;
use App\Filament\Resources\SubmissionResource\RelationManagers;
use App\Models\Submisions;
use App\Models\Chall;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submisions::class;
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('chall_id')
                    ->label('Challenge')
                    ->options(Chall::all()->pluck('title', 'id'))
                    ->required()
                    ->placeholder('Select challenge'),
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required()
                    ->placeholder('Select user'),
                TextInput::make('flag_submited')
                    ->label('Flag Submitted')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter flag submitted'),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'correct' => 'Correct',
                        'incorrect' => 'Incorrect',
                    ])
                    ->default('correct')
                    ->required()
                    ->placeholder('Select submission status'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('chall_id')        
                    ->searchable(),
                TextColumn::make('chall.title')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'correct' => 'success',
                        'incorrect' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('flag_submited')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->searchable(),
                TextColumn::make('deleted_at')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListSubmissions::route('/'),
            'create' => Pages\CreateSubmission::route('/create'),
            'edit' => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
}
