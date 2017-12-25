<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 25/12/2017
 * Time: 01:06
 */

namespace Lib\Console;


use function is_array;

abstract class CreatorCommand
{
	protected $args;


	protected function __construct (array $args)
	{
		$this->args = $args;
	}

	public static abstract function create(array $args);

	public static abstract function displayHelp();
}