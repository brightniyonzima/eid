<?php

namespace EID\Http\Controllers;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;
use StockManager;

use EID\stock_adjustments;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Input;
use DB;

class stock_adjustmentsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $stock_adjustments = stock_adjustments::paginate(15);

        return view('stock_adjustments.index', compact('stock_adjustments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('stock_adjustments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //-----------------------//
        $facility_id = Input::get('facility_id');
        $commodity = Input::get('commodity');
        $commodity_id = $commodity[0];
        $adjustment_type=Input::get('adjustment_type');
        $change_in_quantity = Input::get('change_in_quantity');
        $remarks = Input::get('remarks');
        $adjustment = stock_adjustments::create( ['commodity_id'=>$commodity_id,
                                                  'facility_id'=>$facility_id,
                                                   'change_in_quantity'=>$change_in_quantity,
                                                   'adjustment_type'=>$adjustment_type,
                                                   'remarks'=>$remarks
                                                 ] );
        //----------------------//


        //$adjustment = stock_adjustments::create( $request->all() );
        $stock_adjustment_id = $adjustment->id;
        
        $new_status = StockManager::update_stock_status(SAVE_AS_STOCK_ADJUSTMENT, $stock_adjustment_id, $request);

        //---------------bright start-------------//
        
        $update_stock = StockManager::update_stock_on_stock_adjustment($stock_adjustment_id, $facility_id, $commodity_id, $change_in_quantity, $adjustment_type);
        //---------------bright end------------------//

        Session::flash('flash_message', 'stock_adjustments successfully added!');

        return redirect('stock_adjustments');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $stock_adjustment = stock_adjustments::findOrFail($id);

        return view('stock_adjustments.show', compact('stock_adjustment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $stock_adjustment = stock_adjustments::findOrFail($id);

        return view('stock_adjustments.edit', compact('stock_adjustment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        $stock_adjustment = stock_adjustments::findOrFail($id);
        $stock_adjustment->update($request->all());

        Session::flash('flash_message', 'stock_adjustments successfully updated!');

        return redirect('stock_adjustments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        stock_adjustments::destroy($id);

        Session::flash('flash_message', 'stock_adjustments successfully deleted!');

        return redirect('stock_adjustments');
    }

}
