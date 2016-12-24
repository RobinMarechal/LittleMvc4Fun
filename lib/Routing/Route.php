<?php
namespace Lib\Routing;
use Lib\Routing\Router;
use Lib\Request;

class Route{

	static $routes = ['get' => [], 'post' => [], 'delete' => [], 'patch' => [], 'put' => []];
	public $page;
	public $class;
	public $function;
	public $closure;
	public $middleware;

	function __construct($page, $route)
	{
		$this->page = $page;

		if(array_key_exists('middleware', $route))
		{
			$this->middleware = $route['middleware'];
		}

		if(array_key_exists('closure', $route))
		{
			$this->closure = $route['closure'];
		}
		else
		{
			$this->class = $route['controller'];
			$this->function = $route['function'];
		}
	}

	public function trigger(Request $request)
	{
		if(isset($request->route->middleware))
		{
			$middList = require "../App/Http/middlewares.php";
			foreach ($request->route->middleware as $m) 
			{
				$middNamespace = $middList[$m];
				$middleware = new $middNamespace($request);
				$middleware->handle();
			}
		}

		if(isset($request->route->closure))
		{
			$closure = $request->route->closure;
			$closure();
		}
		else
		{
			$classNamespace = "App\Controller\\$this->class";
			$classPath = '../App/Http/Controllers/'.$this->class.'.php';

			if(!is_file($classPath))
			{
				echo "<pre>";
					throw new \Exception("Controller '$this->class' does not exist at location '$classPath'");
			}

			$controller = new $classNamespace($request);
			$function = $this->function;
			$controller->$function();
		}
	}

	static function get($page, $call, array $array=[])
	{
		self::$routes['get'][$page] = self::addRoute($call, $array);
	}

	static function put($page, $call, array $array=[])
	{
		self::$routes['put'][$page] = self::addRoute($call, $array);
	}

	static function post($page, $call, array $array=[])
	{
		self::$routes['post'][$page] = self::addRoute($call, $array);
	}

	static function patch($page, $call, array $array=[])
	{
		self::$routes['patch'][$page] = self::addRoute($call, $array);
	}

	static function delete($page, $call, array $array=[])
	{
		self::$routes['delete'][$page] = self::addRoute($call, $array);
	}

	static function addRoute($call, array $array=[])
	{
		$route = [];

		if(!empty($array))
		{
			if(array_key_exists('middleware', $array))
			{
				$middlewares = $array['middleware'];
				if(!is_array($array['middleware']))
				{
					$middlewares = array_map('trim', explode(',', $array['middleware']));
				}

				$route['middleware'] = $middlewares;
			}
		}

		if(is_callable($call))
		{
			$route['closure'] = $call;
		}
		else
		{
			$call = explode('@', $call);
			$route['controller'] = $call[0];
			$route['function'] = $call[1];
		}

		return $route;
	}

	static function getRouteList()
	{
		return self::$routes;
	}
}