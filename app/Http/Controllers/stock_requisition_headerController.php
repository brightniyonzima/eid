<?php

namespace EID\Http\Controllers;

use EID\stock_status;
use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\stock_requisition_header;
use Illuminate\Http\Request;
use EID\Models\Stock\StockStatus;
use Carbon\Carbon;
use Session;
use DB;

class stock_requisition_headerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $CPHL = 2406;
        $comparison = \Request::has('i') ? "=" : "!=" ;
        $stock_requisition_headers = stock_requisition_header::where('facility_id', $comparison, $CPHL)->paginate(15);

        return view('stock_requisition_header.index', compact('stock_requisition_headers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('stock_requisition_header.create');
    }

    /**
     * Show the form for approving/rejecting a requisition.
     *
     * @return Response
     */
    public function approval($id)
    {
        return view('stock_requisition_header.approval', array('requisition_id' => $id));
    }


    /**
     * Release stock from the store for dispath
     *
     * @return Response
     */
    public function stock_release()
    {
        return view('stock_requisition_header.outgoing_stock');
    }


    /**
     * After sending stock to facilities, they send back a delivery receipt. This records it
     *
     * @return Response
     */
    public function stock_received()
    {
        return view('stock_requisition_header.receipt');
    }



    /**
     * Provide early warning about when certain facilities will need to be re-stocked
     *
     * @return Response
     */
    public function stock_forecast()
    {
        return view('stock_requisition_header.stock_forecast');
    }

    public function stock_out()
    {
        return view('stock_requisition_header.stock_out');
    }


    public function stock_status()
    {
        $MatchFields1=['stock_status.stock_changed_by'=>'STOCK_ADJUSTMENT','stock_status.is_most_recent_change'=>'YES'];
        $MatchFields2=['stock_status.stock_changed_by'=>'STOCK_REQUISITION',
                       'stock_status.is_most_recent_change'=>'YES'
                       ];
        
        $AdjustmentStockStatus = DB::table('stock_status')
        ->join('stock_adjustments','stock_status.stock_change_details_id', '=', 'stock_adjustments.id')
        ->join('commodities','stock_status.commodity_id','=','commodities.id')
        ->join('facilities','stock_status.facility_id','=','facilities.id')
        ->select('stock_status.total_stock_on_hand','stock_status.average_monthly_consumption',
                 'stock_status.restock_quantity','stock_status.forecasted_stockout_date','facilities.facility AS facility',
                 'commodities.commodity AS commodity','stock_adjustments.change_in_quantity AS adjustments')
        ->where($MatchFields1);
        


        $RequisitionStockStatus = DB::table('stock_status')
        ->join('stock_requisition_line_items','stock_status.stock_change_details_id','=','stock_requisition_line_items.id')
        ->join('commodities','stock_status.commodity_id','=','commodities.id')
        ->join('facilities','stock_status.facility_id','=','facilities.id')
        ->select('stock_status.total_stock_on_hand','stock_status.average_monthly_consumption',
                 'stock_status.restock_quantity','stock_status.forecasted_stockout_date','facilities.facility AS facility',
                 'commodities.commodity AS commodity','stock_requisition_line_items.quantity_approved AS adjustments')
        ->where($MatchFields2);

        $results=$AdjustmentStockStatus->union($RequisitionStockStatus)->get();

        return view('stock_requisition_header.stock_status',compact("results"));
    }

    public function stock_settings()
    {
        return view('stock_requisition_header.stock_settings');
    }




    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        stock_requisition_header::create($request->all());

        Session::flash('flash_message', 'stock_requisition_header successfully added!');

        return redirect('stock_requisition_header');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $stock_requisition_header = stock_requisition_header::findOrFail($id);

        return view('stock_requisition_header.show', compact('stock_requisition_header'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $stock_requisition_header = stock_requisition_header::findOrFail($id);

        return view('stock_requisition_header.edit', compact('stock_requisition_header'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        $stock_requisition_header = stock_requisition_header::findOrFail($id);
        $stock_requisition_header->update($request->all());

        Session::flash('flash_message', 'stock_requisition_header successfully updated!');

        return redirect('stock_requisition_header');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        stock_requisition_header::destroy($id);

        Session::flash('flash_message', 'stock_requisition_header successfully deleted!');

        return redirect('stock_requisition_header');
    }

}
