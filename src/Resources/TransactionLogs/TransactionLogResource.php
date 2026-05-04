<?php

namespace CodeWithDiki\TransactionModule\Resources\TransactionLogs;

use CodeWithDiki\TransactionModule\Resources\TransactionLogs\Pages\CreateTransactionLog;
use CodeWithDiki\TransactionModule\Resources\TransactionLogs\Pages\EditTransactionLog;
use CodeWithDiki\TransactionModule\Resources\TransactionLogs\Pages\ListTransactionLogs;
use CodeWithDiki\TransactionModule\Resources\TransactionLogs\Pages\ViewTransactionLog;
use CodeWithDiki\TransactionModule\Resources\TransactionLogs\Schemas\TransactionLogForm;
use CodeWithDiki\TransactionModule\Resources\TransactionLogs\Schemas\TransactionLogInfolist;
use CodeWithDiki\TransactionModule\Resources\TransactionLogs\Tables\TransactionLogsTable;
use BackedEnum;
use CodeWithDiki\TransactionModule\Models\TransactionLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TransactionLogResource extends Resource
{
    protected static ?string $model = TransactionLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBarsArrowUp;

    protected static null|\UnitEnum|string $navigationGroup = 'Transaction Management';

    protected static ?string $recordTitleAttribute = 'transaction.trx_id';

    public static function form(Schema $schema): Schema
    {
        return TransactionLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TransactionLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionLogsTable::configure($table);
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
            'index' => ListTransactionLogs::route('/'),
            'view' => ViewTransactionLog::route('/{record}'),
        ];
    }
}
