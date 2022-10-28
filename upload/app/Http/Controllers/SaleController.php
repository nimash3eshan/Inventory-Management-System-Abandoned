<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Category;
use App\Http\Controllers\Traits\CommonTrait;
use App\Item;
use App\PaymentType;
use App\Sale;
use App\SaleItem;
use App\SaleTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    use CommonTrait;

    public function __construct(PaymentType $paymentType, Customer $customer, Category $category, Sale $sale, Item $item, SaleItem $saleItem)
    {
        $this->middleware('auth');
        $this->paymentType = $paymentType;
        $this->customer = $customer;
        $this->category = $category;
        $this->sale = $sale;
        $this->item = $item;
        $this->saleItem = $saleItem;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $filter = [];
        if ($request->ajax()) {
            $search = [];
            if (!empty($request->filter)) {
                $search = $request->filter;
                Session::put('sale_filter', $search);
            } else if (Session::get('sale_filter')) {
                $search = Session::get('sale_filter');
            }
            $data['sales'] = $this->sale->getSales($filter, 'paginate', $search);
            $data['salereport'] = $data['sales'];
            $data['type'] = 'all';
            return $this->sendCommonResponse($data, null, 'index');
        } else {
            $data['sales'] = $this->sale->getSales($filter, 'paginate');
        }
        $data['customers'] = $this->customer->getAll('select');
        return view('sale.list', $data);
    }

    public function create()
    {
        $data['invoice'] = $this->getInvoiceNo(['name' => 'SALE']);
        $data['customers'] = $this->customer->getAll();
        $data['categories'] = $this->category->all();
        $data['action'] = 'add';
        $data['formurl'] = url('sales');
        return view('sale.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $sale = new Sale();
        if ($request->ajax()) {
            if ($request->action == 'hold-sale') {
                $validation = $this->validator($input)->validate();
                $input['user_id'] = Auth::user()->id;
                $saleItems = SaleTemp::all();
                $sale->processSale($input, $saleItems, $hold = true);
                $data['redirect'] = url('sales/create');
                session()->push('notify', [
                    'options' => ['message' => __('Sale Holded Successfully!')],
                    'settings' => ['type' => 'success']
                ]);
                return $this->sendCommonResponse($data, null, 'hold');
            } else {
                $this->validator($input)->validate();
                return $this->processSale($input, $sale);
            }
        }
        $this->validator($input)->validate();
        return $this->processSale($input, $sale);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $sale = $this->sale->getById($id);
        $sale->status = 0;
        $sale->update();
        Session::flash('message', 'Sale Open Successfully');
        return redirect()->back();
    }

    public function showInvoice($id)
    {
        $sale = $this->sale->getById($id);
        $itemssale = (new SaleItem())->getAllBySaleId($id);
        return view('sale.complete')->with('sales', $sale)->with('saleItems', $itemssale);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->status = 1;
        $sale->update();
        Session::flash('message', 'Sale Close Successfully');
        return redirect()->back();
    }

    public function editSale(Request $request, $id)
    {
        $sale = $this->sale->getById($id);
        $data['action'] = 'edit';
        if ($request->isMethod('POST')) {
            $input = $request->all();
            $this->validator($input)->validate();
            $sale->resetSale();
            return $this->processSale($input, $sale);
        }
        $sale_item_obj = new SaleItem();
        $saleItems = $sale_item_obj->getAllBySaleId($sale->id);
        SaleTemp::truncate();
        foreach ($saleItems as $item) {
            $saleTemp = new SaleTemp();
            $saleTemp->saveSaleTemp($item);
        }

        $data['customers'] = $this->customer->getAll();
        $data['selected_customer'] = $this->customer->getById($sale->customer_id)->name;
        $data['sale'] = $sale;
        $data['formurl'] = url('/sales/edit/' . $sale->id);
        $data['categories'] = $this->category->all();
        return view('sale.index', $data);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'customer_id' => 'required',
            'payment_type' => 'required',
            'subtotal' => 'required',
            'payment' => 'required',
            'invoice_no' => 'required',
            'account_id' => 'required'
        ]);
    }

    private function processSale($input, $salesObj, $refund = false)
    {
        $saleItems = SaleTemp::all();
        if (empty($saleItems->toArray())  && $refund == false) {
            return $this->sendCommonResponse([], ['danger' => __('Please Add some Items to create sale!')]);
        }
        if ($this->item->checkItemStock($saleItems)) {
            return $this->sendCommonResponse([], ['danger' => __('Some of your selected Item/Items quantity are out of stock!')]);
        }
        $input['user_id'] = Auth::user()->id;
        if ($refund) {
            $sale = $salesObj->processSale($input, $saleItems, $hold = false, $refund = true);
            $data['redirect'] = url('sales');
            return $this->sendCommonResponse($data, __('Sale refunded successfully!'));
        } else {
            $data['sales'] = $salesObj->processSale($input, $saleItems);
            $data['saleItems'] = SaleItem::where('sale_id', $data['sales']->id)->get();
            return $this->sendCommonResponse($data, __('You have successfully added sales'), 'add');
        }
    }

    public function refund(Request $request, $id)
    {
        $saleObj = new Sale();
        $sale = $saleObj->getById($id);
        $data['action'] = 'refund';
        if ($request->isMethod('POST')) {
            $input = $request->all();
            $this->validator($input)->validate();
            $sale->resetSale('refund');
            return $this->processSale($input, $sale, $refund = true);
        }
        $sale_item_obj = new SaleItem();
        $saleItems = $sale_item_obj->getAllBySaleId($sale->id);
        SaleTemp::truncate();
        foreach ($saleItems as $item) {
            $saleTemp = new SaleTemp();
            $saleTemp->saveSaleTemp($item);
        }
        $data['customers'] = $this->customer->getAll();
        $data['sale'] = $sale;
        $data['formurl'] = '/sales/refund/' . $sale->id;
        $data['categories'] = $this->category->all();
        $data['selected_customer'] = $this->customer->getById($sale->customer_id)->name;
        return view('sale.index', $data);
    }

    public function mailInvoice(Sale $sale)
    {
        if (empty($sale->customer->email)) {
            return $this->sendCommonResponse([], ['warning' => __('Customer email not found to email this invoice. Please add email for this customer and click this button!')]);
        }
        $data['sales'] = $sale;
        $data['saleItems'] = SaleItem::where('sale_id', $sale->id)->get();
        $this->sendMail($sale->customer->email, 'emails.sale_invoice', $data);
        return $this->sendCommonResponse([], __('Invoice successfully sent to current customer email!'));
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $response = $this->processNotification($notify);
        if (!empty($data['redirect'])) {
            $response['redirect'] = $data['redirect'];
        }

        if ($option == 'add') {
            $response['replaceWith']['#saleContent'] = view('sale.print_invoice', $data)->render();
        }
        if ($option == 'index') {
            $response['replaceWith']['#saleTable'] = view('customer.partials.sale_table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
