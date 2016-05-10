<?php namespace EID\Http\Controllers\Admin;
				

class AdminHomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/


	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('Admin.home');
	}

	public function AppendixHome(){
		return view('appendix.home');
	}

	public function LocationsHome(){
		return view('locations.home');
	}


}