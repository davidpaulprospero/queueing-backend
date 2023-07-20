<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

# -----------------------------------------------------------
# LOGIN
# -----------------------------------------------------------
# login
Route::get('/', 'Common\LoginController@login');
Route::get('login', 'Common\LoginController@login')->name('login');
Route::post('login', 'Common\LoginController@checkLogin');
Route::get('logout', 'Common\LoginController@logout')->name('logout');
# login - {provider: google}
Route::get('login/{provider}', 'Common\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Common\LoginController@handleProviderCallback');


# -----------------------------------------------------------
# CLEAN CACHE
# -----------------------------------------------------------
Route::get('clean', function () {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    // \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('clear-compiled');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    dd('Cached Cleared');
});


# -----------------------------------------------------------
# COMMON 
# -----------------------------------------------------------
Route::prefix('common')
    ->namespace('Common')
    ->group(function() { 
	# switch language
	Route::get('language/{locale?}', 'LanguageController@index');

	# cron job
	Route::get('jobs/sms', 'CronjobController@sms');

	# display 
	Route::get('display','DisplayController@display');  
	Route::post('display1', 'DisplayController@display1');  
	Route::post('display2','DisplayController@display2');  
	Route::post('display3','DisplayController@display3'); 
	Route::post('display4','DisplayController@display4'); 
	Route::post('display5','DisplayController@display5'); 

	# -----------------------------------------------------------
	# AUTHORIZED COMMON 
	# -----------------------------------------------------------
	Route::middleware('auth')
	    ->group(function() { 
		#message notification
		Route::get('message/notify','NotificationController@message'); 
		# message  
		Route::get('message','MessageController@show'); 
		Route::post('message','MessageController@send'); 
		Route::get('message/inbox','MessageController@inbox'); 
		Route::post('message/inbox/data','MessageController@inboxData'); 
		Route::get('message/sent','MessageController@sent'); 
		Route::post('message/sent/data','MessageController@sentData'); 
		Route::get('message/details/{id}/{type}','MessageController@details'); 
		Route::get('message/delete/{id}/{type}','MessageController@delete');  
		Route::post('message/attachment','MessageController@UploadFiles'); 

		# profile 
		Route::get('setting/profile','ProfileController@profile');
		Route::get('setting/profile/edit','ProfileController@profileEditShowForm');
		Route::post('setting/profile/edit','ProfileController@updateProfile');
	});
});

