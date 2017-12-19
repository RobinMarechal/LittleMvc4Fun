<?php

namespace Lib\Routing;

use function array_filter;
use function array_key_exists;
use function array_keys;
use function call_user_func_array;
use Closure;
use function extract;
use Lib\Routing\Router;
use Lib\Request;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use ReflectionClass;
use ReflectionMethod;
use function substr;

class Route
{

	const PARAM_REGEX = '/\{[\w\d]+\}/';
	const PARAM_REGEX_REPLACEMENT = "[\w\d_\-\s\+]+";

	static $routes = ['get' => [], 'post' => [], 'delete' => [], 'patch' => [], 'put' => []];
	public $page;
	public $class;
	public $function;
	public $closure;
	public $middleware;
	public $params;


	function __construct ($page, $route)
	{
		$this->page = $page;

		if (array_key_exists('middleware', $route)) {
			$this->middleware = $route['middleware'];
		}

		if (array_key_exists('closure', $route)) {
			$this->closure = $route['closure'];
		}
		else {
			$this->class = $route['controller'];
			$this->function = $route['function'];
		}

		if(array_key_exists('params', $route))
		{
			$this->params = $route['params'];
		}
	}


	public function trigger (Request $request)
	{
		if (isset($request->route->middleware)) {
			$middList = require "../App/Http/middlewares.php";
			foreach ($request->route->middleware as $m) {
				$middNamespace = $middList[ $m ];
				$middleware = new $middNamespace($request);
				$middleware->handle();
			}
		}

		$params = [];
		$urlParts = explode('/', $this->page);

		foreach ($this->params as $pos => $name) {
			$params[$name] = $urlParts[$pos];
		}

		if (isset($request->route->closure)) {
			$closure = $request->route->closure;
			call_user_func_array($closure, $params);
		}
		else {
			$classNamespace = "App\\Controller\\$this->class";
			$classPath = '..\\app\\Http\\Controllers\\' . $this->class . '.php';

			if (!is_file($classPath)) {
				echo "<pre>";
				throw new \Exception("Controller '$this->class' does not exist at location '$classPath'");
			}

			$class = new ReflectionClass($classNamespace);
			$instance = $class->newInstanceArgs([$request]);
			$method = new ReflectionMethod($classNamespace, $this->function);
			$method->invokeArgs($instance, $params);
		}
	}


	private static function registerRouteForMethod ($method, $pattern, $call, array $array = [])
	{
		$valueRoute = self::createRoute($pattern, $call, $array);

		$pattern = preg_replace(Route::PARAM_REGEX, Route::PARAM_REGEX_REPLACEMENT, $pattern);
		$pattern = preg_replace('/\//', '\\/', $pattern);
		$pattern = "/^$pattern$/";

		self::$routes[ $method ][ $pattern ] = $valueRoute;
	}


	static function get ($pattern, $call, array $array = [])
	{
		self::registerRouteForMethod('get', $pattern, $call, $array);
	}


	static function put ($pattern, $call, array $array = [])
	{
		self::registerRouteForMethod('put', $pattern, $call, $array);
	}


	static function post ($pattern, $call, array $array = [])
	{
		self::registerRouteForMethod('post', $pattern, $call, $array);
	}


	static function patch ($pattern, $call, array $array = [])
	{
		self::registerRouteForMethod('patch', $pattern, $call, $array);
	}


	static function delete ($pattern, $call, array $array = [])
	{
		self::registerRouteForMethod('delete', $pattern, $call, $array);
	}


	static function createRoute ($pattern, $call, array $array = [])
	{
		$route = [];
		$paramNames = [];
		$route['params'] = [];

		$urlParts = explode('/', $pattern);

		for ($i = 0; $i < count($urlParts); $i++) {
			$p = $urlParts[ $i ];
			if (!preg_match(self::PARAM_REGEX, $p)) {
				continue;
			}

			$route['params'][$i] = substr($p, 1, strlen($p) - 2);
		}

		if (!empty($array)) {
			if (array_key_exists('middleware', $array)) {
				$middlewares = $array['middleware'];
				if (!is_array($array['middleware'])) {
					$middlewares = array_map('trim', explode(',', $array['middleware']));
				}

				$route['middleware'] = $middlewares;
			}
		}

		if (is_callable($call)) {
			$route['closure'] = $call;
		}
		else {
			$call = explode('@', $call);
			$route['controller'] = $call[0];
			$route['function'] = $call[1];
		}

		return $route;
	}


	static function getRouteList ()
	{
		return self::$routes;
	}
}