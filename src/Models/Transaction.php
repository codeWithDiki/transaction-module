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
        'status' => \CodeWithDiki\TransactionModule\Enums\TransactionStatus::class,
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function logs()
    {
        return $this->hasMany(config('transaction-module.log_class'));
    }

    public function markAsPaid()
    {
        $this->payment_status = \CodeWithDiki\TransactionModule\Enums\PaymentStatus::PAID;
        $this->paid_at = now();
        $this->status = config('transaction-module.status_after_payment', \CodeWithDiki\TransactionModule\Enums\TransactionStatus::PROCESSING);
        $this->save();
    }

    public function markAsFailed()
    {
        $this->payment_status = \CodeWithDiki\TransactionModule\Enums\PaymentStatus::FAILED;
        $this->failed_at = now();
        $this->save();
    }

}