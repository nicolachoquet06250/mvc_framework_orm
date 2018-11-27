<?php

namespace mvc_framework\core\orm\traits;


use mvc_framework\core\orm\services\ArrayContext;

trait dbcontext {
	use data_format;
	protected $auto_save = false;
	protected $connection;

	/**
	 * dbcontext constructor.
	 *
	 * @param SQL $connection
	 */
	public function __construct($connection) {
		$this->select_format($connection->get_format());
		$this->set_connection($connection);
	}
	public function get_structure() {
		$vars = [];
		foreach (get_object_vars($this) as $var => $value) {
			if(is_array($value)) $vars[$var] = $value;
		}
		return $vars;
	}
	public function get($key, $what = 'value') {
		if(isset($this->$key)) {
			return $this->$key[$what];
		}
		return null;
	}
	public function set($key, $value, $what = 'value') {
		if(isset($this->$key)) {
			if($this->$key['type']['name'] === 'integer'
			   || $this->$key['type']['name'] === 'int'
			   ||  $this->$key['type']['name'] === 'numeric') {
				$value = intval($value);
			}
			$this->$key[$what] = $value;
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

	public function to_array() {
		$array = ArrayContext::create('mixed');
		foreach (get_object_vars($this) as $prop_name => $prop_value) {
			if(is_array($prop_value)) {
				$array->push($prop_value['value'], $prop_name)/*[$prop_name] = $prop_value['value']*/;
			}
		}
		return $array;
	}
}