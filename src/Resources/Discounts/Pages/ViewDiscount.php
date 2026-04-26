<?php

namespace CodeWithDiki\TransactionModule\Resources\Discounts\Pages;

use CodeWithDiki\TransactionModule\Resources\Discounts\DiscountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDiscount extends ViewRecord
{
    protected static string $resource = DiscountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
