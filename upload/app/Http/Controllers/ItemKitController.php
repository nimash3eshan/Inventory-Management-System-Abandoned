<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItemKit, App\ItemKitItem, App\ItemKitItemTemp;
use App\Item;
use App\Http\Requests;
use App\Http\Requests\ItemKitRequest;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ItemKitController extends Controller
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
        $itemkits = Item::where('type', 2)->get();
        return view('itemkit.index')->with('itemkits', $itemkits);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('itemkit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $ItemKitItemTemps = new ItemKitItemTemp;
        $ItemKitItemTemps->item_id = $request->item_id;
        $ItemKitItemTemps->quantity = 1;
        $ItemKitItemTemps->cost_price = $request->cost_price;
        $ItemKitItemTemps->selling_price = $request->selling_price;
        $ItemKitItemTemps->total_cost_price = $request->cost_price;
        $ItemKitItemTemps->total_selling_price = $request->selling_price;
        $ItemKitItemTemps->save();
        return response($ItemKitItemTemps);
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
        $ItemKitItemTemps = ItemKitItemTemp::find($id);
        $ItemKitItemTemps->quantity = Input::get('quantity');
        $ItemKitItemTemps->save();
        return $ItemKitItemTemps;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        ItemKitItemTemp::destroy($id);
    }

    public function itemKitApi()
    {
        $items = ItemKitItemTemp::with('item')->get();
        if ($items->count() == 0) {
            $items = new Collection();
        }
        return Response::json($items);
    }
    public function itemKits()
    {
        return Response::json(Item::where('type', 1)->get());
    }
    public function storeItemKits(Request $request)
    {
            $itemkits = new Item;
            $itemkits->item_name = $request->item_kit_name;
            $itemkits->cost_price = $request->cost_price;
            $itemkits->selling_price = $request->selling_price;
            $itemkits->description = $request->description;
            $itemkits->type = 2;
            $itemkits->save();
            // process receiving items
            $item_kit_items = ItemKitItemTemp::all();
            foreach ($item_kit_items as $value) {
                $item_kit_items_data = new ItemKitItem;
                $item_kit_items_data->item_kit_id = $itemkits->id;
                $item_kit_items_data->item_id = $value->item_id;
                $item_kit_items_data->cost_price = $value->cost_price;
                $item_kit_items_data->selling_price = $value->selling_price;
                $item_kit_items_data->quantity = $value->quantity;
                $item_kit_items_data->total_cost_price = $value->quantity * $value->cost_price;
                $item_kit_items_data->total_selling_price = $value->quantity * $value->selling_price;
                $item_kit_items_data->save();
            }
            //delete all data on ReceivingTemp model
            ItemKitItemTemp::truncate();
            Session::flash('message', 'You have successfully added Item Kits');
            return Redirect::to('item-kits/create');
    }
}
