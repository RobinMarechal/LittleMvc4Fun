<?php

use Lib\View;
use Lib\Redirect;

function dd (...$data)
{
	echo '<pre>';
	foreach ($data as $item) {
		if (is_array($item) || is_object($item)) {
			print_r($item);
		}
		else {
			var_dump($item);
		}
		echo '<br/>';
	}
	echo '</pre>';

	die();
}

function e404 ()
{
	if (is_file('../views/errors/404.php')) ;
	{
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
		View::make('errors.404');
		die();
	}
	header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
	die();
}

function view ($path, $data = [])
{
	return View::make($path, $data);
}

function redirect ($url)
{
	return Redirect::to($url);
}

function config ($configPath)
{
	$steps = explode('.', $configPath);

	$base = include("../config/$steps[0].php");

	for ($i = 1; $i < count($steps); $i++) {
		$base = $base[ $steps[ $i ] ];
	}

	return $base;
}

function firstLetterUpperCase ($str)
{
	return strtoupper(substr($str, 0, 1)) . substr($str, 1);
}

function camelCase ($str, $firstCapital = true)
{
	$parts = explode('_', $str);
	$res = $parts[0];


	if ($firstCapital) {
		$res = firstLetterUpperCase($res);
	}

	for ($i = 1; $i < count($parts); $i++) {
		$p = $parts[ $i ];
		$res .= firstLetterUpperCase($p);
	}

	return $res;
}

function singular ($str)
{
	if (ends_with($str, 'ies')) {
		return substr($str, 0, strlen($str) - 3) . 'y';
	}
	else if (ends_with($str, 's')) {
		return substr($str, 0, strlen($str) - 1);
	}

	return $str;
}

function plural ($str)
{
	$len = strlen($str);
	$last = $str[ -1 ];
	$base = substr($str, 0, $len - 1);

	if ($last == 's') {
		return $str;
	}

	if ($last == 'y') {
		return $base . 'ies';
	}

	return $str . 's';
}