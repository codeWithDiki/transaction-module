<?php

namespace CodeWithDiki\TransactionModule;

use Filament\Contracts\Plugin;
use Filament\Panel;

class TransactionModuleFilament implements Plugin
{
    public function getId(): string
    {
        return 'transaction-module';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                Resources\Transactions\TransactionResource::class,
                Resources\Discounts\DiscountResource::class,
                Resources\TransactionItems\TransactionItemResource::class,
                Resources\Customers\CustomerResource::class,
                Resources\TransactionLogs\TransactionLogResource::class,
            ])
            ->pages([

            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}