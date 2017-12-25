<?php

return [
	'auth' 		=> App\Middleware\Authenticate::class,
	'guest' 	=> App\Middleware\RedirectIfAuthenticated::class,
];