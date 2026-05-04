<?php

namespace CodeWithDiki\TransactionModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionLog extends Model
{
    protected $table = 'transaction_logs';

    protected $guarded = [];

    protected $casts = [
        "from_status" => \CodeWithDiki\TransactionModule\Enums\TransactionStatus::class,
        "to_status" => \CodeWithDiki\TransactionModule\Enums\TransactionStatus::class,
    ];

    public function transaction() : BelongsTo
    {
        return $this->belongsTo(config('transaction-module.transaction_class', Transaction::class));
    }


}