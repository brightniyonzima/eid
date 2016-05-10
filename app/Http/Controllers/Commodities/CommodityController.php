<?php namespace EID\Http\Controllers\Commodities;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\Models\Commodities\Commodity;
use EID\Models\Commodities\CommodityCategory;
use EID\Models\Commodities\CommodityRequisition;
use EID\Models\Commodities\CommodityStockin;
use EID\Models\Commodities\CommodityConfig;

use Validator;
use Lang;
use Redirect;
use Request;
use Session;

class CommodityController extends Controller {
	/**
	 * View for home /start of commodity management
	 *
	 * @return Response
	 */

	public function commodityManHome(){
		return view('commodities.home');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
		//
		$commodities=Commodity::getCommodities();
		return view("commodities.commodities.index",compact("commodities"));
	}

	public function config_list(){
		$items=CommodityConfig::all();
		return view("commodities.config_list",compact("items"));
	}

	public function config_edit($id){
		$item=CommodityConfig::findOrFail($id);
		return view("commodities.config_edit",compact("item","id"));
	}

	public function config_update($id){
		$item = CommodityConfig::findOrFail($id);
		$validator = Validator::make($data=Request::all(), CommodityConfig::$rules);
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput()->with('msge',trans('general.edit_failure'));
		$item->update($data);
		return redirect('commodities/config_list')->with('msge',trans('general.edit_success'));
	
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$categories=CommodityCategory::commodityCatsArr();
		return view("commodities.commodities.create",compact("categories"));
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
		$validator = Validator::make($data, Commodity::$rules);
		if($validator->fails()){
			return redirect()->back()->withInput()->with('msge',trans('general.save_failure'));
		}else{
			$commodity=Commodity::create($data);	
			return redirect('commodities/commodities/show/'.$commodity->id)->with('msge',trans('general.save_success'));
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
		$commodity=Commodity::getCommodity($id);
		return view("commodities.commodities.show",compact("commodity"));
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
		$categories=CommodityCategory::commodityCatsArr();
		$commodity=Commodity::findOrFail($id);
		return view("commodities.commodities.edit",compact("commodity","categories","id"));
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
		$commodity = Commodity::findOrFail($id);
		$validator = Validator::make($data=Request::all(), Commodity::$rules);
		if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput()->with('msge',trans('general.edit_failure'));
		$commodity->update($data);
		return redirect('commodities/commodities/show/'.$id)->with('msge',trans('general.edit_success'));
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

	public function balances(){
		$commodities=Commodity::getCommodities();
		return view("commodities.stock_status.balances",compact("commodities"));
	}

}
