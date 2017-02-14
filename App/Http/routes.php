<?php
use Lib\Routing\Route;

Route::get('/', function() {
	return view('home'); 
});

Route::get('coucou', function() {
	return view('maxence'); 
});

Route::get('test', 'UserController@test', ['middleware' => 'auth']);