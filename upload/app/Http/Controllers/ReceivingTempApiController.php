<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ReceivingTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReceivingTempApiController extends Controller
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
        $receivingtemps = ReceivingTemp::with('item', 'attribute')->get();
        $format_temps = [];
        foreach ($receivingtemps as $receivingtemp) {
            $nreceivingtemp =  $receivingtemp;
            if (!empty($receivingtemp->attribute_id)) {
                $nreceivingtemp->item_name = $receivingtemp->item->item_name . '->' . $receivingtemp->attribute->name;
            } else {
                $nreceivingtemp->item_name = $receivingtemp->item->item_name;
            }
            $format_temps[] = $nreceivingtemp;
        }
        return Response::json($format_temps);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('receiving.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->newItem($input);
    }

    public function updateItem()
    {
        $ReceivingTemps = ReceivingTemp::find(3);
        $ReceivingTemps->quantity = 5;
        $ReceivingTemps->total_cost = 54;
        $ReceivingTemps->save();
        return $ReceivingTemps;
    }

    public function newItem($input)
    {
        $ReceivingTemps = new ReceivingTemp;
        $ReceivingTemps->saveRecivingTemp($input);
        return $ReceivingTemps;
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
        $ReceivingTemps = ReceivingTemp::find($id);
        $ReceivingTemps->attribute_id = request()->attribute_id;
        $ReceivingTemps->quantity = request()->quantity;
        $ReceivingTemps->total_cost = request()->total_cost;
        $ReceivingTemps->save();
        return $ReceivingTemps;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        ReceivingTemp::destroy($id);
    }
}
