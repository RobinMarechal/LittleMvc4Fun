<?php
namespace Lib;
use \App\User;

class Session{

	public $user;

	function __construct()
	{

	}

	static function initUser()
	{
		if(!array_key_exists('auth', $_SESSION))
		{
			return null;
		}

		$user = new User();

		if(array_key_exists('auth', $_SESSION))
		{
			$fields = array_diff(array_diff($user->fields, $user->hidden), $_SESSION['auth']);
			$user->init($_SESSION['auth']);
		}

		return $user;
	}

	static function destroy()
	{
		unset($_SESSION['auth']);
	}

	static function start(User $user)
	{
		$fields = array_diff($user->fields, $user->hidden);

		foreach ($fields as $f) {
			if($user->$f instanceOf \DateTime)
				$_SESSION['auth'][$f] = $user->$f->format('Y-m-d H:i:s');
			else
				$_SESSION['auth'][$f] = $user->$f;
		}
	}
}