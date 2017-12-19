<?php

namespace Lib;

use \PDO;

class Database extends PDO
{
    private $tests;

    protected $host;
    protected $dbname;
    protected $username;
    protected $password;
    protected $driver;
    protected $charset;

    public function __construct($tests = false)
    {
        $this->tests = $tests;

        prepareDbInfos();

        try {
            parent::__construct("$this->driver:host=$this->host;dbname=$this->dbname;charset=$this->charset",
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    private function prepareDbInfos()
    {
        $config = config('database.app');

        if ($this->tests) {
            $testsConfig = config('database.tests');
            if ($testsConfig !== false) {
                $config = array_merge($config, $testsConfig);
            }
        }

        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->driver = $config['driver'];
        $this->charset = $config['charset'];
    }

    public static function connect($tests = false)
    {
        return new self($tests);
    }
}