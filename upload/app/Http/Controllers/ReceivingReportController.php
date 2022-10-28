<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Receiving;
use App\Expense;
use \Auth, \Redirect;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReceivingReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $units = [];
        $formated_report = [];
        $formated_expense = [];
        $expense_cat = (new ExpenseCategory)->getAll();
        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];

        if (!empty($request->input('month')) && !empty($request->input('year'))) {
            $salesReport = Receiving::where('status', '!=', Receiving::HOLD)->whereMonth('updated_at', $request->input('month'))->whereYear('updated_at', $request->input('year'))->get();
            $date = '01-' . $request->input('month') . '-' . $request->input('year');
            $total_days = Carbon::parse($date)->daysInMonth;

            for ($i = 1; $i <= $total_days; $i++) {
                if ($i < 10) {
                    $i = '0' . $i;
                }
                $units[$i] = $i . ' ' . $months[$request->input('month')];
            }
            foreach ($salesReport as $report) {
                $formated_report[$report->updated_at->format('d')][] = $report;
            }

            $expenses = Expense::whereMonth('updated_at', $request->input('month'))->whereYear('updated_at', $request->input('year'))->get();
            if (!empty($expenses)) {
                foreach ($expenses as $expense) {
                    $formated_expense[$expense->updated_at->format('d')][] = $expense;
                }
            }
            $data['saleReport'] = $salesReport;
            $data['formated_report'] = $formated_report;
            $data['units'] = $units;
            $data['months'] = $months;
            $data['input'] = $request->all();
            $data['formated_expense'] = $formated_expense;
            $data['expense_cat'] = $expense_cat;
            return $this->commonResponse($data, null, 'receiving-report');
        } else if (!empty($request->input('year'))) {
            $salesReport = Receiving::whereYear('updated_at', $request->input('year'))->where('status', '!=', Receiving::HOLD)->get();
            $expenses = Expense::whereYear('updated_at', $request->input('year'))->get();
        } else {
            $salesReport = Receiving::where('status', '!=', Receiving::HOLD)->latest()->get();
            $expenses = Expense::latest()->get();
        }

        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];

        foreach ($salesReport as $report) {
            $formated_report[$report->updated_at->format('m')][] = $report;
        }
        if (!empty($expenses)) {
            foreach ($expenses as $expense) {
                $formated_expense[$expense->updated_at->format('m')][] = $expense;
            }
        }
        $data['saleReport'] = $salesReport;
        $data['formated_report'] = $formated_report;
        $data['units'] = $months;
        $data['months'] = $months;
        $data['input'] = $request->all();
        $data['formated_expense'] = $formated_expense;
        $data['expense_cat'] = $expense_cat;
        if ($request->ajax()) {
            return $this->commonResponse($data, null, 'receiving-report');
        }
        return view('report.receiving', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    private function commonResponse($data = null, $notify = null, $option = null)
    {
        $response = $this->processNotification($notify);
        if ($option == 'receiving-report') {
            $response['replaceWith']['#list_receiving_report'] = view('report.list_receiving_report', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
