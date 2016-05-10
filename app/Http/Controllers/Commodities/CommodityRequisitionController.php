<?php namespace EID\Http\Controllers\Commodities;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\Models\Commodities\Commodity;
use EID\Models\Commodities\CommodityCategory;
use EID\Models\Commodities\CommodityStockin;
use EID\Models\Commodities\CommodityRequisition;
use EID\Models\Commodities\CommodityReqApproval;

use Validator;
use Lang;
use Redirect;
use Request;
use Session;

class CommodityRequisitionController extends Controller {

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
		$commodities=Commodity::commoditiesArr();
		return view("commodities.requisitions.create",compact("commodities"));

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
		$validator = Validator::make($data, CommodityRequisition::$rules);
		if($validator->fails()){
			return redirect()->back()->withInput()->with('msge',trans('general.save_failure'));
		}else{
			$requisition=CommodityRequisition::create($data);	
			return redirect('commodities/requisitions/show/'.$requisition->id)->with('msge',trans('general.save_success'));
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
		$requisition=CommodityRequisition::getCommodityRequisition($id);
		return view('commodities.requisitions.show',compact("requisition"));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function approve($id){
		$requisition=CommodityRequisition::getCommodityRequisition($id);
		return view('commodities.requisitions.approve',compact("requisition","id"));
	}

	public function post_approve($id){
		$data=Request::all();

		$requisition = CommodityRequisition::findOrFail($id);
		$cmdty=Commodity::findOrFail($requisition->commodityID);
		$cmdty_bal=$cmdty->getCurrentQuantity($cmdty->id);

		$post_bal=$data['quantity_approved']-$cmdty_bal;

		/*if($post_bal<=$cmdty->alert_quantity){

			\Mail::raw("Quantity of $cmdty->commodity running low", function($message){
				$subject='EID Alerts [Low Quantity]';
				$message->to('pkitutu@gmail.com')->subject($subject);
				$message->to('pkitutu@clintonhealthaccess.org')->subject($subject);
				$message->to('pkitutu@cis.mak.ac.ug')->subject($subject);
			});
		}*/

		if($cmdty_bal<$data['quantity_approved']){
			return redirect()->back()->withInput()->with('msge',trans('general.less_balance'));
		}



		$requisition->update(["approved"=>1]);
		
		$data['requisitionID']=$id;
		$data['created']=date('Y-m-d H:i:s');
		$data['createdby']=Session::get('username')?Session::get('username'):"system";
		$req_approval=CommodityReqApproval::create($data);
		return redirect('commodities/requisitions/pending_reqs')->with('msge',trans('general.edit_success'));	
	}

	public function pending_reqs(){
		$requisitions=CommodityRequisition::getPendingRequisitions();
		return view("commodities.requisitions.pending_reqs",compact("requisitions"));
	}

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
