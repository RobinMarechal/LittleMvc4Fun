<?php
namespace Lib;
use \PDO;

class Database extends PDO
{

	protected $host;
	protected $dbname;
	protected $username;
	protected $password;
	protected $driver;
	protected $charset;

	public function __construct()
	{		
		$config = include('../config/database.php');
		$this->host = $config['host'];
		$this->dbname = $config['dbname'];
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->driver = $config['driver'];
		$this->charset = $config['charset'];

		try
		{
			$db = parent::__construct("$this->driver:host=$this->host;dbname=$this->dbname;charset=$this->charset",
							$this->username, 
							$this->password, 
							[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
						);
		}
		catch(Exception $e)
		{
			die('Error: '.$e->getMessage());
		}

		return $db;
	}

	public static function connect()
	{
		return new self;
	}
}