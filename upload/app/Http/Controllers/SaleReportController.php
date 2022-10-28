<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Inventory;
use App\Item;
use App\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SaleReportController extends Controller
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
        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];

        if (!empty($request->input('month')) && !empty($request->input('year'))) {
            $salesReport = Sale::where('status', '!=', Sale::HOLD)->whereMonth('updated_at', $request->input('month'))->whereYear('updated_at', $request->input('year'))->get();
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
            $data['saleReport'] = $salesReport;
            $data['formated_report'] = $formated_report;
            $data['units'] = $units;
            $data['months'] = $months;
            $data['input'] = $request->all();
            return $this->commonResponse($data, null, 'sale-report');
        } else if (!empty($request->input('year'))) {
            $salesReport = Sale::where('status', '!=', Sale::HOLD)->whereYear('updated_at', $request->input('year'))->get();
        } else {
            $salesReport = Sale::where('status', '!=', Sale::HOLD)->latest()->get();
        }
        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];
        foreach ($salesReport as $report) {
            $formated_report[$report->updated_at->format('m')][] = $report;
        }
        $data['saleReport'] = $salesReport;
        $data['formated_report'] = $formated_report;
        $data['units'] = $months;
        $data['months'] = $months;
        $data['input'] = $request->all();
        if ($request->ajax()) {
            return $this->commonResponse($data, null, 'sale-report');
        }
        return view('report.sale', $data);
    }

    public function stockReport(Request $request)
    {
        $itemObj = new Item();
        $items = $itemObj->getAll('paginate');
        $item_ids = $items->pluck('id');
        $inventories = (new Inventory())->getAll(['item_ids' => $item_ids], ['get' => 'get']);
        $stock_reports = [];
        foreach ($inventories as $inventory) {
            if (empty($stock_reports[$inventory->item_id]['in'])) {
                $stock_reports[$inventory->item_id]['in'] = 0;
            }
            if (empty($stock_reports[$inventory->item_id]['out'])) {
                $stock_reports[$inventory->item_id]['out'] = 0;
            }
            if ($inventory->in_out_qty < 0) {
                $stock_reports[$inventory->item_id]['out'] += abs($inventory->in_out_qty);
            } else {
                $stock_reports[$inventory->item_id]['in'] += abs($inventory->in_out_qty);
            }
        }
        $data['stock_reports'] = $stock_reports;
        $data['items'] = $items;
        if ($request->ajax()) {
            return $this->commonResponse($data, null, 'stock-report');
        }
        return view('report.stocks.stock_report', $data);
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
        if ($option == 'sale-report') {
            $response['replaceWith']['#list_sale_report'] = view('report.list_sale_report', $data)->render();
        } else if ($option == 'stock-report') {
            $response['replaceWith']['#list_stock_report'] = view('report.stocks.stock_table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
