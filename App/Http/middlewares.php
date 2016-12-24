<?php

return [
	'auth' 		=> App\Middleware\Authenticate::class,
	'guest' 	=> App\Middleware\RedirectIfAuthentificated::class,
];