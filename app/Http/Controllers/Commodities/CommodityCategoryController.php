<?php namespace EID \Http\Controllers\Commodities;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\Models\Commodities\CommodityCategory;

use Validator;
use Lang;
use Redirect;
use Request;
use Session;
class CommodityCategoryController extends Controller {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//landing page or categories of commodities
		$categories=CommodityCategory::all();
		$post_url='commodities/categories/store';
		return view("commodities.categories",compact('categories','post_url'));

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		$posted=Request::all();
		extract($posted);
		$errors=0;		
		foreach ($categories as $k => $v) {
			$data=array();
			$data['category']=$v;
			$data['created']=date('Y-m-d H:i:s');
			$data['createdby']=Session::get('username')?Session::get('username'):"system";	
			$validator = Validator::make($data, CommodityCategory::$rules);
			if($validator->fails()) $errors++;
			else CommodityCategory::create($data);	
		}
		$msge=$errors>=1?trans('general.save_failure'):trans('general.save_success');
		
		return redirect('commodities/categories')->with('msge',$msge);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($edit_id)
	{
		//
		$categories=CommodityCategory::all();
		$post_url='commodities/categories/update/'.$edit_id;
		return view('commodities.categories',compact('categories','post_url','edit_id'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($edit_id)
	{
		//
		$category = CommodityCategory::findOrFail($edit_id);
		$validator = Validator::make($data=Request::all(), CommodityCategory::$rules);
		if ($validator->fails()) return back()->withErrors($validator)->withInput()->with('msge',trans('general.edit_failure'));
		$category->update($data);
		return redirect('commodities/categories')->with('msge',trans('general.edit_success'));
	}
}
