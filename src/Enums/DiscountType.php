<?php

namespace CodeWithDiki\TransactionModule\Enums;

enum DiscountType: string
{
    case Percentage = 'Percentage';
    case FixedAmount = 'FixedAmount';

    public function symbol(): string
    {
        return match ($this) {
            self::Percentage => '%',
            self::FixedAmount => '$',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Percentage => 'primary',
            self::FixedAmount => 'success',
        };
    }

}