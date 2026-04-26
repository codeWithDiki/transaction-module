<?php

namespace CodeWithDiki\TransactionModule;

use CodeWithDiki\TransactionModule\Data\CustomerData;
use CodeWithDiki\TransactionModule\Data\TransactionData;
use CodeWithDiki\TransactionModule\Data\TransactionItemData;
use CodeWithDiki\TransactionModule\Models\Customer;
use CodeWithDiki\TransactionModule\Models\Transaction;
use Illuminate\Support\Collection;
use CodeWithDiki\TransactionModule\Enums\PaymentStatus;

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
        return $customerClass::create($customer->toArray());
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

}
