<?php
namespace App\Middleware;
use Lib\Middleware;
use Lib\Redirect;

class RedirectIfAuthentificated extends Middleware{

	public function handle()
	{
		if(!empty($this->request->session->user))
			Redirect::back();
	}
}