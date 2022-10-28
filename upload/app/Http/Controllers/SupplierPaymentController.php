<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use App\Supplier;
use App\SupplierPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierPaymentController extends Controller
{
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();
        $input['user_id'] = Auth::user()->id;
        SupplierPayment::create($input);

        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier->payment = $supplier->payment + $request->payment;
        $supplier->prev_balance = $supplier->prev_balance - $request->payment;
        $supplier->update();

        // Update Account Balance
        $this->account->updateBalance($request->account_id, null, $request->payment);

        $data = $supplier->processShowProfile($supplier->id);
        $data['supplier'] = $supplier;
        return $this->sendCommonResponse($data, __('You have successfully added Payments for Supplier ' . $supplier->name), 'show');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'payment_type' => 'required|max:100',
            'payment' => 'required|gt:0|numeric|max:9999999999'
        ]);
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $response = $this->processNotification($notify);
        if ($option == 'show') {
            $response['replaceWith']['#showSupplier'] = view('supplier.profile', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
