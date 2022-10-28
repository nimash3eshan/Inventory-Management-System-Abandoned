<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CommonTrait;
use App\PaymentType;
use App\Receiving;
use App\ReceivingItem;
use App\ReceivingTemp;
use App\Supplier;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use \Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ReceivingController extends Controller
{
    use CommonTrait;
    public function __construct(PaymentType $paymentType, Receiving $receiving, Category $category, Supplier $supplier)
    {
        $this->middleware('auth');
        $this->paymentType = $paymentType;
        $this->receiving = $receiving;
        $this->category = $category;
        $this->supplier = $supplier;
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
                Session::put('receiving_filter', $search);
            } else if (Session::get('receiving_filter')) {
                $search = Session::get('receiving_filter');
            }
            $data['receivings'] = $this->receiving->getReceivings($filter, 'paginate', $search);
            $data['receivingreport'] = $data['receivings'];
            $data['type'] = 'all';
            return $this->sendCommonResponse($data, null, 'index');
        } else {
            $data['receivings'] = $this->receiving->getReceivings($filter, 'paginate');
        }
        $data['suppliers'] = $this->supplier->getAll('select');
        return view('receiving.list', $data);
    }

    public function create()
    {
        $data['invoice'] = $this->getInvoiceNo(['name' => 'REC']);
        $data['suppliers'] = (new Supplier())->getAll('select', ['company_name', 'id']);
        $data['action'] = 'add';
        $data['categories'] = $this->category->all();
        return view('receiving.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $sale = new Receiving();
        $this->validator($input)->validate();
        return $this->processReceiving($input, $sale);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $receiving = Receiving::findOrFail($id);
        $receiving->status = 0;
        $receiving->update();
        Session::flash('message', __('Sale Opened Successfully'));
        return redirect()->back();
    }


    public function showInvoice($id)
    {
        $receiving = (new Receiving())->getById($id);
        $itemsreceiving = (new ReceivingItem())->getAllByReceivingId($id);
        return view('receiving.complete')->with('receivings', $receiving)->with('receivingItems', $itemsreceiving);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $receiving = Receiving::findOrFail($id);
        $receiving->status = 1;
        $receiving->update();
        Session::flash('message', __('Sale Closed Successfully'));
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        // process inventory
        $receivingTemps = new ReceivingTemp;
        $receivingTemps->item_id = $id;
        $receivingTemps->quantity = request()->quantity;
        $receivingTemps->save();
        Session::flash('message', __('You have successfully add item'));
        return redirect()->to('receivings');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'supplier_id' => 'required',
            'payment_type' => 'required',
            'total' => 'required',
            'payment' => 'required'
        ]);
    }

    private function processReceiving($input, $receivingObj, $refund = false)
    {
        $receivingItems = ReceivingTemp::all();
        if (empty($receivingItems->toArray())  && $refund == false) {
            return $this->sendCommonResponse([], ['danger' => __('Please Add some Items to create sale!')]);
        }

        $input['user_id'] = Auth::user()->id;
        if ($refund) {
            $receiving = $receivingObj->processReceiving($input, $receivingItems, false, $refund = true);
            $data['redirect'] = url('receivings');
            return $this->sendCommonResponse($data, __('Sale refunded successfully!'));
        } else {
            $data['receivings'] = $receivingObj->processReceiving($input, $receivingItems);
            $data['receivingItems'] = ReceivingItem::where('receiving_id', $data['receivings']->id)->get();
            return $this->sendCommonResponse($data, __('You have successfully added receiving'), 'add');
        }
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $response = $this->processNotification($notify);
        if (!empty($data['redirect'])) {
            $response['redirect'] = $data['redirect'];
        }

        if ($option == 'add') {
            $response['replaceWith']['#receivingContent'] = view('receiving.print_invoice', $data)->render();
        }
        if ($option == 'index') {
            $response['replaceWith']['#receivingTable'] = view('supplier.partials.receiving_table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
