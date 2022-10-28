<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
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
        $accountObj = new Account();
        $data['accounts'] = $accountObj->getAll('paginate');
        if ($request->ajax()) {
            return $this->sendCommonResponse($data, null, 'index');
        }
        return view('account.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('account.edit');
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
        Account::create($input);
        return $this->sendCommonResponse([], __('Account added successfully!'), 'add');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $data['account'] = $account;
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
        $input = $request->all();
        $this->validator($input)->validate();
        $account = Account::findOrFail($id);
        $input['user_id'] = Auth::user()->id;
        $account->update($input);
        $data['account'] = $account;
        return $this->sendCommonResponse($data, __('Account updated successfully!'), 'update');
    }

    protected function validator(Array $data)
    {
        return Validator::make($data, [
            'name'=>'required|max:100',
            'company'=>'required|max:100',
            'branch_name'=>'max:100',
            'account_no'=>'max:100',
            'pin'=>'max:10',
            'email'=>'max:100',
            'balance'=>'required|numeric|max:9999999999'
        ]);
    }

    private function sendCommonResponse($data=[], $notify = '', $option = null) 
    {
        $accountObj = new Account();
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $response['replaceWith']['#addAccount'] = view('account.form', ['account'=>''])->render();
        } else if ($option == 'edit' || $option == 'update') {
            $response['replaceWith']['#editAccount'] = view('account.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showAccount'] = view('account.profile', $data)->render();
        } 
        if ( $option == 'index' || $option == 'add' || $option == 'update' || $option == 'delete') {
            $data['accounts'] = $accountObj->getAll('paginate');
            $response['replaceWith']['#accountTable'] = view('account.table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
