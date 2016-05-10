<?php namespace EID\Http\Controllers\CustomerCare;

use EID\Http\Requests;
use EID\Http\Controllers\Controller;

use EID\Models\CustomerCare\Category;

use Validator;
use Lang;
use Redirect;
use Request;
use Session;

class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$categories=Category::all();
		$post_url='customer_care/categories/store';
		return view("customer_care.categories",compact('categories','post_url'));
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
		$posted=Request::all();
		extract($posted);
		$errors=0;		
		foreach ($categories as $k => $v) {
			$data=array();
			$data['category']=$v;
			$data['created']=date('Y-m-d H:i:s');
			$data['createdby']=Session::get('username')?Session::get('username'):"system";	
			$validator = Validator::make($data, Category::$rules);
			if($validator->fails()) $errors++;
			else Category::create($data);	
		}
		$msge=$errors>=1?trans('general.save_failure'):trans('general.save_success');
		
		return redirect('customer_care/categories')->with('msge',$msge);
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
		$categories=Category::all();
		$post_url='customer_care/categories/update/'.$edit_id;
		return view('customer_care.categories',compact('categories','post_url','edit_id'));
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
		$category = Category::findOrFail($edit_id);
		$validator = Validator::make($data=Request::all(), Category::$rules);
		if ($validator->fails()) return back()->withErrors($validator)->withInput()->with('msge',trans('general.edit_failure'));
		$category->update($data);
		return redirect('customer_care/categories')->with('msge',trans('general.edit_success'));
	}


}
