<?php
namespace Lib;

class Middleware{

    /**
     * @var \Lib\Request
     */
	protected $request;

	function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		return true;
	}
}