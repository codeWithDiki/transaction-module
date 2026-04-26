<?php

namespace CodeWithDiki\TransactionModule\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodeWithDiki\TransactionModule\TransactionModule
 */
class TransactionModule extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CodeWithDiki\TransactionModule\TransactionModule::class;
    }
}
