<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\SaleTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SaleTempApiController extends Controller
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
        $saletemps = SaleTemp::with('item', 'attribute')->get();
        $format_saletemps = [];
        foreach ($saletemps as $saletemp) {
            $nsaletemp =  $saletemp;
            if (!empty($saletemp->attribute_id)) {
                $nsaletemp->item_name = $saletemp->item->item_name . '->' . $saletemp->attribute->name;
            } else {
                $nsaletemp->item_name = $saletemp->item->item_name;
            }


            $format_saletemps[] = $nsaletemp;
        }
        return Response::json($saletemps);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('sale.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $SaleTemps = new SaleTemp;
        $SaleTemps->item_id = $request->item_id;
        $SaleTemps->cost_price = $request->cost_price;
        $SaleTemps->selling_price = $request->selling_price;
        $SaleTemps->quantity = !empty($request->quantity) ? $request->quantity : 1;
        $SaleTemps->total_cost = $request->cost_price;
        $SaleTemps->attribute_id = $request->attribute_id;
        $SaleTemps->total_selling = $request->selling_price;
        $SaleTemps->save();
        return $SaleTemps;
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
        $SaleTemps = SaleTemp::find($id);
        $SaleTemps->quantity = request()->quantity;
        $SaleTemps->attribute_id = request()->attribute_id;
        $SaleTemps->total_cost = request()->total_cost;
        $SaleTemps->total_selling = request()->total_selling;
        $SaleTemps->save();
        return $SaleTemps;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        SaleTemp::destroy($id);
    }
}
