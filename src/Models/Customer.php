<?php

namespace CodeWithDiki\TransactionModule\Models;

use CodeWithDiki\TransactionModule\Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[UseFactory(CustomerFactory::class)]
class Customer extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    protected $guarded = [];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}