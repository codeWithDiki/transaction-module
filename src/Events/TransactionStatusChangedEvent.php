<?php

namespace CodeWithDiki\TransactionModule\Events;

use CodeWithDiki\TransactionModule\Models\Transaction;
use CodeWithDiki\TransactionModule\Models\TransactionLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionStatusChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Transaction $transaction,
        Public TransactionLog $log
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('transaction-status-changed'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'cwd.transaction-module.transaction-status-changed';
    }
}
