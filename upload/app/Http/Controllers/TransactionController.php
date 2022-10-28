<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactionObj = new Transaction();
        $data['transactions'] = $transactionObj->getAll('paginate');
        $data['accounts'] = Account::pluck('company', 'id');
        if ($request->ajax()) {
            return $this->sendCommonResponse($data, null, 'index');
        }
        return view('account.transaction.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::pluck('company', 'id');
        return view('account.transaction.edit', compact('accounts'));
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
        $transaction = Transaction::create($input);
        $transaction->updateAccountBalance('create');
        $data['transaction'] = [];
        $data['accounts'] = Account::pluck('company', 'id');
        return $this->sendCommonResponse($data, __('Transaction created successfully!'), 'add');
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
        $data['accounts'] = Account::pluck('company', 'id');
        $data['transaction'] = Transaction::findOrFail($id);
        return $this->sendCommonResponse($data, null, 'edit');
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
        return $this->sendCommonResponse([], ['danger' => __('Transaction Can not be edited!')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->updateAccountBalance('delete');
        $transaction->delete();
        return $this->sendCommonResponse([], __('You have successfully deleted Transaction'), 'delete');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'transaction_type' => 'required',
            'account_id' => 'required',
            'transaction_with' => 'required',
            'amount' => 'required'
        ]);
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $transactionObj = new Transaction();
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $response['replaceWith']['#addTransaction'] = view('account.transaction.form', $data)->render();
        } else if ($option == 'edit' || $option == 'update') {
            $response['replaceWith']['#editTransaction'] = view('account.transaction.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showTransaction'] = view('account.transaction.profile', $data)->render();
        }
        if ($option == 'index' || $option == 'add' || $option == 'update' || $option == 'delete') {
            $data['transactions'] = $transactionObj->getAll('paginate');
            $response['replaceWith']['#transactionTable'] = view('account.transaction.table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
