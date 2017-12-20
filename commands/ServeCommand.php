<?php
namespace Command;

use function array_key_exists;
use function exec;
use Lib\Console\Command;
use function passthru;
use function preg_grep;
use const STDOUT;
use function strlen;
use function strpos;
use function substr;

class ServeCommand extends Command
{
	private $port = 8000;
	private $hostname = 'localhost';
	private $dir = 'www';


	public function defaultCommand (...$args)
	{
		$this->parseParams($args);

		$this->runServer();
	}


	public function help (...$args)
	{
		print("Params: \n");
		print("\t--port\t\t->\tdefine the server's port (default: $this->port)\n");
		print("\t--hostname\t->\tdefine the server's hostname (default: $this->hostname)\n");
		print("\t--dir\t\t->\tdefine the server's directory root (default: $this->dir/)");
		print("\n\nExample: 'php cli serve --port=8080' -> run a server with default dir, default hostname at port 8080\n");
	}


	public function parseParams (...$args)
	{
		$arr = [];

		foreach ($args[0] as $arg) {
			$pos = strpos($arg, '=');
			$arr[substr($arg, 2, $pos - 2 )] = substr($arg, $pos + 1);
		}

		if(array_key_exists('port', $arr))
			$this->port = $arr['port'];
		if(array_key_exists('hostname', $arr))
			$this->hostname = $arr['hostname'];
		if(array_key_exists('dir', $arr))
			$this->dir = $arr['dir'];
	}

	public function runServer()
	{
		$cmd = "php -S $this->hostname:$this->port -t $this->dir";
		print("Server started at $this->hostname:$this->port\n");
		passthru($cmd);
	}
}