<?php

namespace App\Filament\Resources;

use App\Enums\WorkOrderStatus;
use App\Filament\Resources\WorkOrderResource\Pages;
//use App\Filament\Resources\WorkOrderResource\RelationManagers;
use App\Models\WorkOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Production');
    }

    public static function getNavigationLabel(): string
    {
        return __('Work Orders');
    }

    public static function getModelLabel(): string
    {
        return __('Work Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Work Orders');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Details'))
                    ->columns(3)
                    ->schema([
                        TextInput::make('number')
                            ->label(__('Work Order Number'))
                            ->required(),

                        Select::make('sales_order_id')
                            ->label(__('Sales Order Number'))
                            ->relationship('salesOrder', 'number'),

                        Select::make('product_id')
                            ->label(__('Product'))
                            ->relationship('product', 'name'),

                        Select::make('user_id')
                            ->label(__('Assigned To'))
                            ->relationship('user', 'name'),

                        DatePicker::make('start_date')
                            ->label(__('Start Date')),

                        DatePicker::make('end_date')
                            ->label(__('End Date')),

                        ToggleButtons::make('status')
                            ->label(__('Status'))
                            ->inline()
                            ->columnSpanFull()
                            ->options(WorkOrderStatus::class),

                        MarkdownEditor::make('notes')
                            ->label(__('Notes'))
                            ->columnSpan('full'),
                    ]),

                Section::make(__('Quantities'))
                    ->columns(3)
                    ->schema([
                        Placeholder::make('required_quantity')
                            ->label(__('Required Quantity')),

                        TextInput::make('produced_quantity')
                            ->label(__('Produced Quantity'))
                            ->numeric(),

                        TextInput::make('defect_quantity')
                            ->label(__('Defect Quantity'))
                            ->numeric(),

                        TextInput::make('verified_quantity')
                            ->label(__('Verified Quantity'))
                            ->numeric(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Assigned To'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('salesOrder.number')
                    ->label(__('Sales Order'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('number')
                    ->label(__('Work Order'))
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('start_date')
                    ->label(__('Start'))
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('end_date')
                    ->label(__('End'))
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('produced_quantity')
                    ->label(__('Produced'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('defect_quantity')
                    ->label(__('Defects'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('verified_quantity')
                    ->label(__('Verified'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('notes')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
