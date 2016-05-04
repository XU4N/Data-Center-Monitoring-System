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

//testing intelligent predict
Route::get('intelligent', 'IntelligentPredictionController@index');

Route::get('isettings', 'IntelligentPredictionController@index');

Route::get('testing', 'ReadingsController@test');

Route::get('sms', function() {
	$reading = App\Reading::find(4);
	\Event::fire(new App\Events\AbnormalReadingWasTaken($reading));
	return view('sms');
});

Route::get('/', 'DashboardController@index');

Route::get('dashboard', 'DashboardController@index');

Route::get('forms', 'FormsController@index');
Route::post('forms', 'FormsController@store');
Route::get('sensor', 'FormsController@readSensorData');

Route::resource('zones', 'ZonesController');

Route::resource('readings', 'ReadingsController');

Route::resource('profile', 'ProfileController',
                ['only' => ['index', 'update']]);

Route::resource('parameters', 'ParametersController');

Route::resource('manage_accounts', 'ManageAccountsController',
                ['only' => ['index', 'store', 'update', 'destroy']]);

//Thresholds routes
Route::resource('thresholds', 'ThresholdController',
                ['only' => ['index', 'store', 'update']]);

Route::get('logs', 'LogController@index');


Route::get('statistics', 'StatisticsController@index');
Route::post('statistics', 'StatisticsController@view');

Route::get('reports', 'ReportsController@index');
Route::post('reports', 'ReportsController@search');
Route::post('reports/export', 'ReportsController@export');

Route::resource('alerts', 'AlertsController');

Route::get('details{zone_id}', 'ZoneDetailsController@index');

// Authentication routes
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');