<?php

namespace App\Filament\Resources\ComponentResource\RelationManagers;

use App\Models\Price\ComponentPrice;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComponentPricesRelationManager extends RelationManager
{
    protected static string $relationship = 'componentPrices';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Component Prices');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('created_at')
                    ->label(__('Effective Date'))
                    ->content(fn (ComponentPrice $record): ?string => $record->created_at?->format('l, F j, Y g:i A'))
                    ->disabled()
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label(__('Price'))
                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?/'])
                    ->required()
                    ->numeric(2),

                TextInput::make('currency')
                    ->label(__('Currency')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Effective Date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('price')
                    ->label(__('Price'))
                    ->summarize(Average::make(__('Summary'))
                        ->label(__('Average Price'))
                        ->money())
                    ->money()
                    ->sortable(),

                TextColumn::make('currency')
                    ->label(__('Currency')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotificationTitle(__('Price updated.')),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
}
