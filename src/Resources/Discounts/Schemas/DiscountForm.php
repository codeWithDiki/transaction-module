<?php

namespace CodeWithDiki\TransactionModule\Resources\Discounts\Schemas;

use CodeWithDiki\TransactionModule\Enums\DiscountType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Discount Information")
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Toggle::make('is_active')
                            ->required(),
                        Select::make('type')
                            ->options(DiscountType::class)
                            ->required(),
                        TextInput::make('value')
                            ->required()
                            ->numeric(),
                    ])
                    ->aside()
                    ->columns(1),
            ])
            ->columns(1);
    }
}
