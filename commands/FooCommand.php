<?php
namespace Command;

use Lib\Console\Command;

class FooCommand extends Command{

	public function defaultCommand (...$args)
	{
		printf("foo");
	}

	public function help (...$args)
	{
		// TODO: Implement help() method.
	}


	public function bar($truc = null)
	{
		print('bar - '. $truc);
	}
}