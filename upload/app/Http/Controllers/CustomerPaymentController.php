<?php

namespace App\Http\Controllers;

use App\Account;
use App\Customer;
use App\CustomerPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerPaymentController extends Controller
{
    public function __construct(Customer $customer, Account $account)
    {
        $this->customer = $customer;
        $this->account = $account;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        CustomerPayment::create($input);

        $customer_id = $request->customer_id;
        $this->customer->updateCustomerBalance($customer_id, -$request->payment, $request->payment);
        $customer = $this->customer->findOrFail($customer_id);
        // Update Account Balance
        $this->account->updateBalance($request->account_id, $request->payment);

        $data = $customer->processShowProfile($customer->id);
        $data['customer'] = $customer;

        return $this->sendCommonResponse($data, __('Customer Payment added successfully for ' . $customer->name . '!'), 'show');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'payment_type' => 'required|max:150',
            'customer_id' => 'required|integer|exists:customers,id',
            'payment' => 'required|max:9999999999|numeric'
        ]);
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $response = $this->processNotification($notify);
        if ($option == 'show') {
            $response['replaceWith']['#showCustomer'] = view('customer.profile', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
