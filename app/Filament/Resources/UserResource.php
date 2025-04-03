<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter full name'),
                TextInput::make('username')
                    ->required()
                    ->unique( ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('Enter username'),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->placeholder('Enter email address'),
                TextInput::make('institution')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter institution name'),
                TextInput::make('password')
                    ->required()
                    ->password()
                    ->minLength(8)
                    ->placeholder('Enter password')
                    ->afterStateHydrated(function (Forms\Components\TextInput $component, $state) {
                        $component->state('');
                    })
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn($livewire) => ($livewire instanceof CreateRecord)),
                Select::make('role')
                    ->options([
                        'superadmin' => 'Super Admin',
                        'admin' => 'Admin',
                        'user' => 'User',
                    ])
                    ->default('user')
                    ->required()
                    ->placeholder('Select user role'),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required()
                    ->placeholder('Select user status'),
                Textarea::make('bio')
                    ->maxLength(500)
                    ->placeholder('Enter bio')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('bio')
                    ->searchable()
                    ->limit(10),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('institution')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'info',
                        'superadmin' => 'danger',
                        'user' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //

            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
