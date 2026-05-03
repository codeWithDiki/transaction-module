<?php

namespace CodeWithDiki\TransactionModule;

use CodeWithDiki\TransactionModule\Data\CustomerData;
use CodeWithDiki\TransactionModule\Data\TransactionData;
use CodeWithDiki\TransactionModule\Data\TransactionItemData;
use CodeWithDiki\TransactionModule\Models\Customer;
use CodeWithDiki\TransactionModule\Models\Transaction;
use Illuminate\Support\Collection;
use CodeWithDiki\TransactionModule\Enums\PaymentStatus;
use CodeWithDiki\TransactionModule\Events\TransactionStatusChangedEvent;

class TransactionModule {

    /**
     * Ini adalah method untuk membuat transaksi baru dan itemnya juga
     * @params TransactionData $data ini untuk data transaksi
     * @params Collection $items ini untuk isi item dari transaksi yang akan dibuat
     * @return Transaction method ini akan mengembalikan data transaksi yang barudibuat 
     */
    public function createTransaction(TransactionData $data, Collection $items) : Transaction
    {
        $transactionClass = config('transaction-module.transaction_class');
        $tax = config('transaction-module.tax', 0);

        $data->tax_amount = ($data->total_amount * $tax) / 100;
        $data->grand_total = $data->total_amount + $data->tax_amount;

        if($data->total_amount != $items->sum(fn(TransactionItemData $item) => ($item->total ?? ($item->quantity * $item->price)))) {
            throw new \Exception("Total amount does not match with the sum of items total.");
        }

        $transaction = $transactionClass::create($data->toArray());

        collect($items)->each(function(TransactionItemData $itemData) use ($transaction) {
            $transaction->items()->create([
                'itemable_type' => get_class($itemData->itemable),
                'itemable_id' => $itemData->itemable->id,
                'name' => $itemData->name,
                'description' => $itemData->description,
                'quantity' => $itemData->quantity,
                'price' => $itemData->price,
                'total' => $itemData->total ?? ($itemData->quantity * $itemData->price),
            ]);
        });

        return $transaction;

    }

    public function createCustomer(CustomerData $customer) : Customer
    {
        $customerClass = config('transaction-module.customer_class');
        return $customerClass::firstOrCreate([
            "email" => $customer->email,
        ], [
            "name" => $customer->name,
            "phone_number" => $customer->phone_number,
            "address" => $customer->address,
        ]);
    }

    public function getCustomerByEmail(string $email) : ?Customer
    {
        $customerClass = config('transaction-module.customer_class');
        return $customerClass::where('email', $email)->first();
    }

    public function getWalkInCustomer() : ?Customer
    {
        $customerClass = config('transaction-module.customer_class');
        return $customerClass::where('email', 'walkin@customer.id')->orWhere('id', 1)->first();
    }

    public function getTransactions(
        ?string $from = null,
        ?string $to = null,
        ?PaymentStatus $status = null,
        ?int $limit = null,
    ) : \Illuminate\Database\Eloquent\Collection
    {
        return Transaction::with('items')
        ->when($status, function($query) use ($status) {
            $query->where('payment_status', $status);
        })
        ->when($from, function($query) use ($from) {
            $query->whereDate('created_at', '>=', $from);
        })
        ->when($to, function($query) use ($to) {
            $query->whereDate('created_at', '<=', $to);
        })
        ->when($limit, function($query) use ($limit) {
            $query->limit($limit);
        })
        ->latest()
        ->get();
    }

    public function getTransactionsCountsByDate(?PaymentStatus $status = null, ?string $from = null, ?string $to = null) : int
    {
        return Transaction::when($status, function($query) use ($status) {
                $query->where('payment_status', $status);
            })
            ->when($from, function($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            })
            ->count();
    }

    public function getTransactionSumByDate(?PaymentStatus $status = null, ?string $from = null, ?string $to = null) : float
    {
        return Transaction::when($status, function($query) use ($status) {
                $query->where('payment_status', $status);
            })
            ->when($from, function($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            })
            ->sum("total_amount");
    }

    public function getTransactionByTrxId(string $trxId) : ?Transaction
    {
        return Transaction::with('items')->where('trx_id', $trxId)->first();
    }

    public function getTransactionById(int $id) : ?Transaction
    {
        return Transaction::with('items')->where('id', $id)->first();
    }

    public function getTransactionsByCustomerEmail(string $email) : \Illuminate\Database\Eloquent\Collection
    {
        return Transaction::with('items')->whereHas('customer', function($query) use ($email) {
            $query->where('email', $email);
        })->get();
    }

    public function setTransactionStatus(
        Transaction $transaction, 
        \CodeWithDiki\TransactionModule\Enums\TransactionStatus $status, 
        ?string $note = null, 
        ?\App\Models\User $user = null) : Transaction
    {
        $current_status = $transaction->status;

        // Create log
        $logClass = config('transaction-module.log_class');
        $log = $logClass::create([
            'transaction_id' => $transaction->id,
            'from_status' => $current_status,
            'to_status' => $status,
            'note' => $note,
        ]);

        $transaction->status = $status;
        $transaction->save();

        // Fire event
        TransactionStatusChangedEvent::dispatch($transaction, $log);

        return $transaction;
    }

    public function setPaymentStatus(
        Transaction $transaction, 
        PaymentStatus $status, 
        ?string $note = null, 
        ?\App\Models\User $user = null
    ) : Transaction
    {
        $current_status = $transaction->payment_status;

        if($status == PaymentStatus::PAID) {
            $transaction->markAsPaid();
        } else if($status == PaymentStatus::FAILED) {
            $transaction->markAsFailed();
        } else {
            $transaction->payment_status = $status;
            $transaction->save();
        }

        // Create log
        $logClass = config('transaction-module.log_class');
        $log = $logClass::create([
            'transaction_id' => $transaction->id,
            'from_status' => $current_status,
            'to_status' => $status,
            'note' => $note,
        ]);

        // Fire event
        TransactionStatusChangedEvent::dispatch($transaction, $log);

        return $transaction;
    }


}
