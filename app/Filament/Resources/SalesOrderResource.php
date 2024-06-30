<?php

namespace App\Filament\Resources;

use App\Enums\SalesOrderStatus;
use App\Enums\ShippingMethod;
use App\Filament\Resources\SalesOrderResource\Pages;
//use App\Filament\Resources\SalesOrderResource\RelationManagers;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Traits\CreateCustomerForm;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Random\RandomException;

class SalesOrderResource extends Resource
{
    protected static ?string $model = SalesOrder::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('Sales Orders');
    }

    public static function getModelLabel(): string
    {
        return __('Sales Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Sales Orders');
    }

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    /**
     * @throws RandomException
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->columnSpanFull()
                    ->schema([
                        Section::make(__('Details'))
                            ->headerActions([
                                Action::make('createWorkOrder')
                                    ->label('Create Work Order')
                                    ->action(function () {
                                        // Define the action here, e.g., navigate to a form
                                        redirect()->route('work-orders.create');
                                    }),

                                Action::make('createInvoice')
                                    ->label('Create Invoice')
                                    ->action(function () {
                                        // Define the action here, e.g., navigate to a form
//                                        redirect()->route('work-orders.create');
                                    }),
                            ])
                            ->schema(static::getDetailsFormSchema())
                            ->columns(),

                        Section::make(__('Products'))
                            ->headerActions([
                                Action::make(__('Reset'))
                                    ->modalHeading(__('Are you sure?'))
                                    ->modalDescription(__('All existing items will be removed from the order.'))
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn (Set $set) => $set('salesOrderProducts', [])),
                            ])
                            ->schema([
                                static::getItemsRepeater()
                            ]),

                        Section::make(__('Totals'))
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label(__('Subtotal'))
                                    ->readOnly()
                                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                                    ->numeric(2)
                                    ->prefix('$')
                                    ->afterStateHydrated(function (Get $get, Set $set) {
                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('margin')
                                    ->label(__('Margin'))
                                    ->suffix('%')
                                    ->numeric()
                                    ->default(20)
                                    ->live(true),

                                TextInput::make('tax')
                                    ->label(__('Tax'))
                                    ->suffix('%')
                                    ->numeric()
                                    ->default(20)
                                    ->live(true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('total_amount')
                                    ->label(__('Total Price'))
                                    ->readOnly()
                                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                                    ->numeric(2)
                                    ->prefix('$')
                            ])
                            ->maxWidth('1/2')
                            ->columns(),

                        Grid::make()
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label(__('Created at'))
                                    ->content(fn (SalesOrder $record): ?string => $record->created_at),

                                Placeholder::make('updated_at')
                                    ->label(__('Updated at'))
                                    ->content(fn (SalesOrder $record): ?string => $record->updated_at),
                            ])
                            ->columns()
                            ->hidden(fn (?SalesOrder $record) => $record === null),
                    ])
            ])
            ->columns();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label(__('Order'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make(__('customer.name'))
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),

                TextColumn::make('total_amount')
                    ->label(__('Price'))
                    ->money()
                    ->summarize([
                        Sum::make()
                            ->label(__('Total'))
                            ->money(),
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('Date Created'))
                    ->date()
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesOrders::route('/'),
            'create' => Pages\CreateSalesOrder::route('/create'),
            'edit' => Pages\EditSalesOrder::route('/{record}/edit'),
        ];
    }

    /**
     * @throws RandomException
     */
    public static function getDetailsFormSchema(): array
    {
        return [
            TextInput::make('number')
                ->label(__('Order Number'))
                ->default ('S-' . random_int(100000, 999999))
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(32)
                ->unique(SalesOrder::class,'number',ignoreRecord:true),

            CreateCustomerForm::make(),

            ToggleButtons::make('status')
                ->label(__('Status'))
                ->inline()
                ->options(SalesOrderStatus::class)
                ->default('new'),

            DatePicker::make('delivery_date')
                ->label(__('Delivery Date')),

            Select::make('shipping_method')
                ->label(__('Shipping Method'))
                ->options(ShippingMethod::class),

            TextInput::make('currency')
                ->label(__('Currency')),

            MarkdownEditor::make('notes')
                ->label(__('Notes'))
                ->columnSpan('full'),
        ];
    }

    public static function calculateOrderPrice(Get $get, Set $set): void
    {
        $requiredQuantity = $get('required_quantity');
        $unitPrice = $get('unit_price');
        $productAmount = $requiredQuantity * $unitPrice;
        $set('product_amount', number_format($productAmount, 2, '.', ''));
    }

    public static function updateTotals(Get $get, Set $set): void
    {
        $selectedProducts = collect($get('salesOrderProducts'))->filter(fn($item) => !empty($item['product_id']) && !empty($item['required_quantity']));

        $subtotal = $selectedProducts->reduce(function ($subtotal, $product) {
            return $subtotal + ($product['unit_price'] * $product['required_quantity']);
        }, 0);

        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('total_amount', number_format($subtotal + ($subtotal * ($get('tax') / 100)), 2, '.', ''));
    }

    public static function getItemsRepeater(): Repeater
    {
        $products = Product::get();

        return Repeater::make('salesOrderProducts')
            ->label(__('Products'))
            ->relationship()
            ->schema([
                // Three fields in each row: product, quantity and price
                Select::make('product_id')
                    ->label(__('Product'))
                    ->relationship('product', 'name')
                    // Options are all products, but we have modified the display to also show the price as well
                    ->options(
                        $products->mapWithKeys(function (Product $product) {
                            return [$product->id => sprintf('%s ($%s)', $product->name, $product->price)];
                        })
                    )
                    ->distinct()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 3,
                    ])
                    ->searchable()
                    ->required(),

                TextInput::make('required_quantity')
                    ->label(__('Required quantity'))
                    ->numeric()
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(fn($state, Set $set, Get $get) => self::calculateOrderPrice($get, $set))
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->required(),

                TextInput::make('unit_price')
                    ->label(__('Unit Price'))
                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                    ->numeric(2)
                    ->prefix('$')
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->readOnly(),

                TextInput::make('product_amount')
                    ->label(__('Amount'))
                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                    ->numeric(2)
                    ->prefix('$')
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->readOnly(),
            ])
            // Repeatable field is live so that it will trigger the state update on each change
            ->live()
            // After adding a new row, we need to update the totals
            ->afterStateUpdated(function (Get $get, Set $set) {
                self::updateTotals($get, $set);
            })
            // After deleting a row, we need to update the totals
            ->deleteAction(
                fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::updateTotals($get, $set)),
            )
            ->extraItemActions([
                Action::make('openProduct')
                    ->label(__('Open product'))
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $product = Product::find($itemData['product_id']);

                        if (! $product) {
                            return null;
                        }

                        return ProductResource::getUrl('edit', ['record' => $product]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['product_id'])),
            ])
            ->orderColumn()
            ->defaultItems(1)
            ->hiddenLabel()
            ->columns([
                'md'=>10,
            ])
            ->required();
    }
}
