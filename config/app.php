<?php

return [

	'debug' => true,

	/**
	 * If you don't want to use a template engine,
	 * this framework provides a global layout (../views/layout.php).
	 * If this value if 'false', don't suppress it.
	 * You can rename it, but you need to modify the two next attributes
	 * @true if you use a template engine (Twig, Blade...)
	 * @false if you want to use the global layout provided by this framework
	 */
	'template_engine' => false,

	/**
	 * The path where is located the layout
	 * if 'template_engine' is set at TRUE, this information is useless
	 */
	'layout_path' => '../views/template.php',

    'providers' => [

    ]


];