<?php
namespace Lib\Routing;
use Lib\Request;
use Lib\Routing\Route;
use Lib\Exceptions\RouteException;

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
		require_once("../App/Http/routes.php");

		$this->routes = Route::getRouteList();
	}

	public function triggerRequestedRoute()
	{
		if(!array_key_exists($this->request->page, $this->routes[$this->request->httpMethod]))
		{
			e404();
		}

		$route = $this->routes[$this->request->httpMethod][$this->request->page];
		$route = new Route($this->request->page, $route);
		$this->request->route = $route;
		$route->trigger($this->request);
	}
}