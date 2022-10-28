<?php

namespace App\Http\Controllers;

use App\Account;
use App\Expense;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Expense $expense, Account $account)
    {
        $this->middleware('auth');
        $this->expense = $expense;
        $this->account = $account;
    }

    public function index(Request $request)
    {
        $data['expense_categories'] = ExpenseCategory::pluck('name', 'id');
        $data['index_route'] = route('expense.index');
        if ($request->ajax()) {
            $search = [];
            if (!empty($request->filter)) {
                $search = $request->filter;
                Session::put('expense_filter', $search);
            } else if (Session::get('expense_filter')) {
                $search = Session::get('expense_filter');
            }
            $data['expenses'] = $this->expense->getAll(null, ['paginate' => 10], $search);
            return $this->sendCommonResponse($data, null, 'index');
        }
        $data['expenses'] = $this->expense->getAll(null, ['paginate' => 10]);
        return view('expense.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense_categories = ExpenseCategory::pluck('name', 'id');
        return view('expense.edit', compact('expense_categories'));
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
        $expense = new Expense();
        $input['payment'] = $input['unit_price'];
        $expense->saveExpense($input);
        if ($input['payment'] > 0) {
            $this->account->updateBalance($input['account_id'], null, $input['payment']);
        }
        $data['expense_categories'] = ExpenseCategory::pluck('name', 'id');
        $data['expense'] = [];
        return $this->sendCommonResponse($data, __('Expense Created successfully'), 'add');
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
        $data['expense'] = Expense::findOrFail($id);
        $data['expense_categories'] = ExpenseCategory::pluck('name', 'id');
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
        $expense = Expense::findOrFail($id);
        $prev_amount = $expense->payment;
        $expense->saveExpense($input);
        if ($prev_amount != $input['unit_price']) {
            $amount_diff = $input['unit_price'] - $prev_amount;
            $this->account->updateBalance($input['account_id'], null,  $amount_diff);
        }

        $data['expense'] = $expense;
        $data['expense_categories'] = ExpenseCategory::pluck('name', 'id');
        return $this->sendCommonResponse($data, __('Expense Updated successfully'), 'update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return $this->sendCommonResponse([], __('Expense deleted successfully!'), 'delete');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'expense_category_id' => 'required|integer|exists:expense_categories,id',
            'description' => 'required',
            'unit_price' => 'numeric|max:9999999999',
            'account_id' => 'numeric|max:9999999',
            'payment_type' => 'required|max:50'
        ]);
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $expenseObj = new Expense();
        $data['page'] = 'expense';
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $response['replaceWith']['#addExpense'] = view('expense.form', $data)->render();
        } else if ($option == 'edit' || $option == 'update') {
            $response['replaceWith']['#editExpense'] = view('expense.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showSupplier'] = view('supplier.profile', $data)->render();
        }
        if ($option == 'index' || $option == 'add' || $option == 'update' || $option == 'delete') {
            if (empty($data['expenses'])) {
                $data['expenses'] = $expenseObj->getAll(null, ['paginate' => 10]);
            }
            $data['index_route'] = route('expense.index');
            $response['replaceWith']['#expenseTable'] = view('expense.partials.expense_table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
