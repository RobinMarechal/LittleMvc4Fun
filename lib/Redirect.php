<?php
namespace Lib;
use Lib\Request;

class Redirect{

	protected $request;

	function __construct()
	{
		$this->request = new Request();
	}

	static function to($url)
	{
		if($url[0] != '/')
			$url = '/'.$url;

		return header("location: $url");
	}

	static function back()
	{
		$obj = new self();
		return self::to($obj->request->previousUrl);
	}
}