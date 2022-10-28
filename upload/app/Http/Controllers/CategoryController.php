<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class CategoryController extends Controller
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
        $expense_categories =Category::latest()->pluck('name', 'id');
        return view('item.category')->with('categories', $expense_categories);
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
        Category::create($input);
        $data['categories'] = Category::latest()->pluck('name', 'id');
        $data['page'] = $request->page;
        return $this->sendCommonResponse($data, __('Category added successfully!'), 'add');
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
        $data['index_route'] = route('category.show', $id);

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
            if($data['page']=='category') {
                $replaceId = "#category-btns";
            } else {
                $replaceId = "#addCategoryBtn";
            }
            $response['replaceWith'][$replaceId] = view('item.add_category_btn', $data)->render();
            $response['replaceWith']['#expenseCategoryForm'] = view('item.category_form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#expenseTable'] = view('expense.partials.category_table', $data)->render();
        }
        return $this->sendResponse($response);
    }

    public function create()
    {
        $expense_categories = Category::pluck('name', 'id');
        return view('item.edit', compact('categories'));
    }

    private function sendCommonResponsecategory($data=[], $notify = '', $option = null) 
    {
        $expenseObj = new Expense();
        $data['page'] = 'expense';
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $response['replaceWith']['#addExpense'] = view('item.form', $data)->render();
        } else if ($option == 'edit' || $option == 'update') {
            $response['replaceWith']['#editExpense'] = view('item.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showSupplier'] = view('supplier.profile', $data)->render();
        } 
        if ( $option == 'index' || $option == 'add' || $option == 'update' || $option == 'delete') {
            if(empty($data['expenses'])) {
                $data['expenses'] = $expenseObj->getAll(null, ['paginate'=>10]);
            }
            $data['index_route'] = route('expense.index');
            $response['replaceWith']['#expenseTable'] = view('expense.partials.expense_table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
