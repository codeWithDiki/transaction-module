<?php

namespace CodeWithDiki\TransactionModule\Resources\TransactionItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransactionItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('transaction_id')
                    ->numeric(),
                TextEntry::make('itemable_type'),
                TextEntry::make('itemable_id')
                    ->numeric(),
                TextEntry::make('price')
                    ->money(currency: 'IDR', locale: 'id-ID'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('total')
                    ->money(currency: 'IDR', locale: 'id-ID'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
