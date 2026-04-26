<?php

namespace CodeWithDiki\TransactionModule\Models;

use CodeWithDiki\TransactionModule\Observers\TransactionObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(TransactionObserver::class)]
class Transaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'payment_status' => \CodeWithDiki\TransactionModule\Enums\PaymentStatus::class,
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function markAsPaid()
    {
        $this->payment_status = \CodeWithDiki\TransactionModule\Enums\PaymentStatus::PAID;
        $this->save();
    }

}