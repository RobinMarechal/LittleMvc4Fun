<?php

namespace Lib\Console;

use Lib\Application;

class Console
{
	public static $argc;
	public static $argv;

	public static function run ($argc, $argv)
	{
		self::$argc = $argc;
		self::$argv = $argv;

		require "lib/Application.php";

		/**
		 * Load the framework
		 */
		Application::loadForConsole();

		ConsoleDispatcher::dispatch($argc, $argv);
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