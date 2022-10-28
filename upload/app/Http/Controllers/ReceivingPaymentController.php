<?php

namespace App\Http\Controllers;

use App\PaymentType;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Receiving;
use App\ReceivingPayment;
use Illuminate\Support\Facades\Auth;
use App\Supplier;
use App\Account;

class ReceivingPaymentController extends Controller
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
        $this->validator($request->all())->validate();
        $payment = new ReceivingPayment;
        $payment->payment = $request->payment;
        $payment->receiving_id = $request->receiving_id;
        $payment->payment_type = $request->payment_type;
        //updating sales
        $receiving = Receiving::findOrFail($request->receiving_id);
        $receiving->payment = $receiving->payment + $request->payment;
        $dues = $receiving->dues = $receiving->dues - $request->payment;
        if ($dues <= 0) {
            $receiving->status = Receiving::PAID;
        }
        $receiving->update();
        $payment->user_id = Auth::user()->id;
        $payment->dues = $dues;
        $payment->save();

        //updating customer balance
        $supplier = Supplier::where('id', $receiving->supplier_id)->first();
        $supplier->prev_balance = $supplier->prev_balance - $request->payment;
        $supplier->update();

        // Update Account Balance
        $this->account->updateBalance($request->account_id, null, $request->payment);

        $data['type'] = 'all';
        if ($request->action_page == 'receiving_list') {
            $data['receivingreport'] = (new Receiving())->getReceivings([], 'paginate');
        } else {
            $data = $supplier->processShowProfile($supplier->id);
            $data['type'] = 'supplier';
            $data['supplier'] = $supplier;
        }
        $data['payment_types'] = (new PaymentType())->getAll('select');
        return $this->sendCommonResponse($data, __('You have successfully added Payments for bill/receivings ID#' . $request->receiving_id), 'receiving-payment');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'payment_type' => 'required|max:150',
            'payment' => 'required|numeric|max:9999999999'
        ]);
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $response = $this->processNotification($notify);
        if ($option == 'receiving-payment') {
            if ($data['type'] == 'supplier') {
                $response['replaceWith']['#showSupplier'] = view('supplier.profile', $data)->render();
            } else {
                $response['replaceWith']['#receivingTable'] = view('supplier.partials.receiving_table', $data)->render();
            }
        }
        return $this->sendResponse($response);
    }
}
