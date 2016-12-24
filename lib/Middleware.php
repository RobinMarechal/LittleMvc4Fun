<?php
namespace Lib;
use Lib\Request;

class Middleware{

	protected $request;

	function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		return 1;
	}
}