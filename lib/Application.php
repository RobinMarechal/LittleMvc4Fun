<?php

namespace Lib;

use Lib\routing\Router;

class Application
{

	/**
	 * @var Application
	 */
	private static $_instance;

	/**
	 * @var Request
	 */
	private static $request;

	/**
	 * @var Router
	 */
	private static $router;


	/**
	 * @return mixed
	 */
	public static function getInstance ()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new Application();
		}

		return self::$_instance;
	}


	private function __construct ()
	{

	}


	public static function loadForConsole ()
	{
		require_once("vendor/autoload.php");
		self::require_wildcard("commands/*.php");
		self::require_wildcard("lib/*.php");
		self::require_wildcard("lib/*/*.php");
		self::require_wildcard("app/*.php");
		self::require_wildcard("app/http/controllers/*.php");
		self::require_wildcard("tests/unit/*/*.php");
		self::require_wildcard("tests/feature/*/*.php");
	}


	public static function load ()
	{
		require_once("../vendor/autoload.php");
		self::require_wildcard("../lib/*.php");
		self::require_wildcard("../lib/*/*.php");
		self::require_wildcard("../app/*.php");
		self::require_wildcard("../app/http/controllers/*.php");
		self::require_wildcard("../app/http/middlewares/*.php");
		self::require_wildcard("../routes/*.php");
	}


	static function start ()
	{
		self::load();
		self::registerProviders();

		self::$request = new Request();
		self::$router = new routing\Router(self::$request);
		self::$router->triggerRequestedRoute();
	}


	protected static function require_wildcard ($w)
	{
		foreach (glob($w) as $filename) {
			require_once $filename;
		}
	}


	protected static function registerProviders ()
	{
		$providers = config('app.providers');
		foreach ($providers as $provider) {
			$instance = new $provider();
			self::registerProvider($instance);
		}
	}


	protected static function registerProvider (ServiceProvider $provider)
	{
		$provider->register();
	}


	public static function getRequest ()
	{
		return self::$request;
	}


	public static function getRouter ()
	{
		return self::$router;
	}
}