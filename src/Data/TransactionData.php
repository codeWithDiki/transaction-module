<?php

namespace CodeWithDiki\TransactionModule\Data;

use CodeWithDiki\TransactionModule\Enums\PaymentStatus;
use CodeWithDiki\TransactionModule\Enums\TransactionStatus;

class TransactionData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public string $trx_id,
        public int $customer_id,
        public float $total_amount,
        public PaymentStatus $payment_status,
        public TransactionStatus $status,
        public ?float $tax_amount = null,
        public ?float $grand_total = null,
        public ?string $notes = null,

    ) {}
}