<?php

namespace App\Http\ViewComposers;

use App\Account;
use App\PaymentType;
use Illuminate\View\View;

class Invoice
{    
    public function __construct(Account $account, PaymentType $paymentType)
    {
        $this->account = $account;
        $this->paymentType = $paymentType;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $accounts = $this->account->getAll('select');
        $paymentTypes = $this->paymentType->getAll('select');
        $view->with(['accounts' => $accounts, 'payment_types'=>$paymentTypes]);
    }
}

