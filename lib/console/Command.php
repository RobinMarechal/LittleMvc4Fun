<?php

namespace Lib\Console;

abstract class Command{

	public function __construct ()
	{
	}

	abstract public function defaultCommand(...$args);

	abstract public function help(...$args);
}