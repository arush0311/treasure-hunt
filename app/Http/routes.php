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

Route::get('/','UserController@enter');

Route::get('/login','UserController@loginpage');

Route::post('/apiv1/login/', 'UserController@login');

Route::post('/apiv1/register/','UserController@register');

Route::get('/verify/{email}/{token}','UserController@verify');

Route::match(['get', 'post'],'/apiv1/forgot_password/{email?}/{token?}','UserController@forgot_password');

Route::post('/apiv1/change_password/','UserController@change_password');

Route::post('/apiv1/edit_profile/','UserController@edit_profile');

Route::get('/apiv1/get_profile/','UserController@get_profile');

Route::post('/apiv1/logout/','UserController@logout');

Route::get('/apiv1/search_events/{search_string?}','EventController@search_events');

Route::get('/apiv1/show_event/{event_slug}','EventController@show_event');

Route::post('/apiv1/create_event/','EventController@create_event');

Route::post('/apiv1/register/{event_slug}','EventController@register');

Route::get('/apiv1/show_test/{event_slug}/{round_no}/','TestController@show_test');

Route::post('apiv1/submit_test/{event_slug}/{round_no}/','TestController@submit_test');

