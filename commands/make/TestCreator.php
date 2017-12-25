<?php
/**
 * Created by PhpStorm.
 * User: Utilisateur
 * Date: 20/12/2017
 * Time: 23:28
 */

namespace Command\Make;


use function camelCase;
use Command\TestCommand;
use function join;
use Lib\Console\CreatorCommand;
use function mkdir;
use PHPUnit\Runner\Exception;
use function preg_split;
use function substr;

class TestCreator extends CreatorCommand
{
	const TYPE_UNIT = 0;
	const ARG_UNIT = "--unit";

	const TYPE_FEATURE = 1;
	const ARG_FEATURE = "--feature";

	/**
	 * @var array
	 */
	private $args;

	/**
	 * @var mixed the test alias
	 */
	private $alias;

	/**
	 * @var string Test name without 'Test' suffix
	 */
	private $className;

	/**
	 * @var the namespace of the created class
	 */
	private $classNamespace;

	/**
	 * @var string Tests root namespace
	 */
	private $testsNamespace = "Test\\";

	/**
	 * @var string unit tests namespace inside of the root namespace
	 */
	private $unitNamespace = "Unit\\";

	/**
	 * @var string feature tests namespace inside of the root namespace
	 */
	private $featureNamespace = "Feature\\";

	/**
	 * @var The type of test (unit or feature)
	 */
	private $testType = TestCreator::TYPE_UNIT;

	/**
	 * @var class directory inside of tests/unit or tests/feature
	 */
	private $dir;

	/**
	 * @var complete final namespace
	 */
	private $completeNameSpace;


	protected function __construct (array $args)
	{
		parent::__construct($args);

		// Create Class
		if (!isset($this->args[0])) {
			throw new Exception("The name of the command to create is missing...");
		}
		$this->parseArgs();
	}


	private function parseArgs ()
	{
		// length == 1
		$this->alias = $this->args[0];
		$this->className = camelCase($this->alias);

		if (!isset($this->args[1])) {
			return;
		}


		for ($i = 1; $i < count($this->args); $i++) {
			$arg = $this->args[ $i ];

			if ($arg == TestCreator::ARG_FEATURE) {
				$this->testType = TestCreator::TYPE_FEATURE;
			}
			else if ($arg == TestCreator::ARG_UNIT) {
				$this->testType = TestCreator::TYPE_UNIT;
			}
			else {
				$equalPos = strpos($arg, '=');
				if (!$equalPos || ($arg[0] . $arg[1] != "--")) {
					continue;
				}

				$key = substr($arg, 2, $equalPos - 2);
				$value = substr($arg, $equalPos + 1);

				if ($key == 'class') {
					$this->className = $value;
				}
				else if ($key == 'dir') {
					$this->dir = preg_split("/(\\\|\/)/", $value);
					$this->classNamespace = camelCase(join("\\_", $this->dir)); // tricky
				}
			}
		}
	}


	public static function create (array $args)
	{
		try {
			$instance = new self($args);
			$instance->createTestClass();
			$instance->updateConfigFile();
			print("The test has been created.");
		} catch (\Exception $e) {
			print($e->getMessage());
			print("\n");
			self::displayHelp();
		}
	}


	private function updateConfigFile ()
	{
		// append data to config array;

		$file = fopen('config\\tests.php', 'c+');
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

		$toInsert = "\n\t'$this->alias' => $this->completeNameSpace\\$this->className"."Test::class,\n";

		$newContent = $start;
		if ($newContent[ -1 ] != ',') {
			$newContent .= ',';
		}

		$newContent .= $toInsert;
		$newContent .= $end;

		rewind($file);
		$bool = fwrite($file, $newContent);

		if (!$bool) {
			throw new \Exception("An error has occurred, the test could not be created...\n");
		}
	}


	private function createTestClass ()
	{
		$testsArr = include 'config\\tests.php';

		if (array_key_exists($this->alias, $testsArr)) {
			throw new Exception("The command '$this->alias' already exists.");
		}

		$directory = join("\\", $this->dir);

		$this->completeNameSpace = $this->testsNamespace;
		if ($this->testType == TestCreator::TYPE_UNIT) {
			$this->completeNameSpace .= "$this->unitNamespace";
			$directory = "tests\\unit\\$directory";
		}
		else {
			$this->completeNameSpace .= "$this->featureNamespace";
			$directory = "tests\\feature\\$directory";
		}


		if (isset($this->classNamespace)) {
			$this->completeNameSpace .= $this->classNamespace;
		}


		if (!mkdir($directory, 0777, true)) {
			throw new Exception("The test folders could not be created...");
		}

		$file = fopen("$directory\\$this->className"."Test.php", "w");

		$bool = true;

		$bool = $bool && fwrite($file, "<?php\n");
		$bool = $bool && fwrite($file, "namespace $this->completeNameSpace;\n\n");
		$bool = $bool && fwrite($file, "use PHPUnit\Framework\TestCase;\n\n");
		$bool = $bool && fwrite($file, "class " . $this->className . "Test extends TestCase\n{\n\t// TODO: write tests\n");
		$bool = $bool && fwrite($file, "}");

		fclose($file);

		if (!$bool) {
			throw new \Exception("An error has occurred, the test could not be created...\n");
		}
	}


	public static function displayHelp ()
	{
		print("\nphp cli make:test <testAlias> [OPTIONS]\n");
		print("Options:\n");
		print("\t--dir\t-> set the directory of the created class, inside of /tests/unit or /tests/feature (depending on the type of test)\n");
		print("\t--class\t-> set the name of the class. the suffix 'Test' will be added to it.\n");
		print("\t--unit\t-> create a unit test, which is the default value. Class dir: /tests/unit/\n");
		print("\t--feature\t-> create a feature test. Class dir: /tests/feature/\n");
	}
}