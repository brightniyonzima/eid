<?php namespace EID\Http\Controllers\CustomerCare;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\Models\CustomerCare\Category;
use EID\Models\CustomerCare\Complaint;
use EID\Models\Facility;

use Validator;
use Lang;
use Redirect;
use Request;
use Session;

class ComplaintController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$complaints=Complaint::getComplaints();		
		return view("customer_care.complaints.index",compact("complaints"));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		$categories=Category::catsArr();
		$facilities=Facility::facilitiesArr();
		return view("customer_care.complaints.create",compact("categories","facilities"));
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

		$validator = Validator::make($data, Complaint::$rules);
		if($validator->fails()){
			return redirect()->back()->withInput()->with('msge',trans('general.save_failure'));
		}else{
			$complaint=Complaint::create($data);
			if(array_key_exists('create_new', $data)){
				return redirect('customer_care/complaints/create')->with('msge',trans('general.save_success'));
			}else{
				return redirect('customer_care/complaints/show/'.$complaint->id)->with('msge',trans('general.save_success'));
			}
			//return redirect('user_roles/show/'.$facility->id)->with('msge',trans('general.save_success'));
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
		$complaint=Complaint::getComplaint($id);
		return view("customer_care.complaints.show",compact("complaint"));
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
