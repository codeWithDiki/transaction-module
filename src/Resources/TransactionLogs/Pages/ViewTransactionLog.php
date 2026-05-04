<?php

namespace CodeWithDiki\TransactionModule\Resources\TransactionLogs\Pages;

use CodeWithDiki\TransactionModule\Resources\TransactionLogs\TransactionLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransactionLog extends ViewRecord
{
    protected static string $resource = TransactionLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
