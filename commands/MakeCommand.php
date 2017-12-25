<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/12/2017
 * Time: 15:16
 */

namespace Command;

use Command\Make\CommandCreator;
use Command\Make\ControllerCreator;
use Command\Make\MiddlewareCreator;
use Command\Make\ModelCreator;
use Command\Make\TestCreator;
use Lib\Console\Command;

class MakeCommand extends Command
{
	private $subcommands = [
		'command' => [
			'description' => 'create a command',
		],
		'test' => [
			'description' => 'create a unit or feature test'
		],
		'middleware' => [
			'description' => 'create a middleware'
		],
		'controller' => [
			'description' => 'create a controller'
		],
		'model' => [
			'description' => 'create a model'
		]
	];


	public function defaultCommand (...$args)
	{
		$this->help($args);
	}


	public function help (...$args)
	{
		foreach ($this->subcommands as $s => $v) {
			print("$s  ->  $v[description]\n");
		}
	}

	public function middleware(...$args)
	{
		require_once "commands\\make\\MiddlewareCreator.php";

		if(isset($args[0]) && $args[0] == "help"){
			MiddlewareCreator::displayHelp();
		}
		else{
			MiddlewareCreator::create($args);
		}
	}

	public function test (...$args)
	{
		require_once "commands\\make\\TestCreator.php";

		if (isset($args[0]) && $args[0] == "help") {
			TestCreator::displayHelp();
		}
		else {
			TestCreator::create($args);
		}
	}


	public function command (...$args)
	{
		require_once "commands\\make\\CommandCreator.php";

		if (isset($args[0]) && $args[0] == "help") {
			CommandCreator::displayHelp();
		}
		else {
			CommandCreator::create($args);
		}
	}

	public function controller (...$args){
		require_once "commands\\make\\ControllerCreator.php";

		if (isset($args[0]) && $args[0] == "help") {
			ControllerCreator::displayHelp();
		}
		else {
			ControllerCreator::create($args);
		}
	}

	public function model (...$args){
		require_once "commands\\make\\ModelCreator.php";

		if (isset($args[0]) && $args[0] == "help") {
			ModelCreator::displayHelp();
		}
		else {
			ModelCreator::create($args);
		}
	}
}