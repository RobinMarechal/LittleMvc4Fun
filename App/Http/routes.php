<?php
use Lib\Routing\Route;

Route::get('/', function() {
	return view('home'); 
});