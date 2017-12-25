<?php
namespace App\Middleware;
use Lib\Middleware;
use Lib\Redirect;

class Authenticate extends Middleware{

	public function handle()
	{
		if(empty($this->request->session->user))
		{
			return Redirect::back();
		}
	}
}