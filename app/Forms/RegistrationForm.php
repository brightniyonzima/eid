<?php  namespace EID\Forms;	


class RegistrationForm {

	protected $rules = [
		'username' => 'required|unique:users',
		'email'    => 'required|email|unique:users',
		'password' => 'required|confirmed'
	];

} 