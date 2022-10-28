<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class ExpenseCategoryController extends Controller
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

    public function index()
    {
        $expense_categories = ExpenseCategory::latest()->pluck('name', 'id');
        return view('expense.expensecategory')->with('expense_categories', $expense_categories);
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
        $input['slug'] = Str::slug($request->name);
        ExpenseCategory::create($input);
        $data['expense_categories'] = ExpenseCategory::latest()->pluck('name', 'id');
        $data['page'] = $request->page;
        return $this->sendCommonResponse($data, __('Expense Category added successfully!'), 'add');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $expenseObj = new Expense();
        $data['expenses'] = $expenseObj->getAll(['expense_category_id'=>$id], ['paginate'=>10]);
        $data['index_route'] = route('expensecategory.show', $id);

        return $this->sendCommonResponse($data, null, 'show');
    }

    protected function validator(Array $data)
    {
        return Validator::make($data, [
            'name'=>'required|max:150'
        ]);
    }

    private function sendCommonResponse($data=[], $notify = '', $option = null) 
    {
        $expenseObj = new Expense();
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            if($data['page']=='expense-category') {
                $replaceId = "#category-btns";
            } else {
                $replaceId = "#addCategoryBtn";
            }
            $response['replaceWith'][$replaceId] = view('expense.add_category_btn', $data)->render();
            $response['replaceWith']['#expenseCategoryForm'] = view('expense.category_form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#expenseTable'] = view('expense.partials.expense_table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
