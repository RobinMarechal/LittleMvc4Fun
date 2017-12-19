<?php
namespace Lib\Routing;
use function array_keys;
use function is_nan;
use function is_null;
use Lib\Request;
use Lib\Routing\Route;
use Lib\Exceptions\RouteException;
use function preg_match;

class Router{

	protected $routes = [];
	protected $request;

	function __construct(Request $request)
	{
		$this->request = $request;
		$this->mapRoutes();
	}

	protected function mapRoutes()
	{
		$this->routes = Route::getRouteList();
	}

	public function triggerRequestedRoute()
	{
		$routeKey = null;
		$routeValue = null;

		foreach($this->routes[$this->request->httpMethod] as $key => $value) {
			if (preg_match($key, $this->request->page) === 1){
				$routeKey = $key;
				$routeValue = $value;
				break;
			}
		}

//		dd($match, is_null($match));

		if(is_null($routeKey) || is_null($routeValue))
		{
			e404();
		}

		$route = $this->routes[$this->request->httpMethod][$routeKey];
		$route = new Route($this->request->page, $route);
		$this->request->route = $route;
		$route->trigger($this->request);
	}
}