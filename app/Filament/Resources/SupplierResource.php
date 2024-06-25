<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('Operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Supplier');
    }

    public static function getModelLabel(): string
    {
        return __('Supplier');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Suppliers');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Basic Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Supplier name'))
                            ->required(),

                        TextInput::make('contact_name')
                            ->label(__('Contact name')),

                        TextInput::make('email')
                            ->label(__('Email address'))
                            ->email(),

                        TextInput::make('phone')
                            ->label(__('Phone number'))
                            ->tel(),

                        TextInput::make('website')
                            ->label(__('Website'))
                            ->url(),

                        TextInput::make('notes')
                            ->label(__('Notes')),

                        Placeholder::make('created_at')
                            ->label(__('Created at'))
                            ->content(fn (Supplier $record): ?string => $record->created_at?->diffForHumans())
                            ->hidden(fn (?Supplier $record) => $record === null),

                        Placeholder::make('updated_at')
                            ->label(__('Updated at'))
                            ->content(fn (Supplier $record): ?string => $record->updated_at?->diffForHumans())
                            ->hidden(fn (?Supplier $record) => $record === null),
                    ])
                    ->columns()
                    ->columnSpan(['lg' => fn (?Supplier $record) => $record === null ? 3 : 2]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Supplier'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contact_name')
                    ->label(__('Contact'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('website')
                    ->label(__('Website'))
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('id','desc')
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
            'index' => Pages\ListSuppliers::route('/'),
//            'create' => Pages\CreateSupplier::route('/create'),
//            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
