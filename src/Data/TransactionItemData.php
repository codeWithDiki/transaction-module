<?php

namespace CodeWithDiki\TransactionModule\Data;

use Illuminate\Database\Eloquent\Model;

class TransactionItemData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public Model $itemable,
        public string $name,
        public ?string $description = null,
        public int $quantity,
        public float $price,
        public ?float $total = null,
    ) {}
}