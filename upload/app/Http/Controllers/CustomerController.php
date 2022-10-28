<?php 
namespace App\Http\Controllers;

use App\Customer;
use App\CustomerPayment;
use App\Exports\CustomersExport;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Imports\CustomersImport;
use Illuminate\Http\Request;
use \Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;

class CustomerController extends Controller
{
    use FileUploadTrait;

    public function __construct(Excel $excel, Customer $customer)
    {
        $this->middleware('auth');
        $this->excel = $excel;
        $this->customer = $customer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search = [];
            if(!empty($request->filter)) {
                $search = $request->filter;
                Session::put('customer_filter', $search);
            } else if( Session::get('customer_filter')) {
                $search = Session::get('customer_filter');
            }
            $data['customers'] = $this->customer->getAll('paginate', $search);
            return $this->sendCommonResponse($data, null, 'index');
        }
        $data['customers'] = $this->customer->getAll('paginate');
        return view('customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('customer.edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();
        $customers = new Customer;
        if (!empty($request->file('avatar'))) {
            $avatar_name = $this->uploadImage($request->file('avatar'), 'images/customers');
             $input['avatar'] = $avatar_name;
        }
        $customers->saveCustomer($input);
        if (!empty($input['payment'])) { // processing Payment
            $payment = new CustomerPayment;
            $payment->payment = $input['payment'];
            $payment->customer_id = $customers->id;
            $payment->user_id = Auth::user()->id;
            $payment->save();
        }
        // process avatar
        
        if(!empty($input['page']) && $input['page'] == 'sale') {
            $data['selected_customer'] = $customers->name;
            return $this->sendCommonResponse($data, 'You have successfully added customer', 'sale-add');
        }
        return $this->sendCommonResponse($data=[], 'You have successfully added customer', 'add');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        $data = $this->customer->processShowProfile($id);
        $data['customer'] = $this->customer->getById($id);
        
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
        $data['customer'] = Customer::find($id);
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

        $customers = (new Customer())->getById($id);
        
        // process avatar
        if (!empty($request->file('avatar'))) {
            if (Storage::exists($customers->avatar) && $customers->avatar != 'no-foto.png') {
                Storage::delete($customers->avatar);
            }
            $input['avatar'] = $this->uploadImage($request->file('avatar'), 'images/customers');
        }else{
            $input['avatar']=$customers->avatar;
        }
        $customers->saveCustomer($input);
        $data['customer'] = $customers;
        return $this->sendCommonResponse($data, 'You have successfully updated customer', 'update');
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
            $customer = Customer::find($id);
            $customer->delete();
            return $this->sendCommonResponse([], 'You have successfully deleted customer', 'delete');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendCommonResponse([], ['danger'=>__('Integrity constraint violation: You Cannot delete a parent row')]);
        }
    }

    public function export()
    {
        return $this->excel->download(new CustomersExport, 'customers_'.time().'.xlsx');
    }

    public function import()
    {
        $this->excel->import(new CustomersImport(), request()->file('import_file'));
        return $this->sendCommonResponse([], __('Customers imported successfully.'), 'import');
    }

    protected function validator(Array $data)
    {
        return Validator::make($data, [
            'avatar'=>'mimes:jpeg,bmp,png|max:5120kb',
            'name'=>'required|max:185',
            'email'=>'max:100',
            'address'=>'max:185',
            'city'=>'max:185',
            'state'=>'max:185',
            'zip'=>'max:10',
            'phone_number'=>'max:20',
            'prev_balance'=>'max:999999|numeric',
            'payment'=>'max:999999|numeric|nullable'
        ]);
    }

    private function sendCommonResponse($data=[], $notify = '', $option = null) 
    {
        $customerObj = new Customer();
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $response['replaceWith']['#addCustomer'] = view('customer.form', ['customer'=>''])->render();
        } else if ($option == 'sale-add') {
            $data['customers'] = $customerObj->getAll();
            $response['replaceWith']['#customer_dropdown'] = view('sale.customer_dropdown', $data)->render();
            $response['replaceWith']['#addCustomer'] = view('customer.form', ['customer'=>''])->render();
        } else if ($option == 'edit' || $option == 'update') {
            $response['replaceWith']['#editCustomer'] = view('customer.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showCustomer'] = view('customer.profile', $data)->render();
        }
        if ( in_array($option, ['index', 'add', 'update', 'delete', 'import'])) {
            if (empty($data['customers'])) {
                $data['customers'] = $customerObj->getAll('paginate');
            }
            $response['replaceWith']['#customerTable'] = view('customer.table', $data)->render();
        }
        return $this->sendResponse($response);
    }
}
