<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 25/12/2017
 * Time: 02:02
 */

namespace Command\Make;


use function is_null;
use Lib\Console\CreatorCommand;

class ModelCreator extends CreatorCommand
{
	private $className;

	private $table = null;

	protected function __construct (array $args)
	{
		parent::__construct($args);

		if (!isset($args[0])) {
			throw new Exception("The model's class name is missing...");
		}

		$this->className = $this->args[0];

		if(isset($args[1]))
			$this->table = $args[1];
	}


	public static function create (array $args)
	{
		try {
			$instance = new self($args);
			$instance->createModelClass();
			print("The model has been created.");
		} catch (\Exception $e) {
			print($e->getMessage());
			print("\n");
			self::displayHelp();
		}
	}


	private function createModelClass ()
	{
		$file = fopen("app\\$this->className.php", "w");

		$bool = true;

		$bool = $bool && fwrite($file, "<?php\n");
		$bool = $bool && fwrite($file, "namespace App;\n\n");
		$bool = $bool && fwrite($file, "use Lib\\Model;\n\n");
		$bool = $bool && fwrite($file, "class $this->className extends Model\n{\n");
		if(!is_null($this->table))
		{
			$bool = $bool && fwrite($file, "\tprotected \$table = '$this->table';");
		}
		$bool = $bool && fwrite($file, "\n}");

		fclose($file);

		if (!$bool) {
			throw new \Exception("An error has occurred, the model could not be created...\n");
		}
	}


	public static function displayHelp ()
	{
		print("\nphp cli make:model <ModelClassName> [<tableName>]\n");
	}
}