<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Item;
use App\ItemAttribute;
use App\Receiving;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
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
    public function index()
    {
        //
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
        $data['item'] = Item::with('inventory', 'inventory.user')->find($id);
        $data['attributes'] = ItemAttribute::where('item_id', $id)->get();
        $data['attributep'] = ItemAttribute::where('item_id', $id)->pluck('name', 'id');
        return $this->commonResponse($data);
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
        if (!empty($input['attribute_id'])) {
            $attribute = ItemAttribute::find($input['attribute_id']);
            $attribute->quantity = $attribute->quantity + $input['in_out_qty'];
            $attribute->save();
        } else {

            $item->quantity = $item->quantity + $input['in_out_qty'];
            $item->save();
        }
        $inventories = new Inventory;
        $inventories->item_id = $id;
        $inventories->user_id = Auth::user()->id;
        $inventories->in_out_qty = $input['in_out_qty'];
        $inventories->remarks = $input['remarks'];
        $inventories->save();
        $data['item'] = $item;
        $data['attributes'] = ItemAttribute::where('item_id', $id)->get();
        $data['attributep'] = ItemAttribute::where('item_id', $id)->pluck('name', 'id');
        return $this->commonResponse($data, __('You have successfully updated item'));
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

    public function validator(array $data)
    {
        return Validator::make($data, [
            "in_out_qty" => "required|numeric|max:999999999",
            'remarks' => 'required|max:255'
        ]);
    }

    private function commonResponse($data = [], $notify = null)
    {
        $response = $this->processNotification($notify);
        $sal_inv = [];
        $rec_inv = [];
        $other_inv = [];
        foreach ($data['item']->inventory as $inventory) {
            $first_four = substr($inventory->remarks, 0, 4);
            if (strtoupper($first_four) == Sale::INVOICE_PREFIX) {
                $sal_inv[] = $inventory;
            } elseif (strtoupper($first_four) == Receiving::INVOICE_PREFIX) {
                $rec_inv[] = $inventory;
            } else {
                $other_inv[] = $inventory;
            }
        }
        $inventories = Inventory::all();
        $data['inventories'] = $inventories;
        $data['sal_inv'] = $sal_inv;
        $data['rec_inv'] = $rec_inv;
        $data['other_inv'] = $other_inv;
        $response['replaceWith']['#inventory'] = view('item.inventory', $data)->render();

        return $this->sendResponse($response);
    }
}
