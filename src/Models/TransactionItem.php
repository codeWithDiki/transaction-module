<?php

namespace CodeWithDiki\TransactionModule\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;

class TransactionItem extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = [];

    public function itemable() : MorphTo
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}