<?php

namespace CodeWithDiki\TransactionModule\Data;

class CustomerData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $name,
        public ?string $email = null,
        public ?string $phone_number = null,
        public ?string $address = null,
    ) {
    }
}