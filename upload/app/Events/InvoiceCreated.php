<?php

namespace App\Events;

use App\Customer;
use App\Sale;
use App\SalePayment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sale;
    public $account_id;
    public $payment;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sale, $account_id, $payment)
    {
        $this->sale = $sale;
        $this->account_id = $account_id;
        $this->payment = $payment;
    }

}
