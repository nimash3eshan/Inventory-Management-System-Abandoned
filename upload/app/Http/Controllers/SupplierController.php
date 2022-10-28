<?php

namespace App\Http\Controllers;

use App\Exports\SuppliersExport;
use App\Imports\SuppliersImport;
use App\Supplier;
use Illuminate\Http\Request;
use \Auth;
use App\SupplierPayment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;

class SupplierController extends Controller
{
    public function __construct(Excel $excel)
    {
        $this->middleware('auth');
        $this->excel = $excel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $supplierObj = new Supplier();
        if ($request->ajax()) {
            $search = [];
            if (!empty($request->filter)) {
                $search = $request->filter;
                Session::put('supplier_filter', $search);
            } else if (Session::get('supplier_filter')) {
                $search = Session::get('supplier_filter');
            }
            $data['suppliers'] = $supplierObj->getAll('paginate', null, $search);
            return $this->sendCommonResponse($data, null, 'index');
        }
        $data['suppliers'] = $supplierObj->getAll('paginate');
        return view('supplier.index',  $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('supplier.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['prev_balance'] = !empty($input['prev_balance']) ? $input['prev_balance'] : 0;
        $this->validator($input)->validate();
        $suppliers = new Supplier;
        // process avatar
        $image = $request->file('avatar');
        if (isset($image)) {
            $input['avatar'] = $this->uploadSupplierImage($image);
        }
        $suppliers->saveSupplier($input);

        if (!empty($input['payment'])) {
            $payment = new SupplierPayment;
            $payment->payment = $request->payment;
            $payment->supplier_id = $suppliers->id;
            $payment->user_id = Auth::user()->id;
            $payment->save();
        }
        if (!empty($input['page']) && $input['page'] == 'receiving') {
            $data['selected_supplier'] = $suppliers->id;
            return $this->sendCommonResponse($data, __('You have successfully added supplier!'), 'receiving-add');
        }
        return $this->sendCommonResponse($data = [], __('You have successfully added supplier!'), 'add');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $data = (new Supplier())->processShowProfile($id);
        $data['supplier'] = (new Supplier())->getById($id);
        return $this->sendCommonResponse($data, null, 'show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $suppliers = Supplier::find($id);
        $data['supplier'] = $suppliers;
        return $this->sendCommonResponse($data, null, 'edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $this->validator($input)->validate();
        $suppliers = Supplier::find($id);
        // process avatar
        $image = $request->file('avatar');
        if (!empty($image)) {
            if (Storage::exists($suppliers->avatar) && $suppliers->avatar != 'no-foto.png') {
                Storage::delete($suppliers->avatar);
            }
            $input['avatar'] = $this->uploadSupplierImage($image);
        } else {
            $input['avatar'] = $suppliers->avatar;
        }
        $suppliers->saveSupplier($input); // Update Supplier
        $data['supplier'] = $suppliers;
        return $this->sendCommonResponse($data, __('You have successfully updated supplier'), 'update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $suppliers = Supplier::find($id);
            $suppliers->delete();
            return $this->sendCommonResponse([], __('You have successfully deleted supplier'), 'delete');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendCommonResponse([], ['danger' => __('Integrity constraint violation: You Cannot delete a parent row!')], null);
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'avatar' => 'mimes:jpeg,bmp,png|max:5120kb',
            'name' => 'required|max:100',
            'company_name' => 'required|max:50',
            'email' => 'max:100',
            'address' => 'max:185',
            'city' => 'max:100',
            'state' => 'max:100',
            'account' => 'max:20',
            'zip' => 'max:10',
            'phone_number' => 'max:20',
            'prev_balance' => 'max:9999999999|numeric',
            'payment' => 'max:9999999999|numeric|nullable'
        ]);
    }

    protected function uploadSupplierImage($image)
    {
        return $this->uploadImage($image, 'images/suppliers');
    }
    public function export()
    {
        return $this->excel->download(new SuppliersExport, 'suppliers_' . time() . '.xlsx');
    }

    public function import()
    {
        $this->excel->import(new SuppliersImport(), request()->file('import_file'));
        return $this->sendCommonResponse([], __('Suppliers imported successfully.'), 'index');
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $supplierObj = new Supplier();
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $response['replaceWith']['#addSupplier'] = view('supplier.form', ['supplier' => ''])->render();
        } else if ($option == 'receiving-add') {
            $data['suppliers'] = $supplierObj->getAll('select');
            $response['replaceWith']['#addSupplier'] = view('supplier.form', ['supplier' => ''])->render();
            $response['replaceWith']['#supplier_dropdown'] = view('receiving.supplier_dropdown', $data)->render();
        } else if ($option == 'edit' || $option == 'update') {
            $response['replaceWith']['#editSupplier'] = view('supplier.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showSupplier'] = view('supplier.profile', $data)->render();
        }
        if (in_array($option, ['index', 'add', 'update', 'delete'])) {
            if (empty($data['suppliers'])) {
                $data['suppliers'] = $supplierObj->getAll('paginate');
            }
            $response['replaceWith']['#supplierTable'] = view('supplier.table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
