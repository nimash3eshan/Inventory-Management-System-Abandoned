<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Http\Controllers\Controller;
use App\Category;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Imports\ItemsImport;
use App\Item;
use App\Inventory;
use App\ItemAttribute;
use App\ReceivingTemp;
use App\SaleTemp;
use \Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

class ItemController extends Controller
{
    use FileUploadTrait;
    protected $excel;

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
        $data['categories'] = Category::pluck('name', 'id');
        if ($request->ajax()) {
            $search = [];
            if (!empty($request->filter)) {
                $search = $request->filter;
                Session::put('item_filter', $search);
            } else if (Session::get('item_filter')) {
                $search = Session::get('item_filter');
            }
            $data['items'] = (new Item())->getAll('paginate', $search);
            return $this->sendCommonResponse($data, null, 'index');
        }
        $data['items'] = (new Item())->getAll('paginate');
        return view('item.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('item.edit');
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
        $items = new Item;
        $attributes = new ItemAttribute;
        if (!empty($request->file('avatar'))) {
            $avatarName = $this->uploadImage($request->file('avatar'), "images/items");
            $input['avatar'] = $avatarName;
        }
        $items->saveItem($input);
        // process inventory
        if (!empty($input['quantity'])) {
            $inventories = new Inventory;
            $input['item_id'] = $items->id;
            $input['attribute_id'] = $attributes->attribute_id;
            $input['user_id'] = Auth::user()->id;
            $input['remarks'] = "Manual Edit of Quantity";
            $inventories->saveInventory($input);
        }
        return $this->sendCommonResponse([], 'You have successfully added item', 'add');
    }

    public function customCreate(Request $request)
    {
        $input = $request->all();
        $item = new Item();
        if (!empty($request->type) && $request->type == 'receiving') {
            $input['quantity'] = 0;
        }
        $item->saveItem($input);
        $input['item_id'] = $item->id;
        if (!empty($request->type) && $request->type == 'receiving') {
            $input['quantity'] = $request->quantity;
            $input['category_id'] = $request->category_id;
            $ReceivingTemps = new ReceivingTemp();
            $ReceivingTemps->saveRecivingTemp($input);
            return $ReceivingTemps;
        } else {
            $SaleTemps = new SaleTemp();
            $SaleTemps->saveSaleTemp($input);
            return $SaleTemps;
        }
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
        $data['item'] = Item::find($id);
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
        $item = Item::find($id);
        // process avatar
        if (!empty($request->file('avatar'))) {
            if (Storage::exists($item->avatar) && $item->avatar != 'no-foto.png') {
                Storage::delete($item->avatar);
            }
            $input['avatar'] = $this->uploadImage($request->file('avatar'), "images/items");
        } else {
            $input['avatar'] = $item->avatar;
        }
        $item->saveItem($input);

        // process inventory
        $inventories = new Inventory;
        $input['item_id'] = $id;
        $input['user_id'] = Auth::user()->id;
        $input['quantity'] = $input['quantity'] - $item->quantity;
        $input['remarks'] = "Manual Edit of Quantity";
        $inventories->saveInventory($input);
        $data['item'] = $item;
        return $this->sendCommonResponse($data, __('You have successfully updated item'), 'update');
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
            $item = Item::find($id);
            if (count($item->saleitem()->get()) > 0 || count($item->receivingitem()->get()) > 0) {
                return $this->sendCommonResponse([], ['danger' => __('Item can"t be deleted. Some sales or Receivings found with this Item. Please delete them first.')]);
            }
            $item->delete();
            return $this->sendCommonResponse([], __('You have successfully deleted item'), 'delete');
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->sendCommonResponse([], ['danger' => __('Integrity constraint violation: You Cannot delete a parent row')]);
        }
    }

    public function export(Excel $excel)
    {
        return $excel->download(new ItemsExport, 'items_' . time() . '.xlsx');
    }

    public function import()
    {
        $this->excel->import(new ItemsImport(), request()->file('import_file'));
        return $this->sendCommonResponse([], __('Item imported successfully.'), 'import');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'avatar' => 'mimes:jpeg,bmp,png|max:5120kb',
            'upc_ean_isbn' => 'required|max:90',
            'size' => 'max:20',
            'item_name' => 'required|max:90',
            'cost_price' => 'required|numeric|max:9999999',
            'selling_price' => 'required|numeric|max:9999999',
        ]);
    }

    private function sendCommonResponse($data = [], $notify = '', $option = null)
    {
        $itemObj = new Item();
        $response = $this->processNotification($notify);
        if ($option == 'add') {
            $data['categories'] = Category::pluck('name', 'id');
            $data['item'] = '';
            $response['replaceWith']['#addItem'] = view('item.form', $data)->render();
        } else if ($option == 'edit' || $option == 'update') {
            $data['categories'] = Category::pluck('name', 'id');
            $response['replaceWith']['#editItem'] = view('item.form', $data)->render();
        } else if ($option == 'show') {
            $response['replaceWith']['#showCustomer'] = view('customer.profile', $data)->render();
        }
        if ($option == 'index' || $option == 'add' || $option == 'update' || $option == 'delete' || $option == 'import') {
            $data['categories'] = Category::pluck('name', 'id');
            if (empty($data['items'])) {
                $data['items'] = $itemObj->getAll('paginate');
            }
            $response['replaceWith']['#itemTable'] = view('item.table', $data)->render();
        }

        return $this->sendResponse($response);
    }
}
