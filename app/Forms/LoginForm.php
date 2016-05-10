<?php namespace EID\Forms;	
// use Laracasts\Validation\FormValidator;

// class LoginForm extends FormValidator {
class LoginForm {
	protected $rules = [
		'email'    => 'required|email',
		'password' => 'required'
	];
}
