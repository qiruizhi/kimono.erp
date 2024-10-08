<?php

namespace App\Filament\Resources;

use App\Enums\InventoryStatus;
use App\Enums\ProductCategory;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('Operations');
    }

    public static function getNavigationLabel(): string
    {
        return __('Products');
    }

    public static function getModelLabel(): string
    {
        return __('Product');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Products');
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
                                    ->label(__('Product Name'))
                                    ->required(),

                                TextInput::make('code')
                                    ->label(__('Barcode')),

                                ToggleButtons::make('status')
                                    ->label(__('Status'))
                                    ->inline()
                                    ->options(InventoryStatus::class)
                                    ->default(InventoryStatus::Active),

                                Select::make('category')
                                    ->label(__('Category'))
                                    ->options(ProductCategory::class),

                                MarkdownEditor::make('notes')
                                    ->label(__('Notes'))
                                    ->columnSpan('full'),
                            ])
                            ->columnSpan(['lg' => fn (?Product $record) => $record === null ? 3 : 2]),
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
                        $record?->productPrices()->create([
                            'product_id' => $record->id,
                            'price' => $state['price'],
                            'currency' => $state['currency']
                        ]);
                    }),

                Section::make(__('Cost'))
                    ->schema([
                        TextInput::make('cost')
                            ->label(__('Cost'))
                            ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                            ->numeric(2),
                    ])
                    ->hiddenOn('create'),

                Section::make(__('Specifications'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('depth_value')
                                    ->label(__('Length value'))
                                    ->numeric(3),

                                TextInput::make('width_value')
                                    ->label(__('Width value'))
                                    ->placeholder(__(''))
                                    ->numeric(3),

                                TextInput::make('height_value')
                                    ->label(__('Height value'))
                                    ->numeric(3),

                                Select::make('depth_unit')
                                    ->label(__('Length unit'))
                                    ->options(static::distanceUnitOptions()),

                                Select::make('width_unit')
                                    ->label(__('Width unit'))
                                    ->options(static::distanceUnitOptions()),

                                Select::make('height_unit')
                                    ->label(__('Height unit'))
                                    ->options(static::distanceUnitOptions()),

                                TextInput::make('weight_value')
                                    ->label(__('Weight value'))
                                    ->numeric(3),

                                TextInput::make('volume_value')
                                    ->label(__('Volume value'))
                                    ->numeric(),

                                TextInput::make('primary_material')
                                    ->label(__('Primary material')),

                                Select::make('weight_unit')
                                    ->label(__('Weight unit'))
                                    ->options(static::weightUnitOptions()),

                                Select::make('volume_unit')
                                    ->label(__('Volume unit'))
                                    ->options(static::volumeUnitOptions()),

                                TextInput::make('secondary_material')
                                    ->label(__('Secondary material')),
                            ])
                    ])
                    ->collapsible()
                    ->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Product'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->label(__('Barcode'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('cost')
                    ->label(__('Cost'))
                    ->money()
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
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function distanceUnitOptions(): array
    {
        return [
            __('Metric') => [
                'mm' => __('Millimeter (mm)'),
                'cm' => __('Centimeter (cm)'),
            ],
            'Imperial' => [
                'in' => __('Inch / Inches (in)'),
                'ft' => 'Foot / Feet (ft)',
                'yd' => 'Yard (yd)',
            ],
        ];
    }

    public static function weightUnitOptions(): array
    {
        return [
            'Metric' => [
                'mg' => 'Milligram (mg)',
                'gm' => 'Gram (g)',
                'kg' => 'Kilogram (kg)',
                'mt' => 'Tonne (t)',
            ],
            'Imperial' => [
                'in' => 'Inch / Inches (in)',
                'ft' => 'Foot / Feet (ft)',
                'yd' => 'Yard (yd)',
            ],
        ];
    }

    public static function volumeUnitOptions(): array
    {
        return [
            'Metric' => [
                'ml' => 'Milliliter (mL)',
                'l' => 'Liter (L)',
            ],
            'Imperial' => [
                'cu_in' => 'Cubic Inch / Cubic Inches (in)',
                'cu_ft' => 'Cubic Foot / Cubic Feet (ft)',
                'cu_yd' => 'Cubic Yard (yd)',
                'fl_oz' => 'Fluid ounce (fl oz)',
                'pt' => 'Pint (pt)',
                'qt' => 'Quart (qt)',
                'gal' => 'Gallon (gal)',
            ],
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductPricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
