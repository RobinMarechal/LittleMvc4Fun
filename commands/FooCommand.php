<?php
namespace Command;

use Lib\Console\Command;

class FooCommand extends Command{

	public function defaultCommand ()
	{
		printf("foo");
	}

	public function bar($truc)
	{
		print($truc);
	}
}