<?php
namespace Command;

use Lib\Console\Command;
use ReflectionClass;
use ReflectionMethod;

class HelpCommand extends Command
{
	public function defaultCommand (...$args)
	{
		if(isset($args[0]))
			return $this->helpForCommand($args[0]);

		$commands = include "config/commands.php";
		$commands = $commands['commands'];
		print("Commands: \n");
		foreach ($commands as $c => $v) {
			print("\t$c\t->\t$v[description]\n");
		}
	}


	public function help (...$args)
	{
		$this->defaultCommand($args);
	}

	public function helpForCommand($cmd)
	{
		$commands = include "config/commands.php";
		$commands = $commands['commands'];
		$namespace = $commands[$cmd]['class'];

		$class = new ReflectionClass($namespace);
		$instance = $class->newInstance();
		$method = new ReflectionMethod($instance, 'help');
		$method->invoke($instance);
	}
}