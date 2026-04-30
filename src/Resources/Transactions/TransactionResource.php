<?php

namespace CodeWithDiki\TransactionModule\Resources\Transactions;

use CodeWithDiki\TransactionModule\Resources\Transactions\Pages\ListTransactions;
use CodeWithDiki\TransactionModule\Resources\Transactions\Pages\ViewTransaction;
use CodeWithDiki\TransactionModule\Resources\Transactions\Schemas\TransactionForm;
use CodeWithDiki\TransactionModule\Resources\Transactions\Schemas\TransactionInfolist;
use CodeWithDiki\TransactionModule\Resources\Transactions\Tables\TransactionsTable;
use BackedEnum;
use CodeWithDiki\TransactionModule\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static null|\UnitEnum|string $navigationGroup = 'Transaction Management';

    protected static ?string $recordTitleAttribute = 'trx_id';

    public static function getModel(): string
    {
        return config('transaction-module.transaction_class', self::$model);
    }

    public static function form(Schema $schema): Schema
    {
        return TransactionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransactionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            'view' => ViewTransaction::route('/{record}'),
        ];
    }
}
