<?php

namespace App\Filament\Resources;

use App\Enums\ComponentType;
use App\Enums\InventoryStatus;
use App\Filament\Resources\ComponentResource\Pages;
use App\Filament\Resources\ComponentResource\RelationManagers;
use App\Models\Component;
use App\Traits\CreateSupplierForm;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return (__('Operations'));
    }

    public static function getNavigationLabel(): string
    {
        return (__('Components'));
    }

    public static function getModelLabel(): string
    {
        return __('Component');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Components');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Details'))
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Component name'))
                                    ->required(),

                                TextInput::make('code')
                                    ->label(__('Component code')),

                                ToggleButtons::make('status')
                                    ->label(__('Status'))
                                    ->inline()
                                    ->options(InventoryStatus::class)
                                    ->default(InventoryStatus::Active),

                                CreateSupplierForm::make(),

                                TextInput::make('supplier_product_name')
                                    ->label(__('Supplier product name')),

                                TextInput::make('supplier_code')
                                    ->label(__('Supplier code')),

                                Select::make('type')
                                    ->label(__('Type'))
                                    ->options(ComponentType::class),

                                MarkdownEditor::make('notes')
                                    ->label(__('Notes'))
                                    ->columnSpan('full'),
                            ])
                            ->columnSpan(['lg' => fn (?Component $record) => $record === null ? 3 : 2]),
                    ]),

                Section::make(__('Pricing'))
                    ->schema([
                        TextInput::make('price')
                            ->label(__('Price'))
                            ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                            ->required()
                            ->numeric(2),

                        TextInput::make('currency')
                            ->label(__('Currency'))
                            ->required(),
                    ])
                    ->columns()
                    ->hiddenOn('create')
                    ->afterStateUpdated(function ($state, $record) {
                        // This hook will run when the form is being saved
                        // Update or create entries in the productPrices table
                        $record?->componentPrices()->create([
                            'component_id' => $record->id,
                            'price' => $state['price'],
                            'currency' => $state['currency']
                        ]);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Component'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label(__('Code'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('supplier.name')
                    ->label(__('Supplier'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('supplier_product_name')
                    ->label(__('Supplier product name'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('supplier_code')
                    ->label(__('Supplier SKU'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actionsPosition(ActionsPosition::BeforeColumns)
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hiddenLabel(),
                Tables\Actions\ReplicateAction::make()
                    ->hiddenLabel(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ComponentPricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComponents::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }
}
