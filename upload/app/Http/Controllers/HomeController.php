<?php

namespace App\Http\Controllers;

use App\Item, App\Customer, App\Sale;
use App\Supplier, App\Receiving, App\User;
use App\Account;
use App\Expense;
use Carbon\Carbon;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $itemObj = new Item();
        $aitems = $itemObj->getAll();
        $items = $aitems->count();
        $now_date = Carbon::now();
        $stock_limit_items = $itemObj->where('quantity', '<=', 'stock_limit')->orWhere('expire_date', '<', $now_date)->paginate(10);

        $aexpenses = Expense::with('expense_category')->latest()->get();
        $expenses = $aexpenses->count();
        $expense_pay = $aexpenses->sum('payment');
        $expense_due = $aexpenses->sum('dues');

        $customers = Customer::count();
        $suppliers = Supplier::count();

        $areceivings = Receiving::all();
        $receivings = $areceivings->count();
        $receiving_pay = $areceivings->sum('payment');
        $receiving_dues = $areceivings->sum('dues');

        $total_exp = $expense_pay + $receiving_pay;
        $exp_dues = $expense_due + $receiving_dues;

        $asales = Sale::where('status', '!=', 0)->latest()->get();
        $sales = $asales->count();
        $total_sales = $asales->sum('payment');
        $income_dues = $asales->sum('dues');
        $latest_incomes = $asales;

        $employees = User::count();
        $incomeexpensechart = $this->incomeExpenseChart(15);

        $accounts = Account::latest()->paginate(7);

        return view('home')
            ->with('items', $items)
            ->with('expenses', $expenses)
            ->with('aexpenses', $aexpenses)
            ->with('customers', $customers)
            ->with('suppliers', $suppliers)
            ->with('receivings', $receivings)
            ->with('sales', $sales)
            ->with('employees', $employees)
            ->with('incomeexpensechart', $incomeexpensechart)
            ->with('totalincome', $total_sales)
            ->with('income_dues', $income_dues)
            ->with('exp_dues', $exp_dues)
            ->with('accounts', $accounts)
            ->with('latest_incomes', $latest_incomes)
            ->with('total_exp', $total_exp)
            ->with('stock_limit_items', $stock_limit_items);
    }

    private function getDatesOfWeek($total_days)
    {
        $days = array();
        for ($i = 0; $i >= - ($total_days - 1); $i--) {
            $days[] = date("Y-m-d", strtotime($i . ' days'));
        }
        return $days;
    }

    public function incomeExpenseChart($total_days)
    {
        $daysOfWeek = $this->getDatesOfWeek($total_days);
        $incomes = Sale::whereBetween('created_at', [$daysOfWeek[($total_days - 1)] . ' 00:00:00', $daysOfWeek[0] . ' 23:59:59'])->get();
        $expenses = Receiving::whereBetween('created_at', [$daysOfWeek[($total_days - 1)] . ' 00:00:00', $daysOfWeek[0] . ' 23:59:59'])->get();
        $chartArray = array();
        foreach ($daysOfWeek as $day) {
            $weeklyincome = "0";
            $weeklyexpense = "0";
            $weeklyincome = $incomes->whereBetween('created_at', [$day . ' 00:00:00', $day . ' 23:59:59'])->count();
            $weeklyexpense = $expenses->whereBetween('created_at', [$day . ' 00:00:00', $day . ' 23:59:59'])->count();
            $chart = [
                'y' => $day,
                'a' => $weeklyexpense,
                'b' => $weeklyincome,
            ];
            $chartArray[] =  $chart;
        }
        return $chartArray;
    }
}
