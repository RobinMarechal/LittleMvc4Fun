<?php
namespace Lib;
use Lib\Exceptions\ControllerException;

abstract class Controller{

	protected $request;

	function __construct(Request $request)
	{
		$this->request = $request;
	}

	function __call($func, $params=false)
	{
		$class = $this->request->route->class;
		echo '<pre>';
		throw new ControllerException("Method '$func' not found in class '".$class."'.");
	}
}