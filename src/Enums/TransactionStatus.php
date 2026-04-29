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
}