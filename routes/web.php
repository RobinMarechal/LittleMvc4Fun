<?php
use Lib\routing\Route;

Route::get('/', function() {
	return view('home');
});

Route::get('coucou', function() {
	return view('maxence');
});

Route::get('test', 'UserController@test', ['middleware' => 'auth']);

Route::get('abc', function(){
	dd('abc');
});

Route::get('abc/{id}', function($id){
	return view('testtt', compact('id'));
});


Route::get('abc/{id}/truc', function($id){
	dd("abc/".$id);
});


Route::get('abc/{id}/truc/{id2}/{id5}', 'UserController@test3');