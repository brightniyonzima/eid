<?php namespace EID\Http\Controllers;

use View;
class PagesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$auth=\Auth::check();
		return $auth?View::make('pages.index'):View::make('sessions.create');
	}

	protected function followUpFormAsPDF(){


		if(! \Request::has('f'))
			return "ERROR: No form number";


		$batch_id = \Request::get('f');
		
		$view = \View::make('art_init');
		$contents = $view->renderSections()['content'];

		$pdf = \PDF::loadHTML($contents);

		return $pdf->setOrientation('landscape')->stream("$batch_id".".pdf");
	}

	
	public function art()
	{

		if(\Request::has('fd'))
			return $this->followUpFormAsPDF();
		else
			return View::make('art_init');

	}

	public function ng_test()
	{
		return View::make('ng-ajax');
	}
}
