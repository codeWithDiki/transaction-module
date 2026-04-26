<?php

namespace CodeWithDiki\TransactionModule\Resources\TransactionItems\Pages;

use CodeWithDiki\TransactionModule\Resources\TransactionItems\TransactionItemResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTransactionItem extends ViewRecord
{
    protected static string $resource = TransactionItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
