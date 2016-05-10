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


#New
Route::get('/labss', ['as' => 'xx', 'uses' => 'LabController@find_sample']); // direct
Route::get('/rejects', ['as' => 'yyx', 'uses' => 'LabController@find_rejected_sample']); // direct
Route::get('/csvreload/{ws}', ['as' => 'CSVreload', 'uses' => 'LabController@cancel_CSV_upload']); // direct
Route::get('/ldf', ['as' => 'lab_dispatch', 'uses' => 'LabController@dispatch_results']); // direct
Route::get('/hc_env/{batch_id}', ['as' => 'hc_env', 'uses' => 'LabController@facility_envelope']); // direct
Route::get('/backlog', ['uses' => 'LabController@print_backlog']); // direct
Route::get('/scd_results/{scws}', ['uses' => 'SCController@show_results']); // direct
Route::get('/eron', ['uses' => 'SamplesController@eron']); // direct
Route::get('/eron_envelopes', ['uses' => 'SamplesController@eron_envelopes']); // direct
Route::get('/sc_result_slips', ['uses' => 'SamplesController@sc_result_slips']); // direct

Route::get('data_entry_performance',['uses'=>'ReportController@data_entry_performance']);
Route::get('data_entry_metrics',['uses'=>'ReportController@data_entry_metrics']);


# Home
Route::get('/qq', ['as' => 'xx', 'uses' => 'LabController@qq']); // direct

Route::get('/qty', ['uses' => 'ReportController@data_entry_qty']); // direct
Route::get('/qty_eid', ['uses' => 'ReportController@qty_eid']); // direct
Route::get('/fail_rate', ['uses' => 'ReportController@fail_rate']); // direct
Route::get('/rpt_printed', ['uses' => 'ReportController@rpt_printed']); // direct

Route::get('/', ['as' => 'home', 'uses' => 'PagesController@index']); // direct
Route::get('/login', ['as' => 'login', 'uses' => 'SessionsController@create']);// direct
Route::resource('sessions', 'SessionsController', ['only' => ['create', 'store', 'destroy']]);// not yet checked

