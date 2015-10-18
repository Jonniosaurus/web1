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

resource(
	'/examples/samples', // route URI 
	'sampleController', // controller to call.
	[
	// Named Routing (means the URI above can be changed.)
	'names' => [
		'index' => 'samples',		
		'edit' => 'samples.edit',
        'show' => 'samples.show',
 		'create' => 'samples.create',
        'store' => 'samples.store',
        'update' => 'samples.update',
        'destroy' => 'samples.destroy'
	],
	// only provide for the named routes above	
]);

get('/', ['as' => 'home', 'uses' => 'pageController@index']);
get('{slug}', ['as' => 'page', 'uses' => 'pageController@show']);
get('{slug}/edit', ['as' => 'page.edit', 'uses' => 'pageController@edit']);
get('{slug}/edit/{contentId}', ['as' => 'page.edit.content', 'uses' => 'pageController@editContent']);
get('{slug}/create', ['as' => 'page.create', 'uses' => 'pageController@create']);
delete('/{id}', 'pageController@destroy');
patch('/{slug}', 'pageController@update');
post('/{slug}', 'pageController@store');

//get('/examples/{slug}', ['as' => 'show', 'uses' => 'sampleController.show']);

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@postLogin']);
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
//Route::get('auth/register', 'Auth\AuthController@getRegister');
//Route::post('auth/register', ['as' => 'auth.register', 'uses' => 'Auth\AuthController@postRegister']);

