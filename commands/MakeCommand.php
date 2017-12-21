<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/12/2017
 * Time: 15:16
 */

namespace Command;

use Command\Make\CommandCreator;
use Command\Make\TestCreator;
use Lib\Console\Command;

use function array_key_exists;
use function camelCase;
use function fclose;
use function fgets;
use function fopen;
use function fwrite;
use function rewind;
use function strlen;
use function strpos;
use function substr;

class MakeCommand extends Command
{
	private $subcommands = [
		'command' => [
			'description' => 'create a command',
		],
	];


	public function defaultCommand (...$args)
	{
		$this->help($args);
	}


	public function help (...$args)
	{
		foreach ($this->subcommands as $s => $v) {
			print("$s\t->\t$v[description]");
		}
	}


	public function test (...$args)
	{
		require_once "commands/make/TestCreator.php";

		if (isset($args[0]) && $args[0] == "help") {
			TestCreator::displayHelp();
		}
		else {
			TestCreator::create($args);
		}
	}


	public function command (...$args)
	{
		require_once "commands/make/CommandCreator.php";

		if (isset($args[0]) && $args[0] == "help") {
			CommandCreator::displayHelp();
		}
		else {
			CommandCreator::create($args);
		}
	}
}