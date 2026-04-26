<?php

namespace CodeWithDiki\TransactionModule\Data;

use CodeWithDiki\TransactionModule\Enums\PaymentStatus;

class TransactionData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?string $trx_id = null,
        public ?int $customer_id = null,
        public ?float $total_amount = null,
        public ?float $tax_amount = null,
        public ?float $grand_total = null,
        public ?PaymentStatus $payment_status = null,
    ) {}
}