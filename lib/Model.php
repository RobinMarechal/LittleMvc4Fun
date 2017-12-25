<?php
namespace Lib;

use function get_class;
use \PDO;
use \DateTime;
use function strtolower;

abstract class Model
{

	protected $table;
	protected $primaryKey = 'id';
	protected $timestamps = true;
	protected $timestampFields = ['created_at', 'updated_at'];
	protected $dates = [];
	protected $softDeletes = false;
	protected $softDeleteField = 'deleted_at';

	public $fields = [];
	public $hidden = [];
	public $visible = ['*'];


	function __construct ($data = false)
	{
		$this->table = plural(strtolower(array_last(explode('\\', get_class($this)))));

		if ($data !== false) {
			$this->init($data);
		}
	}


	static function createFromFetch ($class, $data)
	{
		if (is_array($data) && is_object($data[0])) {
			$models = [];
			foreach ($data as $d) {
				$models[] = new $class($d);
			}

			return $models;
		}
		else {
			return new $class($data);
		}
	}


	public function init ($data)
	{
		foreach ($data as $k => $v) {
			$this->$k = $v;
		}

		if (!is_object($data)) {
			$data = (object) $data;
		}

		if ($this->timestamps) {
			foreach ($this->timestampFields as $f) {
				if ($data->$f == null) {
					$this->$f = null;
				}
				else {
					$this->$f = new DateTime($data->$f);
				}
			}
		}

		if ($this->softDeletes) {
			$field = $this->softDeleteField;
			if ($data->$field == null) {
				$this->$field = null;
			}
			else {
				$this->$field = new DateTime($data->$field);
			}
		}

		foreach ($this->dates as $d) {
			$this->$d = new DateTime($data->$d);
		}

		foreach ($this->hidden as $h) {
			unset($this->$h);
		}
	}
}