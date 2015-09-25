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
 
get('/', ['as' => 'home', 'uses' => 'pageController@index']);
get('{page}', ['as' => 'page', 'uses' => 'pageController@show']);
get('{page}/edit', ['as' => 'page.edit', 'uses' => 'pageController@edit']);
patch('/{page}', 'pageController@update');

$router->resource(
	'projects', // route URI 
	'projectController', // controller to call.
	[
	// Named Routing (means the URI above can be changed.)
	'names' => [
		'index' => 'projects',
		'show' => 'projects.show',
		'edit' => 'projects.edit',
 		'create' => 'projects.create',
	],
	// only provide for the named routes above
	'only' => ['index','show','edit','create']
]);
//get('projects/{project}', 'ProjectController@show');
//get('projects/{project}/edit', 'ProjectController@edit');