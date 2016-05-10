<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', 'WelcomeController@index');


# Route::get('/', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

//routes for appendices -- begin
Route::get('appendix/home','HomeController@AppendixHome');

Route::get('appendix/sample_types','Appendix\SampleTypeController@index');
Route::post('appendix/sample_types/store','Appendix\SampleTypeController@store');
Route::get('appendix/sample_types/edit/{edit_id}','Appendix\SampleTypeController@edit');
Route::post('appendix/sample_types/update/{edit_id}','Appendix\SampleTypeController@update');

Route::get('appendix/sample_rejection_reasons','Appendix\SampleRejectionReasonController@index');

//routes for appendices -- end

//routes for locations begin

Route::get('locations/home','HomeController@LocationsHome');

Route::get('locations/regions','Location\RegionController@index');
Route::post('locations/regions/store','Location\RegionController@store');
Route::get('locations/regions/edit/{edit_id}','Location\RegionController@edit');
Route::post('locations/regions/update/{edit_id}','Location\RegionController@update');

Route::get('locations/hubs','Location\HubController@index');
Route::post('locations/hubs/store','Location\HubController@store');
Route::get('locations/hubs/edit/{edit_id}','Location\HubController@edit');
Route::post('locations/hubs/update/{edit_id}','Location\HubController@update');

Route::get('locations/districts','Location\DistrictController@index');
Route::post('locations/districts/store','Location\DistrictController@store');
Route::get('locations/districts/edit/{edit_id}','Location\DistrictController@edit');
Route::post('locations/districts/update/{edit_id}','Location\DistrictController@update');

//routes for ips
Route::get('ips/ips','IPController@index');
Route::post('ips/ips/store','IPController@store');
Route::get('ips/ips/edit/{edit_id}','IPController@edit');
Route::post('ips/ips/update/{edit_id}','IPController@update');
