<?php
namespace Lib;

class Application{

	static function start()
	{
		self::require_wildcard("../lib/*.php");
		self::require_wildcard("../lib/*/*.php");
		self::require_wildcard("../App/*.php");
		self::require_wildcard("../App/Http/Controllers/*.php");
		self::require_wildcard("../App/Http/Middlewares/*.php");

		$request = new Request();
		$router = new Routing\Router($request);
		$router->triggerRequestedRoute();
	}

	protected static function require_wildcard($w) 
	{
	    foreach (glob($w) as $filename)
	    {
	        require_once $filename;
	    }
	}
}