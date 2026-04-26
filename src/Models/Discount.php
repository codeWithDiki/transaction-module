<?php

namespace CodeWithDiki\TransactionModule\Models;

class Discount extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = [];

    protected $casts = [
        "type" => \CodeWithDiki\TransactionModule\Enums\DiscountType::class
    ];

}