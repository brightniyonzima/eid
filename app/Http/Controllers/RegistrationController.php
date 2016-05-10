<?php  namespace EID\Http\Controllers;

use View;
use EID\Forms\RegistrationForm;
use EID\Http\Requests\VerifyRegistrationRequest;

use Input;
use EID\User;
use EID\Models\UserRole;
class RegistrationController extends Controller {

	/**
	 * @var RegistrationForm
	 */
	private $registrationForm;

	/**
	 * @param RegistrationForm $registrationForm
	 */
	function __construct(RegistrationForm $registrationForm)
	{
		$this->registrationForm = $registrationForm;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()

	{	
		$user_roles=UserRole::userRolesArr();
		return View::make('registration.create',compact('user_roles'));

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(VerifyRegistrationRequest $request)
	{
		$input = Input::only('username', 'type', 'email', 'password', 'password_confirmation');

		$user = User::create($input);

		\Auth::login($user);

		return \Redirect::home();
	}
}
