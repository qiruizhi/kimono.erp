<?php

namespace App\Filament\Resources;

use App\Enums\BomOperation;
use App\Enums\BomStep;
use App\Enums\BomWorkStation;
use App\Enums\TimeUnit;
use App\Filament\Resources\BomResource\Pages;
//use App\Filament\Resources\BomResource\RelationManagers;
use App\Models\Bom;
use App\Models\Component;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Random\RandomException;

class BomResource extends Resource
{
    protected static ?string $model = Bom::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Production');
    }

    public static function getNavigationLabel(): string
    {
        return __('BOMs');
    }

    public static function getModelLabel(): string
    {
        return __('BOM');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Bill of Materials');
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ->schema(static::getBomDetailsForm())
                            ->columns(),

                        Section::make(__('Components'))
                            ->headerActions([
                                Action::make(__('Reset'))
                                    ->modalHeading(__('Are you sure?'))
                                    ->modalDescription(__('All existing components will be removed from the order.'))
                                    ->requiresConfirmation()
                                    ->color('danger')
                                    ->action(fn (Set $set) => $set('items', [])),
                            ])
                            ->schema([
                                static::getComponentsRepeater()
                            ])
                            ->hiddenOn('create'),

                        Section::make(__('Operations'))
                            ->schema([
                                static::getOperationsRepeater()
                            ])
                            ->hiddenOn('create'),

                        Section::make(__('Costs'))
                            ->schema([
                                TextInput::make('operating_cost')
                                    ->label(__('Operating Cost'))
                                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                                    ->numeric(2)
                                    ->readOnly()
                                    ->prefix('$'),

                                TextInput::make('component_cost')
                                    ->label(__('Component Cost'))
                                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                                    ->numeric(2)
                                    ->prefix('$')
                                    ->readOnly()
                                    ->afterStateHydrated(function (Get $get, Set $set) {
                                        self::updateBomTotals($get, $set);
                                    }),

                                // Not yet implemented. Total Component+Operating
                                TextInput::make('gross_cost')
                                    ->label(__('Gross Cost'))
                                    ->readOnly()
                                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                                    ->numeric(2)
                                    ->prefix('$'),
//                                    ->afterStateUpdated(function (Get $get, Set $set) {
//                                        self::updateBomTotals($get, $set);
//                                    }),

                                TextInput::make('margin')
                                    ->label(__('Margin'))
                                    ->suffix('%')
                                    ->numeric()
                                    ->default(30)
                                    ->live(true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateBomTotals($get, $set);
                                    }),

                                TextInput::make('total_cost')
                                    ->label(__('Total Cost'))
                                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                                    ->numeric(2)
                                    ->readOnly()
                                    ->prefix('$'),
                            ])
                            ->maxWidth('1/2')
                            ->columns()
                            ->hiddenOn('create'),

                        Grid::make()
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label(__('Created at'))
                                    ->content(fn (Bom $record): ?string => $record->created_at),

                                Placeholder::make('updated_at')
                                    ->label(__('Updated at'))
                                    ->content(fn (Bom $record): ?string => $record->updated_at),
                            ])
                            ->columns()
                            ->hidden(fn (?Bom $record) => $record === null),
                    ])
            ])
            ->columns();
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label(__('BOM #'))
                    ->searchable(),

                TextColumn::make('product.name')
                    ->label(__('Product'))
                    ->numeric()
                    ->sortable(),

                TextColumn::make('operating_cost')
                    ->label(__('Operating Cost'))
                    ->money()
                    ->summarize([
                        Sum::make()
                            ->label(__('Total'))
                            ->money(),
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('component_cost')
                    ->label(__('Component Cost'))
                    ->money()
                    ->summarize([
                        Sum::make()
                            ->label(__('Total'))
                            ->money(),
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('gross_cost')
                    ->label(__('Gross Cost'))
                    ->money()
                    ->summarize([
                        Sum::make()
                            ->label(__('Total'))
                            ->money(),
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('margin')
                    ->label(__('Margin'))
                    ->numeric()
                    ->summarize([
                        Average::make()
                            ->label(__('Average'))
                            ->numeric()
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total_cost')
                    ->label(__('Total Cost'))
                    ->money()
                    ->summarize([
                        Sum::make()
                            ->label(__('Total'))
                            ->money(),
                    ])
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListBoms::route('/'),
            'create' => Pages\CreateBom::route('/create'),
            'edit' => Pages\EditBom::route('/{record}/edit'),
        ];
    }

    /**
     * @throws RandomException
     */
    public static function getBomDetailsForm(): array
    {
        return [
            TextInput::make('number')
                ->label(__('BOM #'))
                ->default ('B-' . random_int(100000, 999999))
                ->disabledOn('edit')
                ->required()
                ->maxLength(32)
                ->unique(Bom::class,'number',ignoreRecord:true),

            Select::make('product_id')
                ->label(__('Product'))
                ->relationship('product', 'name')
                ->searchable()
                ->preload(),

            MarkdownEditor::make('notes')
                ->label(__('Notes'))
                ->columnSpanFull(),
        ];
    }

    public static function calculateTotalCompoCost(Get $get, Set $set): void
    {
        $compoQuantity = $get('quantity');
        $compoCost = $get('unit_cost');
        $totalCompoCost = $compoQuantity * $compoCost;
        $set('compo_amount', number_format($totalCompoCost, 2, '.', ''));
    }

    public static function getComponentsRepeater(): Repeater
    {
        $components = Component::get();

        return Repeater::make('bomComponents')
            ->label(__('Components'))
            ->relationship()
            ->schema([
                // Three fields in each row: component, quantity and price
                Select::make('component_id')
                    ->label(__('Component'))
                    ->relationship('component', 'name')
                    // Options are all components, but we have modified the display to also show the price as well
                    ->options(
                        $components->mapWithKeys(function (Component $component) {
                            return [$component->id => sprintf( '%s ($%s)', $component->name, $component->price)];
                        })
                    )
                    ->distinct()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Set $set) => $set('unit_cost', Component::find($state)?->price ?? 0))
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 3,
                    ])
                    ->searchable(),

                TextInput::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(fn($state, Set $set, Get $get) => self::calculateTotalCompoCost($get, $set))
                    ->columnSpan([
                        'md' => 2,
                    ]),

                TextInput::make('unit_cost')
                    ->label(__('Component Cost'))
                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                    ->numeric(2)
                    ->prefix('$')
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->readOnly(),

                TextInput::make('compo_amount')
                    ->label(__('Total Cost'))
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
                self::calculateTotalCompoCost($get, $set);
            })
            // After deleting a row, we need to update the totals
            ->deleteAction(
                fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::calculateTotalCompoCost($get, $set))
            )
            ->extraItemActions([
                Action::make('openComponent')
                    ->label(__('Open Component'))
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $compo = Component::find($itemData['component_id']);

                        if (!$compo) {
                            return null;
                        }

                        return ComponentResource::getUrl('edit', ['record' => $compo]);
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['component_id'])),
            ])
            ->orderColumn()
            ->defaultItems(1)
            ->hiddenLabel()
            ->reorderable(false)
            ->columns([
                'md' => 10,
            ])
            ->required();
    }

    public static function calculateTotalOpCost(Get $get, Set $set): void
    {
        $opTime = $get('op_time');
        $opCost = $get('op_unit_cost');
        $totalOpCost = $opTime * $opCost;
        $set('op_amount', number_format($totalOpCost, 2, '.', ''));
    }

    public static function getOperationsRepeater(): Repeater
    {
        return Repeater::make('bomOperations')
            ->label(__('Operations'))
            ->relationship()
            ->schema([
                Select::make('step')
                    ->label(__('Step'))
                    ->options(BomStep::class)
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->distinct(),

                Select::make('operation')
                    ->label(__('Operation'))
                    ->options(BomOperation::class)
                    ->columnSpan([
                        'md' => 2,
                    ]),

                Select::make('workstation')
                    ->label(__('Workstation'))
                    // Options are all operations
                    ->options(BomWorkStation::class)
                    ->columnSpan([
                        'md' => 2,
                    ]),

                Toggle::make('fixed_time')
                    ->label(__('Fixed Time'))
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->default(false),

                TextInput::make('op_time')
                    ->label(__('Time'))
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(fn($state, Set $set, Get $get) => self::calculateTotalOpCost($get, $set))
                    ->columnSpan([
                        'md' => 1,
                    ]),

                Select::make('unit_time')
                    ->label(__('Unit'))
                    ->options(TimeUnit::class)
                    ->default('s')
                    ->columnSpan([
                        'md' => 2,
                    ]),

                //Probably should make Operation Unit Cost as an Operation Model with its own resource instead of ENUM?
                TextInput::make('op_unit_cost')
                    ->label(__('Unit Cost'))
                    ->rules(['regex:/^\d{1,8}(\.\d{0,2})?$/'])
                    ->numeric(2)
                    ->prefix('$')
                    ->reactive()
                    ->afterStateUpdated(fn($state, Set $set, Get $get) => self::calculateTotalOpCost($get, $set))
                    ->columnSpan([
                        'md' => 2,
                    ]),

                TextInput::make('op_amount')
                    ->label(__('Operation Cost'))
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
                self::calculateTotalOpCost($get, $set);
            })
            // After deleting a row, we need to update the totals
            ->deleteAction(
                fn(Action $action) => $action->after(fn(Get $get, Set $set) => self::calculateTotalOpCost($get, $set))
            )
            ->orderColumn()
            ->defaultItems(1)
            ->hiddenLabel()
            ->reorderable(false)
            ->columns([
                'md' => 8,
            ])
            ->required();
    }

    public static function updateBomTotals(Get $get, Set $set): void
    {
        $selectedComponents = collect($get('bomComponents'))->filter(fn($item) => !empty($item['component_id']) && !empty($item['quantity']));

        $componentCost = $selectedComponents->reduce(function ($componentCost, $component) {
            return $componentCost + ($component['unit_cost'] * $component['quantity']);
        }, 0);

        $selectedOperations = collect($get('bomOperations'))->filter(fn($item) => !empty($item['op_amount']));

        $operationCost = $selectedOperations->reduce(function ($operationCost, $operation) {
            return $operationCost + $operation['op_amount'];
        }, 0);

        $grossCost = $componentCost + $operationCost;

        $set('component_cost', number_format($componentCost, 2, '.', ''));
        $set('operating_cost', number_format($operationCost, 2, '.', ''));
        $set('gross_cost', number_format($grossCost, 2, '.', ''));
        $set('total_cost', number_format($grossCost + ($grossCost * ($get('margin') / 100)), 2, '.', ''));
    }
}
