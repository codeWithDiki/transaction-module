<?php

namespace CodeWithDiki\TransactionModule\Resources\Transactions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('customer_id')
                    ->numeric(),
                TextEntry::make('trx_id'),
                TextEntry::make('total_amount')
                    ->money(currency: 'IDR', locale: 'id-ID'),
                TextEntry::make('tax_amount')
                    ->money(currency: 'IDR', locale: 'id-ID'),
                TextEntry::make('grand_total')
                    ->money(currency: 'IDR', locale: 'id-ID'),
                TextEntry::make('payment_status')
                    ->badge(),
                TextEntry::make('paid_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('failed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
