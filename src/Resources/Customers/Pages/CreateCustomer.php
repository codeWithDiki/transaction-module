<?php

namespace CodeWithDiki\TransactionModule\Resources\Customers\Pages;

use CodeWithDiki\TransactionModule\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
