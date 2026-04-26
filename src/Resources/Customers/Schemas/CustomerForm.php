<?php

namespace CodeWithDiki\TransactionModule\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email(),
                        TextInput::make('phone_number')
                            ->tel(),
                        TextInput::make('address'),
                    ])
                    ->aside()
                    ->columns(1),
            ])
            ->columns(1);
    }
}
