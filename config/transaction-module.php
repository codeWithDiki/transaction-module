<?php

// config for VendorName/Skeleton
return [
    "customer_class" => \CodeWithDiki\TransactionModule\Models\Customer::class,
    "transaction_class" => \CodeWithDiki\TransactionModule\Models\Transaction::class,
    "transaction_item_class" => \CodeWithDiki\TransactionModule\Models\TransactionItem::class,
    "discount_class" => \CodeWithDiki\TransactionModule\Models\Discount::class,
    "tax" => env("TAX_PERCENTAGE", 0), // in percentage
];
