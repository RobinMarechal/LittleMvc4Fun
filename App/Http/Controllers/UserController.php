<?php
namespace App\Controller;
use Lib\Request;
use Lib\Controller;
use Lib\Database as DB;
use App\User;

class UserController extends Controller{

	public function test()
	{
		$var = "bjr";
		return view('bonjour', ['var' => $var]);
	}
}