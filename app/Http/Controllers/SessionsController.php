<?php namespace EID\Http\Controllers;


use View;
use EID\Forms\LoginForm;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use EID\Http\Requests\VerifyLoginRequest;
use EID\Models\User;
use EID\Models\UserRole;

class SessionsController extends Controller {

	/**
	 * @var Acme\Forms\LoginForm
	 */
	protected $loginForm;

	/**
	 * @param LoginForm $loginForm
	 */
	function __construct(LoginForm $loginForm)
	{
		$this->loginForm = $loginForm;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('sessions.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */


	public function store(VerifyLoginRequest  $request)
	{

		$credentials = \Input::only('username', 'password');

		if ($res=\Auth::attempt($credentials+['deactivated'=>'0']))
		{	
			/*$jwt = JWTAuth::attempt($credentials);
			if(empty($jwt)) \Redirect::back()->withInput()->withFlashMessage('Login Failed');
			else \Response::json(compact('jwt'));*/
			$user=\Auth::user();
			$role=UserRole::findOrFail($user->type);			
			$perms_arr=unserialize($role->permissions);
			$perm_parents_arr=unserialize($role->permission_parents);			
			session([
				'username'=>$user->username,
				'email'=>$user->email,
				'is_admin'=>$user->is_admin,
				'facility_limit'=>$user->facilityID,
				'hub_limit'=>$user->hubID,
				'ip_limit'=>$user->ipID,
				'permissions'=>$perms_arr,
				'permission_parents'=>$perm_parents_arr]);
			return \Redirect::intended('/');			
		}

		return \Redirect::back()->withInput()->withFlashMessage('Login Failed');

				/*if( Auth::check() ){
					return Redirect::intended('/');// user was already logged in
				}

				$credentials = Input::only('email', 'password');
				$user_is_logged_in = Auth::attempt($credentials, true);

				if($user_is_logged_in){
					return Redirect::home();
				}
				else{
					return Redirect::back()->withInput()->withFlashMessage('Invalid credentials provided');
				}*/

		
		// $jwt = JWTAuth::attempt($credentials) ?: null;

		// if( $jwt == null ){// auth attempt failed
		// 	return Redirect::back()->withInput()->withFlashMessage('Invalid credentials provided');
		// }

		// // auth attempt succeeded:
		// $user = Auth::user();
		// Auth::loginUsingId($user->id);
		// return $this->loginTo('http://chai.admin/', $jwt);
		// // return Redirect::home();
	}

	public function store_OldVersion(VerifyLoginRequest  $request)
	{
		$credentials = Input::only('email', 'password');
		$jwt = JWTAuth::attempt($credentials) ?: null;

		return \Redirect::back()->withInput()->withFlashMessage('Login Failed');
	/*	if( $jwt == null ){// auth attempt failed
			return Redirect::back()->withInput()->withFlashMessage('Invalid credentials provided');
		}*/

		// auth succeeded:
		// $this->loginTo('http://chai.admin', $jwt);
		// return Redirect::intended('/');
		// auth attempt succeeded:
		$user = Auth::user();
		Auth::loginUsingId($user->id);
		return $this->loginTo('http://chai.admin/', $jwt);
		// return Redirect::home();
	}


	public function rlogin()
	{
		if(Auth::guest()){
			return "Please login first";// use flash msg instead
		}

		$user = Auth::user();

		if($user->is_admin){
			
			$jwt = JWTAuth::fromUser(Auth::user());
			return $this->loginTo('http://chai.admin/', $jwt);
		}
		else{
			return "You are not authorised to view admin page";
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id = null)
	{   
		\Auth::logout();
		\Session::flush();
		return redirect('/');
	}

	public static function php_session_getBool($key){

		if(empty($key)) return "false";
		
		if(Session::has($key)){
			return Session::get($key);
		}else{
			return "false";// works for me. YMMV
		}
	}


	public static function php_session_setBool($key){

		if(\Input::has($key)){

			if(\Input::get($key) === "YES")
				\Session::put($key, true);
			else
				\Session::put($key, false);
		}
	}


	public function loginTo($domain, $jwt){
		
		$url = $domain . "?token=" . $jwt;
		return redirect($url);
	}
}
