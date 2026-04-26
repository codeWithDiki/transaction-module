<?php

namespace CodeWithDiki\TransactionModule\Observers;

use CodeWithDiki\TransactionModule\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        
    }

    public function updated(Transaction $transaction)
    {
        if($transaction->isDirty('payment_status') && $transaction->payment_status === 'paid') {

        }
    }

}