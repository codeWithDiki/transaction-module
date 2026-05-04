<?php

namespace CodeWithDiki\TransactionModule\Enums;

enum TransactionStatus: string
{
    case ONHOLD = 'On-Hold';
    case PROCESSING = 'Processing';
    case ONDELIVERY = 'On-Delivery';
    case DELIVERED = 'Delivered';
    case CANCELLED = 'Cancelled';
    case RETURNED = 'Returned';
    case REFUNDED = 'Refunded';
    case FAILED = 'Failed';
    case COMPLETED = 'Completed';

    public function color() : string
    {
        return match($this) {
            self::ONHOLD => 'warning',
            self::PROCESSING => 'success',
            self::ONDELIVERY => 'success',
            self::DELIVERED => 'success',
            self::CANCELLED => 'danger',
            self::RETURNED => 'danger',
            self::REFUNDED => 'success',
            self::FAILED => 'danger',
            self::COMPLETED => 'success',
        };
    }

}