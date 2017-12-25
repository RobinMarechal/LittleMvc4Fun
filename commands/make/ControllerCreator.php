<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 25/12/2017
 * Time: 01:41
 */

namespace Command\Make;


use Lib\Console\CreatorCommand;

class ControllerCreator extends CreatorCommand
{
	private $className;


	protected function __construct (array $args)
	{
		parent::__construct($args);

		if (!isset($args[0])) {
			throw new Exception("The controller's class name is missing...");
		}

		$this->className = $this->args[0];
	}


	public static function create (array $args)
	{
		try {
			$instance = new self($args);
			$instance->createControllerClass();
			print("The controller has been created.");
		} catch (\Exception $e) {
			print($e->getMessage());
			print("\n");
			self::displayHelp();
		}
	}

	private function createControllerClass ()
	{
		$file = fopen("app\\http\\controllers\\$this->className.php", "w");

		$bool = true;

		$bool = $bool && fwrite($file, "<?php\n");
		$bool = $bool && fwrite($file, "namespace App\\Controller;\n\n");
		$bool = $bool && fwrite($file, "use Lib\\Controller;\n\n");
		$bool = $bool && fwrite($file, "class $this->className extends Controller\n{\n\n");
		$bool = $bool && fwrite($file, "}");

		fclose($file);

		if (!$bool) {
			throw new \Exception("An error has occurred, the controller could not be created...\n");
		}
	}


	public static function displayHelp ()
	{
		print("\nphp cli make:controller <ControllerClassName>\n");
	}
}