<?php

namespace CodeWithDiki\TransactionModule\Commands;

use CodeWithDiki\TransactionModule\Models\Customer;
use Illuminate\Console\Command;

class CreateWalkInCustomerCommand extends Command
{
    public $signature = 'transaction-module:create-walk-in-customer';

    public $description = 'Create a walk-in customer in the database';

    public function handle(): int
    {
        Customer::factory()->count(1)->create([
            "name" => "Walk-In Customer",
            "email" => "walkin@customer.id"
        ]);

        $this->info('Walk-in customer created successfully.');

        return self::SUCCESS;
    }
}
