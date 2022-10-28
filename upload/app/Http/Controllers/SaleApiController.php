<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use App\ItemAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SaleApiController extends Controller
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
        $items = Item::where('quantity', '>=', 1)->latest()->get();
        $formatted_items = [];
      
        foreach($items as $item) {
                $attributes= ItemAttribute::where('item_id',$item->id)->where('status',1)->get();

            if($attributes->count() > 0){
                $item_name = $item->item_name;
                foreach($attributes as $key=>$attribute){
                    $nattribute = new Item();
                    $nattribute->id = $item->id;
                    $nattribute->attribute_id= $attribute->id;
                    $nattribute->cost_price= $attribute->cost_price;
                    $nattribute->selling_price= $attribute->selling_price;
                    $nattribute->quantity= $attribute->quantity;
                    $nattribute->exp_date= $attribute->exp_date;
                    $nattribute->item_name = $item_name."->".$attribute->name;
                    $nattribute->avatar = $attribute->fileUrl('image');
                    $formatted_items[]= $nattribute;
                    $nattribute=null;
                }
            } else{
                $nitem = $item;
                $nitem->avatar = $item->fileUrl();
                $formatted_items[] = $nitem;
            }
          
        }

        return Response::json($formatted_items);
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

}
