<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;
    public Transaction $transaction;

    public function __construct(Transaction $transaction, string $action)
    {
        $this->transaction = $transaction;
        $this->action = $action;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('transactions');
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->transaction->id,
            'action' => $this->action,
        ];
    }

    public function broadcastAs(): string
    {
        return 'TransactionChanged';
    }
}
