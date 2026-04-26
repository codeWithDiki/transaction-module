<?php

namespace CodeWithDiki\TransactionModule\Enums;

enum DiscountType: string
{
    case Percentage = 'Percentage';
    case FixedAmount = 'FixedAmount';
}