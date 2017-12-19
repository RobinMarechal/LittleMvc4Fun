<?php
namespace Lib;

class Console
{
	public static function run ($argc, $argv)
	{
		self::$argc = $argc;
		self::$argv = $argv;

		require "lib/Application.php";

		/**
		 * Load the framework
		 */
		Application::loadForConsole();


	}


	public static function debug (...$data)
	{
		foreach ($data as $item) {
			if (is_array($item) || is_object($item)) {
				print_r($item);
			}
			else {
				var_dump($item);
			}
		}

		die();
	}
}