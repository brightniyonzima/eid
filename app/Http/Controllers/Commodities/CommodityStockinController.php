<?php namespace EID\Http\Controllers\Commodities;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\Models\Commodities\Commodity;
use EID\Models\Commodities\CommodityCategory;
use EID\Models\Commodities\CommodityStockin;

use Validator;
use Lang;
use Redirect;
use Request;
use Session;

class CommodityStockinController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$stockins=CommodityStockin::getAllCommodityStockin();
		return view("commodities.stockin.index",compact("stockins"));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$commodities=Commodity::commoditiesArr();
		return view("commodities.stockin.create",compact("commodities"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$data=Request::all();
		$data['created']=date('Y-m-d H:i:s');
		$data['createdby']=Session::get('username')?Session::get('username'):"system";	
		$data['arrival_date']=date("Y-m-d",strtotime($data['arrival_date']));
		$data['expiry_date']=date("Y-m-d",strtotime($data['expiry_date']));
		$validator = Validator::make($data, CommodityStockin::$rules);
		if($validator->fails()){
			return redirect()->back()->withInput()->with('msge',trans('general.save_failure'));
		}else{
			$commodity_stockin=CommodityStockin::create($data);	
			return redirect('commodities/stockin/show/'.$commodity_stockin->id)->with('msge',trans('general.save_success'));
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
		$commodity_stockin=CommodityStockin::getCommodityStockin($id);
		return view('commodities.stockin.show',compact("commodity_stockin"));
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
		$commodity_stockin=CommodityStockin::findOrFail($id);
		$commodities=Commodity::commoditiesArr();
		return view("commodities.stockin.edit",compact("commodity_stockin","commodities","id"));
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
		$data=Request::all();
		$data['arrival_date']=date("Y-m-d",strtotime($data['arrival_date']));
		$data['expiry_date']=date("Y-m-d",strtotime($data['expiry_date']));

		$commodity_stockin = CommodityStockin::findOrFail($id);

		$validator = Validator::make($data, CommodityStockin::$rules);

		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput()->with('msge',trans('general.edit_failure'));
		$commodity_stockin->update($data);
		return redirect('commodities/stockin/show/'.$id)->with('msge',trans('general.edit_success'));
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
