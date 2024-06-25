<?php

namespace App\Traits;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class MakeSupplierForm
{
    public static function make(): Select
    {
        return
            Select::make('supplier_id')
                ->label(__('Supplier name'))
                ->relationship('supplier', 'name')
                ->searchable()
                ->preload()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label(__('Supplier name'))
                        ->required()
                        ->maxLength(140),

                    TextInput::make('contact_name')
                        ->label(__('Contact name'))
                        ->maxLength(140),

                    TextInput::make('email')
                        ->label(__('Email address'))
                        ->email()
                        ->maxLength(140)
                        ->unique(),

                    TextInput::make('phone')
                        ->label(__('Phone number'))
                        ->maxLength(140),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading(__('Create a new supplier'))
                        ->modalSubmitActionLabel(__('Create'))
                        ->modalWidth('lg');
                });
    }
}
