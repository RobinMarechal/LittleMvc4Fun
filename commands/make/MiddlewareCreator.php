<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 25/12/2017
 * Time: 00:49
 */

namespace Command\Make;


use Lib\Console\CreatorCommand;

class MiddlewareCreator extends CreatorCommand
{

	private $className;

	private $alias;


	protected function __construct (array $args)
	{
		parent::__construct($args);

		if (!isset($args[0])) {
			throw new Exception("The middleware's class name is missing...");
		}

		if (!isset($args[1])) {
			throw new Exception("The middleware's alias is missing...");
		}


		$this->className = $this->args[0];
		$this->alias = $this->args[1];
	}


	public static function create (array $args)
	{
		try {
			$instance = new self($args);
			$instance->createMiddlewareClass();
			$instance->updateConfigFile();
			print("The middleware has been created.");
		} catch (\Exception $e) {
			print($e->getMessage());
			print("\n");
			self::displayHelp();
		}
	}


	private function updateConfigFile ()
	{
		// append data to config array;

		$file = fopen('app\\http\\middlewares.php', 'c+');
		rewind($file);
		$buffer = "";

		while ($line = fgets($file)) {
			$buffer .= $line;
		}

		$i = strlen($buffer) - 1;
		while ($buffer[ $i ] != ',' && $buffer[ $i ] != '[' && $buffer[ $i ] != 's') {
			$i--;
		}

		$start = substr($buffer, 0, $i + 1);
		$end = substr($buffer, $i + 2);

		// Complete namespace

		$toInsert = "\n\t'$this->alias' => App\\Middleware\\$this->className::class,\n";

		$newContent = $start;
		if ($newContent[ -1 ] != ',') {
			$newContent .= ',';
		}

		$newContent .= $toInsert;
		$newContent .= $end;

		rewind($file);
		$bool = fwrite($file, $newContent);

		if (!$bool) {
			throw new \Exception("An error has occurred, the middleware could not be created...\n");
		}
	}


	private function createMiddlewareClass ()
	{
		$middlewareArr = include 'app\\http\\middlewares.php';

		if (array_key_exists($this->alias, $middlewareArr)) {
			print("The middleware '$this->alias' already exists.");

			return;
		}

		$file = fopen("app\\http\\middlewares\\$this->className.php", "w");

		$bool = true;

		$bool = $bool && fwrite($file, "<?php\n");
		$bool = $bool && fwrite($file, "namespace App\\Middleware;\n\n");
		$bool = $bool && fwrite($file, "use Lib\\Middleware;\n\n");
		$bool = $bool && fwrite($file, "class $this->className extends Middleware\n{\n");
		$bool = $bool && fwrite($file, "\tpublic function handle (){\n\t\t// TODO: Implement handle() method.\n\t}\n\n");
		$bool = $bool && fwrite($file, "}");

		fclose($file);

		if (!$bool) {
			throw new \Exception("An error has occurred, the middleware could not be created...\n");
		}
	}


	public static function displayHelp ()
	{
		print("\nphp cli make:middleware <MiddlewareClassName> <middlewareAlias>\n");
	}
}