# -----------------------------------------------------------
# AUTHORIZED
# -----------------------------------------------------------
Route::group(['middleware' => ['auth']], function() { 

	# -----------------------------------------------------------
	# ADMIN
	# -----------------------------------------------------------
	Route::prefix('admin')
	    ->namespace('Admin')
	    ->middleware('roles:admin')
	    ->group(function() { 
		# home
		Route::get('/', 'HomeController@home');

		# user 
		Route::get('user', 'UserController@index');
		Route::post('user/data', 'UserController@userData');
		Route::get('user/create', 'UserController@showForm');
		Route::post('user/create', 'UserController@create');
		Route::get('user/view/{id}','UserController@view');
		Route::get('user/edit/{id}','UserController@showEditForm');
		Route::post('user/edit','UserController@update');
		Route::get('user/delete/{id}','UserController@delete');

		# Department routes
		Route::get('department', 'DepartmentController@index');
		Route::get('department/create', 'DepartmentController@showForm');
		Route::post('department/create', 'DepartmentController@create');
		Route::get('department/edit/{id}', 'DepartmentController@showEditForm');
		Route::post('department/edit', 'DepartmentController@update');
		Route::get('department/delete/{id}', 'DepartmentController@delete');

		# Transaction Type routes
		//Route::get('department/transaction', 'DepartmentController@showTransactionTypes')->name('admin.department.transaction');
		Route::delete('department/transaction/delete/{transactionType}', 'DepartmentController@destroyTransaction')->name('admin.department.transaction.destroy');
		Route::get('department/transaction/', 'DepartmentController@createTransaction')->name('admin.department.transaction.create');
		Route::post('department/transaction/create', 'DepartmentController@storeTransaction')->name('admin.department.transaction.store');
		// Route::get('department/{departmentId}/transaction/delete/{transactionId}', 'DepartmentController@deleteTransactionType')->name('admin.department.transaction.delete');


		# counter
		Route::get('counter','CounterController@index');
		Route::get('counter/create','CounterController@showForm');
		Route::post('counter/create','CounterController@create');
		Route::get('counter/edit/{id}','CounterController@showEditForm');
		Route::post('counter/edit','CounterController@update');
		Route::get('counter/delete/{id}','CounterController@delete');

		# sms
		Route::get('sms/new', 'SmsSettingController@form');
		Route::post('sms/new', 'SmsSettingController@send');
		Route::get('sms/list', 'SmsSettingController@show');
		Route::post('sms/data', 'SmsSettingController@smsData');
		Route::get('sms/delete/{id}', 'SmsSettingController@delete');
		Route::get('sms/setting', 'SmsSettingController@setting');
		Route::post('sms/setting', 'SmsSettingController@updateSetting');

		# token
		Route::get('token/setting','TokenController@tokenSettingView'); 
		Route::post('token/setting','TokenController@tokenSetting'); 
		Route::get('token/setting/delete/{id}','TokenController@tokenDeleteSetting');
		Route::get('token/auto','TokenController@tokenAutoView'); 
		Route::post('token/auto','TokenController@tokenAuto'); 
		Route::get('token/current','TokenController@current');
		Route::get('token/refresh', 'TokenController@refresh')->name('token.refresh');
		Route::get('token/report','TokenController@report');  
		Route::post('token/report/data','TokenController@reportData');  
		Route::get('token/performance','TokenController@performance');  
		Route::get('token/create','TokenController@showForm');
		Route::post('token/create','TokenController@create');
		Route::post('token/print', 'TokenController@viewSingleToken');
		Route::get('token/complete/{id}','TokenController@complete');
		Route::get('token/stoped/{id}','TokenController@stoped');
		Route::get('token/recall/{id}','TokenController@recall');
		Route::get('token/delete/{id}','TokenController@delete');
		Route::post('token/transfer','TokenController@transfer'); 

		# setting
		Route::get('setting','SettingController@showForm'); 
		Route::post('setting','SettingController@create');  
		Route::get('setting/display','DisplayController@showForm');  
		Route::post('setting/display','DisplayController@setting');  
		Route::get('setting/display/custom','DisplayController@getCustom');  
		Route::post('setting/display/custom','DisplayController@custom');  

		// Route::get('setting/display/video', 'API\VideosController@showForm');



	
	});
	Route::prefix('admin')
		->middleware('roles:admin')
		->group(function() { 

	Route::get('setting/display/video', 'API\VideosController@showForm');
	Route::get('setting/display/video/update/{id}', 'API\VideosController@showUpdateForm')->name('showUpdateForm');
	Route::post('admin/setting/display/video/update/{id}', 'API\VideosController@update')->name('updateVideo');


	Route::post('admin/setting/display/video/uploadVideo', 'API\VideosController@store')->name('uploadVideo');
	Route::delete('admin/setting/display/video/delete/{id}', 'API\VideosController@destroy')->name('deleteVideo');
	Route::post('admin/setting/display/video/arrange', 'API\VideosController@showVids')->name('arrangeVideos');
	});

	# -----------------------------------------------------------
	# OFFICER
	# -----------------------------------------------------------
	Route::prefix('officer')
	    ->namespace('Officer')
	    ->middleware('roles:officer')
	    ->group(function() { 
		# home
		Route::get('/', 'HomeController@home');
		# user
		Route::get('user/view/{id}', 'UserController@view');

		# token
		Route::get('token','TokenController@index');
		Route::post('token/data','TokenController@tokenData');  
		Route::get('token/current','TokenController@current');
		Route::get('token/complete/{id}','TokenController@complete');
		Route::get('token/recall/{id}','TokenController@recall');
		Route::get('token/stoped/{id}','TokenController@stoped');
		Route::post('token/print', 'TokenController@viewSingleToken');
	});

	# -----------------------------------------------------------
	# RECEPTIONIST
	# -----------------------------------------------------------
	Route::prefix('receptionist')
    ->namespace('Receptionist')
    ->group(function() { 
    # home
	
    Route::get('/', 'TokenController@tokenAutoView')->middleware('roles:receptionist'); 

    # receptionist homepage
	Route::get('/receptionist', function () {
		return view('receptionist.home');
	})->middleware('allow.guest.access');
	
    # token
    Route::get('token/auto', 'TokenController@tokenAutoView')->middleware('roles:receptionist'); 
    Route::post('token/auto', 'TokenController@tokenAuto')->middleware('roles:receptionist'); 
    Route::get('token/create', 'TokenController@showForm')->middleware('roles:receptionist');
    Route::post('token/create', 'TokenController@create')->middleware('roles:receptionist');
    Route::get('token/current', 'TokenController@current')->middleware('roles:receptionist'); 
    Route::post('token/print', 'TokenController@viewSingleToken')->middleware('roles:receptionist');
});

});