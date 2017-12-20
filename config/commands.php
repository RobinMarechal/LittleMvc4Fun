<?php

return [
	'commands' => [
		'test'  => [
			'class'       => Command\TestCommand::class,
			'description' => 'test',
		],
		'foo'   => [
			'class'       => Command\FooCommand::class,
			'description' => 'foo',
		],
		'tests' => [
			'class'       => Command\TestsCommand::class,
			'description' => 'running tests',
		],
		'help'  => [
			'class'       => Command\HelpCommand::class,
			'description' => 'show help',
		],
		'serve' => [
			'class'       => Command\ServeCommand::class,
			'description' => 'server',
		],
		'make'  => [
			'class'       => Command\MakeCommand::class,
			'description' => 'create things...',
		],
	],
];