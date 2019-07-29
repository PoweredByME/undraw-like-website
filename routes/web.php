<?php

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

Route::get('/', function () {return redirect('/calendar');});
Route::get('/admin', function(){return redirect('/admin/home');});
Route::get('/admin/home/{user_id}', 'AdminController@show')->middleware('admin');
Route::get('/admin/calendar/{user_id}', 'AdminController@calendar')->middleware('admin');
Route::post('/admin/calendar/csv/{user_id}', 'AdminController@calendarImportFromCSV')->middleware('admin');
Route::post('/admin/calendar/agenda/{user_id}', 'AdminController@createAgenda')->middleware('admin');
Route::delete('/admin/calendar/agenda/{agenda_id}/{user_id}', 'AdminController@deleteAgenda')->middleware('admin');
Route::patch('/admin/calendar/agenda/edit/{agenda_id}/{user_id}', 'AdminController@editAgenda')->middleware('admin');

Route::get('/admin/illustrations/{user_id}/{page}', 'AdminController@illustrations')->middleware('admin');
Route::post('/admin/illustrations/csv/{user_id}', 'AdminController@illustrationImportFromCSV')->middleware('admin');
Route::post('/admin/illustrations/{user_id}', 'AdminController@createIllustration')->middleware('admin');
Route::delete('/admin/illustrations/{illustration_id}/{user_id}', 'AdminController@deleteIllustration')->middleware('admin');
Route::post('/admin/illustration/catagory/{user_id}', 'AdminController@addIllustrationCatagory')->middleware('admin');
Route::delete('/admin/illustration/catagory/{catagory_id}/{user_id}', 'AdminController@deleteIllustrationCatagory')->middleware('admin');
Route::patch('/admin/illustration/edit/{illustrations_id}/{user_id}', 'AdminController@editIllustration')->middleware('admin');


Route::get('/calendar', function(){return view('myCalendar');});
Route::get('/calendar/week/{date}', 'CalendarController@getWeek');
Route::get('/calendar/month/{date}', 'CalendarController@getMonth');
Route::get('/agenda/{slug}', 'CalendarController@getAgenda');
Route::get('/agenda/search/{search}', 'CalendarController@searchAgenda');

Route::get('/assets', function(){return view('Assets');});

Route::get('/assets/illustration/{number_of_items}', 'IllustrationController@getIllustrations');
Route::get('/illustration/{slug}', 'IllustrationController@showIllustration');
Route::post('/illustration/{slug}', 'IllustrationController@fetchIllustration');
Route::get('/illustration/search/{search}', 'IllustrationController@searchIllustration');
Route::post('/illustration/svg/fetch', 'IllustrationController@getSVG');




Route::group(['prefix' => 'admin'], function () {
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');
});
