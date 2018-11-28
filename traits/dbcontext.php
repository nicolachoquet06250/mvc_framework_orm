<?php

namespace mvc_framework\core\orm\traits;


use mvc_framework\core\orm\services\ArrayContext;

trait dbcontext {
	use data_format;
	protected $auto_save = false;
	protected $connection;
	private $structure = null;

	/**
	 * dbcontext constructor.
	 *
	 * @param SQL $connection
	 */
	public function __construct($connection) {
		$this->select_format($connection->get_format());
		$this->set_connection($connection);
		$this->structure = [];
		foreach (get_object_vars($this) as $var => $value) {
			if(is_array($value) && $var !== 'structure') $this->structure[$var] = $value;
		}
	}

	public function get_structure() {
		return $this->structure;
	}

	public function get($key, $what = 'value') {
		if($what === 'value') return $this->clean_value($key);
		if(isset($this->structure[$key])) return $this->structure[$key][$what];
		return null;
	}
	public function set($key, $value, $what = 'value') {
		if(isset($this->structure[$key])) {
			if($this->structure[$key]['type']['name'] === 'integer'
			   || $this->structure[$key]['type']['name'] === 'int'
			   ||  $this->structure[$key]['type']['name'] === 'numeric') {
				$value = intval($value);
			}
			$this->structure[$key][$what] = $value;
			if($this->auto_save) $this->save();
			return $this;
		}
		return false;
	}
	public function update_auto_save() {
		$this->auto_save = !$this->auto_save;
	}

	/**
	 * @param SQL $connection
	 * @return dbcontext
	 */
	public static function create($connection) {
		$class = __CLASS__;
		return new $class($connection);
	}

	public function get_table_name() {
		$class_name = __CLASS__;
		$class_name = explode('\\', $class_name);
		$class_name = $class_name[count($class_name)-1];
		$table_name = str_replace('Context', '', $class_name);
		return strtolower($table_name);
	}

	public function get_class() {
		return __CLASS__;
	}

	public function to_array($assoc = true) {
		$array = ArrayContext::create();
		foreach ($this->get_structure() as $prop_name => $prop_value) {
			if(is_array($prop_value)) {
				if($assoc)
					$array->push($this->get($prop_name), $prop_name);
				else
					$array->push($this->get($prop_name));
			}
		}
		return $array;
	}

	public function clean_value($prop_name) {
		$prop_value = isset($this->structure[$prop_name]['value']) ? $this->structure[$prop_name]['value'] : null;
		if(($prop_value_cast = intval($prop_value)) !== 0 &&
		   ($this->structure[$prop_name]['type']['name'] === 'integer' ||
			$this->structure[$prop_name]['type']['name'] === 'numeric' ||
			$this->structure[$prop_name]['type']['name'] === 'int')) $prop_value = $prop_value_cast;
		return $prop_value;
	}
}