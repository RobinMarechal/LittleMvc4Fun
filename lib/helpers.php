<?php
use Lib\View;
use Lib\Redirect;

function dd($data=false, $die=true)
{
	if($data === false)
		die();

	echo '<pre>';
	if(is_array($data) || is_object($data))
		print_r($data);
	else
		var_dump($data);
	echo '</pre>';

	if($die)
		die();
}

function e404()
{
	if(is_file('../views/errors/404.php'));
	{
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
		View::make('errors.404');
		die();
	}
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
	die();
}

function view($path, $data=[])
{
	return View::make($path, $data);
}

function redirect($url)
{
	return Redirect::to($url);
}