<?php

namespace CodeWithDiki\TransactionModule\Resources\TransactionLogs\Pages;

use CodeWithDiki\TransactionModule\Resources\TransactionLogs\TransactionLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransactionLogs extends ListRecords
{
    protected static string $resource = TransactionLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
