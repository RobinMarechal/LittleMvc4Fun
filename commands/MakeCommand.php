<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/12/2017
 * Time: 15:16
 */

namespace Command;


use function array_key_exists;
use function camelCase;
use function count;
use function fclose;
use function fgets;
use function fopen;
use function fputs;
use function fseek;
use function fwrite;
use Lib\Console\Command;
use function rewind;
use const SEEK_END;
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


	public function command (...$args)
	{
		// Create Class
		if (!isset($args[0])) {
			print("The command name is missing...");

			return;
		}

		$commandArr = include 'config/commands.php';
		$commandArr = $commandArr['commands'];

		$commandName = $args[0];

		if (array_key_exists($commandName, $commandArr)) {
			print("The command '$commandName' already exists.");

			return;
		}

		$className = $commandName . "_command";
		$className = camelCase($className);

		$file = fopen("commands/$className.php", "w");

		$bool = true;

		$bool = $bool && fwrite($file, "<?php\n");
		$bool = $bool && fwrite($file, "namespace Command;\n\n");
		$bool = $bool && fwrite($file, "use Lib\Console\Command;\n\n");
		$bool = $bool && fwrite($file, "class $className extends Command\n{\n");
		$bool = $bool && fwrite($file, "\tpublic function defaultCommand (...\$args){\n\t\t// TODO: Implement defaultCommand() method.\n\t}\n\n");
		$bool = $bool && fwrite($file, "\tpublic function help (...\$args){\n\t\t// TODO: Implement help() method.\n\t}\n\n");
		$bool = $bool && fwrite($file, "}");

		fclose($file);

		if (!$bool) {
			print "An error has occurred, the command has not been created...\n";

			return;
		}

		// append data to config array;

		$file = fopen('config/commands.php', 'c+');
		rewind($file);
		$buffer = "";

		while ($line = fgets($file)) {
			$buffer .= $line;
			if (strpos($line, ';') !== false) {
				break;
			}
		}

		$nbBraces = 0;
		$posFromEnd = 0;
		$i = strlen($buffer) - 1;
		while ($nbBraces != 3) {
			if ($buffer[ $i ] == ']') {
				$nbBraces++;
			}
			$i--;
			$posFromEnd++;
		}

		$start = substr($buffer, 0, $i + 2);
		$end = substr($buffer, $i + 3);

		$toInsert = "\n\t\t'$commandName' => [\n";
		$toInsert .= "\t\t\t'class' => Command\\$className::class,\n";
		$toInsert .= "\t\t\t'description' => ''\n";
		$toInsert .= "\t\t]\n";

		$newContent = $start;
		if ($newContent[ -1 ] != ',') {
			$newContent .= ',';
		}
		$newContent .= $toInsert;
		$newContent .= $end;

		rewind($file);
		$bool = fwrite($file, $newContent);

		if (!$bool) {
			print "An error has occurred, the command has not been created...2\n";

			return;
		}
	}
}