<?php

namespace Lib\Console;

use function array_key_exists;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class ConsoleDispatcher
{
	public static function dispatch ($argc, $argv)
	{
		$commandsConfig = include('config/commands.php');
		$commandAliases = $commandsConfig['commands'];

		if ($argc < 2) {
			print("Please enter a command.");

			return;
		}

		$commandParts = explode(':', $argv[1]);


		$commandName = $commandParts[0];
		$subcommand = 'defaultCommand';

		if (isset($commandParts[1])) {
			$subcommand = $commandParts[1];
		}


		if (!array_key_exists($commandName, $commandAliases)) {
			print("This command doesn't exists.");

			return;
		}

		$alias = $commandAliases[ $commandName ]['class'];

		$class = new ReflectionClass($alias);
		$instance = $class->newInstance();
		try {
			$method = new ReflectionMethod($alias, $subcommand);
		} catch (ReflectionException $e) {
			print("The subcommand does not exist.\n");

			return;
		}

		$params = [];

		for ($i = 2; $i < $argc; $i++) {
			$params[] = $argv[ $i ];
		}

		try {
			if (!isset($params[0])) {
				$method->invoke($instance);
			}
			else {
				$method->invokeArgs($instance, $params);
			}
		} catch (\ArgumentCountError $e) {
			print("The number of arguments does not correspond.");
		}
	}
}