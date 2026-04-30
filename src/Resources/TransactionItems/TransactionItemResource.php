<?php

namespace CodeWithDiki\TransactionModule\Resources\TransactionItems;

use CodeWithDiki\TransactionModule\Resources\TransactionItems\Pages\ListTransactionItems;
use CodeWithDiki\TransactionModule\Resources\TransactionItems\Pages\ViewTransactionItem;
use CodeWithDiki\TransactionModule\Resources\TransactionItems\Schemas\TransactionItemForm;
use CodeWithDiki\TransactionModule\Resources\TransactionItems\Schemas\TransactionItemInfolist;
use CodeWithDiki\TransactionModule\Resources\TransactionItems\Tables\TransactionItemsTable;
use BackedEnum;
use CodeWithDiki\TransactionModule\Models\TransactionItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransactionItemResource extends Resource
{
    protected static ?string $model = TransactionItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static null|\UnitEnum|string $navigationGroup = 'Transaction Management';

    protected static ?string $recordTitleAttribute = 'transaction.trx_id';

    public static function getModel(): string
    {
        return config('transaction-module.transaction_item_class', self::$model);
    }

    public static function form(Schema $schema): Schema
    {
        return TransactionItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransactionItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionItemsTable::configure($table);
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
            'index' => ListTransactionItems::route('/'),
            'view' => ViewTransactionItem::route('/{record}'),
        ];
    }
}
