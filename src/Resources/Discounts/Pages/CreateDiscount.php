<?php

namespace CodeWithDiki\TransactionModule\Resources\Discounts\Pages;

use CodeWithDiki\TransactionModule\Resources\Discounts\DiscountResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDiscount extends CreateRecord
{
    protected static string $resource = DiscountResource::class;
}
