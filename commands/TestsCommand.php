<?php
namespace Command;

use Lib\Console\Command;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\TestRunner;
use ReflectionClass;

class TestsCommand extends Command{

	public function __construct ()
	{
		parent::__construct();
	}


	public function defaultCommand (...$args)
	{
		// TODO: Implement defaultCommand() method.
	}

	public function unit($testName)
	{
		$testConfigs = include('config/tests.php');

		$namespace = $testConfigs[$testName];

		$class = new ReflectionClass($namespace);
		TestRunner::run($class)->run($class->newInstance());
	}


	public function help (...$args)
	{
		print("tests help");
	}
}