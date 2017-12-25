<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/12/2017
 * Time: 23:20
 */

namespace Command\Make;


use function camelCase;
use Lib\Console\CreatorCommand;
use PHPUnit\Runner\Exception;

class CommandCreator extends CreatorCommand
{
	/**
	 * @var array
	 */
	private $args;

	private $commandName;

	private $className;


	protected function __construct (array $args)
	{
		parent::__construct($args);

		// Create Class
		if (!isset($args[0])) {
			throw new Exception("The name of the command to create is missing...");
		}
		$this->commandName = $this->args[0];
		$this->className = camelCase($this->commandName . "_command");
	}


	public static function create (array $args)
	{
		try {
			$instance = new self($args);
			$instance->createCommandClass();
			$instance->updateConfigFile();
			print("The command has been created.");
		} catch (\Exception $e) {
			print($e->getMessage());
			print("\n");
			self::displayHelp();
		}
	}


	private function updateConfigFile ()
	{
		// append data to config array;

		$file = fopen('config\\commands.php', 'c+');
		rewind($file);
		$buffer = "";

		while ($line = fgets($file)) {
			$buffer .= $line;
			if (strpos($line, ';') !== false) {
				break;
			}
		}

		$nbBraces = 0;
		$i = strlen($buffer) - 1;
		while ($nbBraces != 3) {
			if ($buffer[ $i ] == ']') {
				$nbBraces++;
			}
			$i--;
		}

		$start = substr($buffer, 0, $i + 2);
		$end = substr($buffer, $i + 3);

		$toInsert = "\n\t\t'$this->commandName' => [\n";
		$toInsert .= "\t\t\t'class' => Command\\$this->className::class,\n";
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
			throw new \Exception("An error has occurred, the command could not be created...2\n");
		}
	}


	private function createCommandClass ()
	{
		$commandArr = include 'config\\commands.php';
		$commandArr = $commandArr['commands'];

		if (array_key_exists($this->commandName, $commandArr)) {
			print("The command '$this->commandName' already exists.");

			return;
		}

		$file = fopen("commands\\$this->className.php", "w");

		$bool = true;

		$bool = $bool && fwrite($file, "<?php\n");
		$bool = $bool && fwrite($file, "namespace Command;\n\n");
		$bool = $bool && fwrite($file, "use Lib\Console\Command;\n\n");
		$bool = $bool && fwrite($file, "class $this->className extends Command\n{\n");
		$bool = $bool && fwrite($file, "\tpublic function defaultCommand (...\$args){\n\t\t// TODO: Implement defaultCommand() method.\n\t}\n\n");
		$bool = $bool && fwrite($file, "\tpublic function help (...\$args){\n\t\t// TODO: Implement help() method.\n\t}\n\n");
		$bool = $bool && fwrite($file, "}");

		fclose($file);

		if (!$bool) {
			throw new \Exception("An error has occurred, the command could not be created...\n");
		}
	}


	public static function displayHelp ()
	{
		print("\nphp cli make:command <name>\n");
	}
}