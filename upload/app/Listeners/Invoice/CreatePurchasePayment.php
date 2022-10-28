<?php

namespace App\Listeners\Invoice;

use App\Account;
use App\Events\PurchaseInvoiceCreated as Event;
use App\Receiving;
use App\ReceivingPayment;
use App\Supplier;

class CreatePurchasePayment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Supplier $supplier, ReceivingPayment $receivingPayment, Account $account)
    {
        $this->supplier = $supplier;
        $this->receivingPayment = $receivingPayment;
        $this->account = $account;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->receiving->dues > 0 && $event->receiving->status != Receiving::REFUND) {
            $this->supplier->updateSupplierBalance($event->receiving->supplier_id, $event->receiving->dues);
        }

        //make a sale Payment
        if ($event->receiving->payment > 0) {
            $this->receivingPayment->saveReceivingPayment($event->receiving);
            // Update Account Balance
            if ($event->receiving->status == Receiving::REFUND) {
                $this->account->updateBalance($event->account_id, $event->payment);
            } else {
                $this->account->updateBalance($event->account_id, null, $event->receiving->payment);
            }
        }
    }
}