Route::group(['middleware' => 'auth'], function()
{//for authentications -- start here

Route::post('/artDB', ['as' => 'save_ART_data', 'uses' => 'SamplesController@saveARTdata']); // direct
Route::get('/printer', ['as' => 'printer1', 'uses' => 'PagesController@printer']); // direct
Route::get('/ai', ['as' => 'art_init', 'uses' => 'PagesController@art']); // direct
Route::match(array('GET', 'POST'), '/ws', ['as' => 'printer2', 'uses' => 'LabController@store_worksheet']); // direct
Route::get('/pws', ['as' => 'printer3', 'uses' => 'LabController@print_worksheet']); // direct
Route::get('/dcsv/{ws_id}/{j}', ['as' => 'dcsvx', 'uses' => 'DummyDataController@makeDummyCSV']); // direct
Route::get('/nextws/{prev_ws_id}', ['as' => 'make_next_worksheet', 'uses' => 'DummyDataController@make_next_ws']); // direct
Route::get('/scd', ['as' => 'scd', 'uses' => 'SCController@enter_results']); // direct
Route::post('/scstore', ['as' => 'scstore', 'uses' => 'SCController@store_results']); // direct
Route::get('/scwsList', ['as' => 'scd', 'uses' => 'SCController@scwsList']); // direct
Route::get('/dispatch', ['permission'=>24,'as' => 'dispatchList', 'uses' => 'SamplesController@dispatchList']); // direct
Route::get('/dispatch_scd', ['permission'=>24,'as' => 'dispatchList', 'uses' => 'SamplesController@dispatchList_SC']); // direct
Route::get('/follow', ['as' => 'follow_up', 'uses' => 'SamplesController@followUp']); // direct
Route::get('/scws_maker', ['as' => 'scws_maker', 'uses' => 'LabController@make_ws']); // direct
Route::get('/ng', ['uses' => 'PagesController@ng_test']); // direct


# User Registration
Route::get('/register', 'RegistrationController@create');// ->before('guest'); // direct
Route::post('/register', ['as' => 'registration.store', 'uses' => 'RegistrationController@store']); // indirect

# User Authentication
Route::get('/rlogin', ['as' => 'remote_login', 'uses' => 'SessionsController@rlogin']);// direct
Route::get('/logout', ['as' => 'logout', 'uses' => 'SessionsController@destroy']);// direct





# EID Samples and Batches

//Route::get('/samples', ['as'=>'samples', 'uses'=>'SamplesController@samples']);// direct
//Route::get('/samples/{sc?}', ['as'=>'samples', 'uses'=>'SamplesController@samples']);// direct

Route::get('/scws/{ws_id}', ['as'=>'scws', 'uses'=>'SamplesController@scws']);// direct
Route::get('/scws_store', ['as'=>'scws_store', 'uses'=>'SCController@save_ws_data']);// direct
Route::get('/cancel_scws/{ws_id}', ['uses'=>'SCController@cancel_scws']);// direct
Route::get('/delete_scws/{ws_id}', ['uses'=>'SCController@delete_scws']);// direct


Route::get('/samples/{id}', ['permission'=>13,'as'=>'samples', 'uses'=>'SamplesController@show']);// direct
Route::get('/samples/{sc?}', ['permission'=>11,'as'=>'samples', 'uses'=>'SamplesController@samples']);// direct

Route::get('/data_entry_speed', ['permission'=>11, 'uses'=>'SamplesController@data_entry_speed']);// direct
// Route::get('/data_entry_accuracy', ['permission'=>11, 'uses'=>'SamplesController@data_entry_accuracy']);// direct

//Routes for and rejected samples
Route::get('dbs/approvedbatches','SamplesController@approvedbatches');
Route::get('dbs/rejectedbatches','SamplesController@rejectedbatches');
Route::get('dbs/approvedsamples/{batchID}','SamplesController@approvedsamples');


Route::get('/batch', ['as'=>'add_batch', 'uses'=>'SamplesController@store_batch']);// change to POST // indirect
Route::get('/dbs', ['as'=>'add_sample',	'uses'=>'SamplesController@store_sample']);// change to POST // indirect
Route::get('/batchQ', ['permission'=>14,'as'=>'samples_to_approve', 'uses'=>'SamplesController@pending_batches']);// direct
Route::get('/dbsQ/{batch_id}', ['permission'=>12,'as'=>'samples_to_approve', 'uses'=>'SamplesController@pending_samples']);// direct
Route::get('/o_dbs_data/{batch_id}', ['permission'=>12, 'uses'=>'SamplesController@get_dbs_data']);// direct
Route::get('batches',['permission'=>12,'uses'=>'SamplesController@list_batches']);

Route::get('/dbsVerify/{sample_id}', 'SamplesController@store_approval');// change to POST // indirect
Route::get('/approve/{batch_id}', ['permission'=>14,'uses'=>'SamplesController@approve']);// direct
Route::get('/bDate', 'SamplesController@newDates');// direct
Route::get('/eid_results', ['permission'=>24,'uses'=>'SamplesController@show_results']);// direct

Route::get('/eid_review/{ws_id}', ['permission'=>24,'uses'=>'SamplesController@review_results']);// direct
Route::get('/eid_release', ['permission'=>24,'uses'=>'SamplesController@release_eid_results']);// direct


Route::get('/rejected_results', ['permission'=>24,'uses'=>'SamplesController@show_rejected_results']);// direct
Route::get('/rejected_env/{batch_id}', ['as' => 'rejected_env', 'uses' => 'LabController@rejected_envelopes']); // direct

Route::get('/vBatchNo', 'SamplesController@batchNo_unique');// direct


#EID Lab Module: Tests
Route::get('/lab', 'LabController@index');// empty
Route::get('/ctr', 'LabController@confirm_test_results');// indirect
Route::get('/csv', 'LabController@load_csv');// indirect
Route::get('/bc', 'LabController@barcode');// barcode generator

#EID Lab Module: Worksheets
Route::get('/worksheet', 'LabController@worksheet_maker');// direct
Route::get('/worksheet_list', 'LabController@worksheet_list');// indirect
Route::get('/worksheet_download', 'LabController@worksheet_download');// direct: needs ID param
Route::get('/worksheet_details', 'LabController@worksheet_details');// indirect
Route::get('/w', ['permission'=>17,'uses'=>'LabController@display_worksheet']);// direct
Route::get('/ss/{t}', 'LabController@setState');// direct
Route::get('/wlist', ['permission'=>18,'as'=>'wlist', 'uses' => 'LabController@list_worksheets']);// direct
Route::get('/dummy_ws/{ws_id}', ['as'=>'dws', 'uses' => 'DummyDataController@dummy_ws']);// direct 
Route::get('/dummy_rs/{ws_id}', ['as'=>'dummy_results', 'uses' => 'DummyDataController@dummy_rs']);// direct
Route::get('/wcsv', 'LabController@load_csv2');// direct
Route::post('/wcsv', 'LabController@load_csv_data');// direct
Route::match(array('GET', 'POST'), '/bc2', 'LabController@barcode2');// barcode generator
Route::get('/img', 'LabController@barcode3');// barcode generator
Route::get('/rscs/{code}', 'LabController@releaseSCsamples');
Route::get('/rscs_undo/{code}', 'LabController@undo_releaseSCsamples');


##
##
## 	ADMIN ROUTES====================================================================
##
##

# EID Admin Home
Route::get('/admin', ['uses'=>'Admin\AdminHomeController@index']);
Route::get('/admin/home', ['uses'=>'Admin\AdminHomeController@index']);

#routes for appendices
Route::get('appendices/index/{cat_id}',['permission'=>45,'uses'=>'Admin\AppendixController@index']);
Route::post('appendices/store/{cat_id}',['permission'=>45,'uses'=>'Admin\AppendixController@store']);
Route::get('appendices/edit/{cat_id}/{edit_id}',['permission'=>45,'uses'=>'Admin\AppendixController@edit']);
Route::post('appendices/update/{cat_id}/{edit_id}',['permission'=>45,'uses'=>'Admin\AppendixController@update']);
Route::get('appendices/deactivate/{cat_id}/{edit_id}/{status}',['permission'=>45,'uses'=>'Admin\AppendixController@deactivate']);

# EID IP Management
Route::get('ips/index',['permission'=>46,'uses'=>'Admin\IPController@index']);
Route::get('ips/create',['permission'=>46,'uses'=>'Admin\IPController@create']);
Route::post('ips/store',['permission'=>46,'uses'=>'Admin\IPController@store']);
Route::get('ips/show/{id}',['permission'=>46,'uses'=>'Admin\IPController@show']);
Route::get('ips/edit/{id}',['permission'=>46,'uses'=>'Admin\IPController@edit']);
Route::post('ips/update/{id}',['permission'=>46,'uses'=>'Admin\IPController@update']);


# EID Facility Management
Route::get('facilities/index',['permission'=>47,'uses'=>'Admin\FacilityController@index']);
Route::get('facilities/create',['permission'=>47,'uses'=>'Admin\FacilityController@create']);
Route::post('facilities/store',['permission'=>47,'uses'=>'Admin\FacilityController@store']);
Route::get('facilities/show/{id}',['permission'=>47,'uses'=>'Admin\FacilityController@show']);
Route::get('facilities/edit/{id}',['permission'=>47,'uses'=>'Admin\FacilityController@edit']);
Route::post('facilities/update/{id}',['permission'=>47,'uses'=>'Admin\FacilityController@update']);
Route::get('facilities/live_search/{q}',['permission'=>47,'uses'=>'Admin\FacilityController@live_search']);


# Roles + Users
Route::get('user_roles/index',['permission'=>43,'uses'=>'Admin\UserRoleController@index']);
Route::get('user_roles/create',['permission'=>43,'uses'=>'Admin\UserRoleController@create']);
Route::post('user_roles/store',['permission'=>43,'uses'=>'Admin\UserRoleController@store']);
Route::get('user_roles/show/{id}',['permission'=>43,'uses'=>'Admin\UserRoleController@show']);
Route::get('user_roles/edit/{id}',['permission'=>43,'uses'=>'Admin\UserRoleController@edit']);
Route::post('user_roles/update/{id}',['permission'=>43,'uses'=>'Admin\UserRoleController@update']);
Route::post('user_roles/index',['permission'=>43,'uses'=>'Admin\UserRoleController@index']);

Route::get('users/index',['permission'=>44,'uses'=>'Admin\UserController@index']);
Route::get('users/create',['permission'=>44,'uses'=>'Admin\UserController@create']);
Route::post('users/store',['permission'=>44,'uses'=>'Admin\UserController@store']);
Route::get('users/show/{id}',['permission'=>44,'uses'=>'Admin\UserController@show']);
Route::get('users/edit/{id}',['permission'=>44,'uses'=>'Admin\UserController@edit']);
Route::post('users/update/{id}',['permission'=>44,'uses'=>'Admin\UserController@update']);
Route::get('users/change_password/{id}',['permission'=>44,'uses'=>'Admin\UserController@change_password']);
Route::post('users/post_change_password/{id}',['permission'=>44,'uses'=>'Admin\UserController@post_change_password']);
Route::get('users/deactivate_account/{id}/{status}',['permission'=>44,'uses'=>'Admin\UserController@deactivate_account']);

Route::get('user_pwd_change',['uses'=>'Admin\UserController@user_pwd_change']);
Route::post('post_user_pwd_change',['uses'=>'Admin\UserController@post_user_pwd_change']);

// Route::get('ips/ips','Admin\IPController@index');
// Route::get('facilities/index','Admin\FacilityController@index');
// Route::get('locations/home','Admin\AdminHomeController@LocationsHome');

# EID Location Management
Route::get('locations/home',['permission'=>48,'uses'=>'Admin\AdminHomeController@LocationsHome']);

Route::get('locations/regions',['permission'=>48,'uses'=>'Admin\Location\RegionController@index']);
Route::post('locations/regions/store',['permission'=>48,'uses'=>'Admin\Location\RegionController@store']);
Route::get('locations/regions/edit/{edit_id}',['permission'=>48,'uses'=>'Admin\Location\RegionController@edit']);
Route::post('locations/regions/update/{edit_id}',['permission'=>48,'uses'=>'Admin\Location\RegionController@update']);



# EID Hubs Management
Route::get('locations/hubs', ['permission'=>48,'uses'=>'Admin\Location\HubController@index']);
Route::post('locations/hubs/store', ['permission'=>48,'uses'=>'Admin\Location\HubController@store']);
Route::get('locations/hubs/edit/{edit_id}', ['permission'=>48,'uses'=>'Admin\Location\HubController@edit']);
Route::post('locations/hubs/update/{edit_id}',['permission'=>48,'uses'=>'Admin\Location\HubController@update']);




# EID Districts Management
Route::get('locations/districts',['permission'=>48,'uses'=>'Admin\Location\DistrictController@index']);
Route::post('locations/districts/store',['permission'=>48,'uses'=>'Admin\Location\DistrictController@store']);
Route::get('locations/districts/edit/{edit_id}',['permission'=>48,'uses'=>'Admin\Location\DistrictController@edit']);
Route::post('locations/districts/update/{edit_id}',['permission'=>48,'uses'=>'Admin\Location\DistrictController@update']);

# testing

Route::get('/approve', ['uses'=>'SamplesController@approve']);

// Route::get('/approve', 'SamplesController@approve');
Route::get('/approve/{id}', ['uses'=>'SamplesController@approve']);

#To download data in excel
Route::get('excel', function(){
	$ex=Excel::create(session('excel_file_name'), function($excel) {
		$excel->sheet('Sheet1', function($sheet) {
		 	$sheet->fromArray(session('excel_data'));
		 });
	});
	return $ex->download('xls');
});


#commodities management routes

Route::get('commodities/home',['uses'=>'Commodities\CommodityController@commodityManHome']);
Route::get('commodities/categories',['permission'=>49,'uses'=>'Commodities\CommodityCategoryController@index']);
Route::post('commodities/categories/store',['permission'=>49,'uses'=>'Commodities\CommodityCategoryController@store']);
Route::get('commodities/categories/edit/{edit_id}',['permission'=>49,'uses'=>'Commodities\CommodityCategoryController@edit']);
Route::post('commodities/categories/update/{edit_id}',['permission'=>49,'uses'=>'Commodities\CommodityCategoryController@update']);

Route::get('commodities/commodities/index',['permission'=>50,'uses'=>'Commodities\CommodityController@index']);
Route::get('commodities/commodities/create',['permission'=>50,'uses'=>'Commodities\CommodityController@create']);
Route::post('commodities/commodities/store',['permission'=>50,'uses'=>'Commodities\CommodityController@store']);
Route::get('commodities/commodities/show/{id}',['permission'=>50,'uses'=>'Commodities\CommodityController@show']);
Route::get('commodities/commodities/edit/{id}',['permission'=>50,'uses'=>'Commodities\CommodityController@edit']);
Route::post('commodities/commodities/update/{id}',['permission'=>50,'uses'=>'Commodities\CommodityController@update']);

Route::get('commodities/config_list',['uses'=>'Commodities\CommodityController@config_list']);
Route::get('commodities/config_edit/{id}',['uses'=>'Commodities\CommodityController@config_edit']);
Route::post('commodities/config/update/{id}',['uses'=>'Commodities\CommodityController@config_update']);


Route::get('commodities/stockin/index',['permission'=>30,'uses'=>'Commodities\CommodityStockinController@index']);
Route::get('commodities/stockin/create',['permission'=>28,'uses'=>'Commodities\CommodityStockinController@create']);
Route::post('commodities/stockin/store',['permission'=>28,'uses'=>'Commodities\CommodityStockinController@store']);
Route::get('commodities/stockin/show/{id}',['permission'=>30,'uses'=>'Commodities\CommodityStockinController@show']);
Route::get('commodities/stockin/edit/{id}',['permission'=>29,'uses'=>'Commodities\CommodityStockinController@edit']);
Route::post('commodities/stockin/update/{id}',['permission'=>29,'uses'=>'Commodities\CommodityStockinController@update']);

Route::get('commodities/requisitions/index',['permission'=>33,'uses'=>'Commodities\CommodityRequisitionController@index']);
Route::get('commodities/requisitions/create',['permission'=>31,'uses'=>'Commodities\CommodityRequisitionController@create']);
Route::post('commodities/requisitions/store',['permission'=>31,'uses'=>'Commodities\CommodityRequisitionController@store']);
Route::get('commodities/requisitions/show/{id}',['permission'=>33,'uses'=>'Commodities\CommodityRequisitionController@show']);
Route::get('commodities/requisitions/edit/{id}',['permission'=>32,'uses'=>'Commodities\CommodityRequisitionController@edit']);
Route::post('commodities/requisitions/update/{id}',['permission'=>32,'uses'=>'Commodities\CommodityRequisitionController@update']);
Route::get('commodities/requisitions/pending_reqs',['permission'=>34,'uses'=>'Commodities\CommodityRequisitionController@pending_reqs']);
Route::get('commodities/requisitions/approve/{id}',['permission'=>34,'uses'=>'Commodities\CommodityRequisitionController@approve']);
Route::post('commodities/requisitions/post_approve/{id}',['permission'=>34,'uses'=>'Commodities\CommodityRequisitionController@post_approve']);

Route::get('commodities/facility_reqs/index',['permission'=>37,'uses'=>'Commodities\CommodityFacilityReqController@index']);
Route::get('commodities/facility_reqs/create',['permission'=>35,'uses'=>'Commodities\CommodityFacilityReqController@create']);
Route::post('commodities/facility_reqs/store',['permission'=>35,'uses'=>'Commodities\CommodityFacilityReqController@store']);
Route::get('commodities/facility_reqs/show/{id}',['permission'=>37,'uses'=>'Commodities\CommodityFacilityReqController@show']);
Route::get('commodities/facility_reqs/edit/{id}',['permission'=>36,'uses'=>'Commodities\CommodityFacilityReqController@edit']);
Route::post('commodities/facility_reqs/update/{id}',['permission'=>36,'uses'=>'Commodities\CommodityFacilityReqController@update']);
Route::get('commodities/facility_reqs/pending_reqs',['permission'=>38,'uses'=>'Commodities\CommodityFacilityReqController@pending_reqs']);
Route::get('commodities/facility_reqs/approve/{id}',['permission'=>38,'uses'=>'Commodities\CommodityFacilityReqController@approve']);
Route::post('commodities/facility_reqs/post_approve/{id}',['permission'=>38,'uses'=>'Commodities\CommodityFacilityReqController@post_approve']);


Route::get('commodities/stock_status/balances',['permission'=>51,'uses'=>'Commodities\CommodityController@balances']);



# XL:
# http://www.maatwebsite.nl/laravel-excel/docs

#customer care routes
Route::get('customer_care/categories',['permission'=>39,'uses'=>'CustomerCare\CategoryController@index']);
Route::post('customer_care/categories/store',['permission'=>39,'uses'=>'CustomerCare\CategoryController@store']);
Route::get('customer_care/categories/edit/{edit_id}',['permission'=>39,'uses'=>'CustomerCare\CategoryController@edit']);
Route::post('customer_care/categories/update/{edit_id}',['permission'=>39,'uses'=>'CustomerCare\CategoryController@update']);

Route::get('customer_care/complaints/index',['permission'=>42,'uses'=>'CustomerCare\ComplaintController@index']);
Route::get('customer_care/complaints/create',['permission'=>40,'uses'=>'CustomerCare\ComplaintController@create']);
Route::post('customer_care/complaints/store',['permission'=>40,'uses'=>'CustomerCare\ComplaintController@store']);
Route::get('customer_care/complaints/show/{id}',['permission'=>42,'uses'=>'CustomerCare\ComplaintController@show']);
Route::get('customer_care/complaints/edit/{id}',['permission'=>40,'uses'=>'CustomerCare\ComplaintController@edit']);
Route::post('customer_care/complaints/update/{id}',['permission'=>40,'uses'=>'CustomerCare\ComplaintController@update']);


# Auth for sub-domains (repeat in AJAX too, if needed)
# header('Access-Control-Allow-Credentials: true');



}); //auth loop ends here






// ------------ Commodities Management Stuff goes here ----------------


Route::get('commodities_home', function () {
    return view('stock');
});

Route::resource('person', 'PersonController');

Route::resource('commodities', 'commoditiesController');
Route::resource('commodity_categories', 'commodity_categoriesController');
Route::resource('stock_requisition_header', 'stock_requisition_headerController');
Route::resource('stock_requisition_line_items', 'stock_requisition_line_itemsController');

Route::get('stock_approval/{id}', ['uses' => 'stock_requisition_headerController@approval']);
Route::get('stock_release',  ['uses' => 'stock_requisition_headerController@stock_release']);
Route::get('stock_received',  ['uses' => 'stock_requisition_headerController@stock_received']);
Route::get('stock_forecast',  ['uses' => 'stock_requisition_headerController@stock_forecast']);
Route::get('stock_out',  ['uses' => 'stock_requisition_headerController@stock_out']);
Route::get('stock_settings',  ['uses' => 'stock_requisition_headerController@stock_settings']);

// links from new menu
Route::get('stock_status',  ['uses' => 'stock_requisition_headerController@stock_status']);


Route::resource('receivestock', 'ReceiveStockController');
Route::resource('stock_adjustments', 'stock_adjustmentsController');
