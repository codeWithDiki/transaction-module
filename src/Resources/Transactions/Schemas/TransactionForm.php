<?php

namespace CodeWithDiki\TransactionModule\Resources\Transactions\Schemas;

use CodeWithDiki\TransactionModule\Enums\PaymentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('trx_id')
                    ->required(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('tax_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('grand_total')
                    ->required()
                    ->numeric(),
                Select::make('payment_status')
                    ->options(PaymentStatus::class)
                    ->required(),
                DateTimePicker::make('paid_at'),
                DateTimePicker::make('failed_at'),
            ]);
    }
}
