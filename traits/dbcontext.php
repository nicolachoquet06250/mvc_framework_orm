<?php

namespace mvc_framework\core\orm\traits;


trait dbcontext {
	protected $auto_save = false;
	abstract public function create_table($if_not_exisis = false);
	abstract public function insert();
	abstract public function update();
	abstract public function delete();
	abstract public function select();
	abstract public function save();

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
}