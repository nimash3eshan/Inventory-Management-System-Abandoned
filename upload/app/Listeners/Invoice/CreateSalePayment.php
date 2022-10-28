<?php

namespace App\Listeners\Invoice;

use App\Account;
use App\Customer;
use App\Events\InvoiceCreated as Event;
use App\Sale;
use App\SalePayment;

class CreateSalePayment
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->sale->dues > 0 && $event->sale->status != Sale::REFUND) {
            $customer = new Customer();
            $customer->updateCustomerBalance($event->sale->customer_id, $event->sale->dues);
        }

        //make a sale Payment
        if ($event->sale->payment > 0) {
            $sale_payment = new SalePayment();
            $sale_payment->saveSalePayment($event->sale);
            // Update Account Balance
            $account = new Account();
            if ($event->sale->status == Sale::REFUND) {
                $account->updateBalance($event->account_id, null, $event->payment);
            } else {
                $account->updateBalance($event->account_id, $event->sale->payment);
            }
        }
    }
}
