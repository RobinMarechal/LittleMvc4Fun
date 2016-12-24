<?php
namespace Lib;
use App\User;

class Auth{

	static function login(\App\User $user)
	{
		\Lib\Session::start($user);
	}

	static function check()
	{
		return isset($_SESSION['auth']);
	}

	static function user()
	{
		return new User($_SESSION['auth']);
	}

	static function logout()
	{
		Session::destroy();
	}
}