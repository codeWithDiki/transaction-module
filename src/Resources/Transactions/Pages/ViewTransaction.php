<?php

namespace CodeWithDiki\TransactionModule\Resources\Transactions\Pages;

use CodeWithDiki\TransactionModule\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
