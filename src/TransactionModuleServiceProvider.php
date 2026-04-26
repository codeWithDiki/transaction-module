<?php

namespace CodeWithDiki\TransactionModule;

use CodeWithDiki\TransactionModule\Commands\CreateWalkInCustomerCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TransactionModuleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('transaction-module')
            ->hasConfigFile()
            ->hasCommand(CreateWalkInCustomerCommand::class)
            ->hasMigration('create_transaction_module_table');
    }
}
