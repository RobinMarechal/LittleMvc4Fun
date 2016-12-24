<?php
namespace Lib;

class Request{

	/**
	 * HTTP METHOD (get/post/put/patch/delete/.....)
	 */
	public $httpMethod;

	/**
	 * The requested URI
	 */
	public $url;

	/**
	 * 'path' value in the url ( http://[HOST]/[PATH]?[PARAMS] ) 
	 */
	public $page;

	/**
	 * URL parameterd
	 */
	public $parameters;

	function __construct()
	{		
		$request_uri = $_SERVER['REQUEST_URI'];

		$oldPrevUrl = $this->url;

		if(array_key_exists('previous_url', $_SESSION))
		{
			$currUrl = $_SESSION['current_url'];
			$prevUrl = $_SESSION['previous_url'];
			$oldPrevUrl = $prevUrl;
			$_SESSION['previous_url'] = $currUrl;
		}

		$_SESSION['current_url'] = $request_uri;

		if($_SESSION['current_url'] == $_SESSION['previous_url'])
			$_SESSION['previous_url'] = $oldPrevUrl;


		$this->previousUrl = $_SESSION['previous_url'];
		$this->currentUrl = $_SESSION['current_url'];

		// A voir si besoin...
		// foreach ($GLOBALS as $g) 
		// {
		// 	foreach ($g as $k => $v) 
		// 	{
		// 		$this->$k = $v;
		// 	}
		// }

		$this->url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		$this->httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

		$this->parameters = $_GET;

		$this->page = parse_url($this->url)['path'];
		if($this->page !== '/')
			$this->page = trim($this->page, '/');

		if($this->page === null)
			$this->page = '/';

		if(strtolower($this->httpMethod) != 'get')
		{
			$method = strtolower($this->httpMethod);
			$this->$method = new \stdClass;
			$str_json = file_get_contents('php://input');
			$json_decoded = json_decode($str_json);
			if($json_decoded)
				foreach ($json_decoded as $k => $v) {
					$this->$method->$k = $v;
				}
		}

		$this->get = new \stdClass;
		foreach ($_GET as $k => $v) {
			$this->get->$k = $v;
		}

		$this->session = new Session();
		$this->session->user = Session::initUser();
	}

	public function all()
	{
		$method = $this->httpMethod;
		$array = (array) $this->$method;

		if(strtolower($method) != 'get')
		{
			$array = array_merge($array, (array) $this->get);
		}

		return (object) $array;
	}

	public function getData($method, $args=false)
	{
		$asked = $this->$method;
		if($args === false)
		{
			return (array) $asked;
		}
		else if(is_array($args))
		{
			$array = [];
			foreach ($args as $k => $v) {
				$array[$k] = $asked->$v;
			}

			return $array;
		}
		else
		{
			return $asked->$args;
		}
	}

	public function get($args=false)
	{
		return $this->getData('get', $args);
	}

	public function put($args=false)
	{
		return $this->getData('put', $args);
	}

	public function patch($args=false)
	{
		return $this->getData('path', $args);
	}

	public function post($args=false)
	{
		return $this->getData('post', $args);
	}

	public function ajax()
	{
		return strpos(strtolower($_SERVER['HTTP_CONTENT_TYPE']), 'json') !== false;
	}

	public function __call($function, $args)
	{
		throw new Exception("Call to undifined function '$function' in /lib/Request.php", 1);
	}
}