<?php

namespace mvc_framework\core\orm\traits;


trait dbcontext {
	protected $auto_save = false;
	public function get_structure() {
		$vars = [];
		foreach (get_class_vars(get_class($this)) as $var => $value) {
			if($var !== 'auto_save') {
				$vars[$var] = $value;
			}
		}
		return $vars;
	}
	abstract public function create_table($if_not_exisis = false);
	abstract public function insert();
	abstract public function update();
	abstract public function delete();
	abstract public function select();
	public function get($key) {
		if(isset($this->$key)) return $this->$key;
		return null;
	}
	public function set($key, $value) {
		if(isset($this->$key) ||
		   (is_string($this->$key) && $this->$key === '') ||
		   (is_array($this->$key) && empty($this->$key)) ||
		   (is_bool($this->$key) && $this->$key === false)) {
			$this->$key = $value;
			if($this->auto_save) $this->save();
			return $this;
		}
		return false;
	}
	public function update_auto_save() {
		$this->auto_save = !$this->auto_save;
	}
	abstract public function save();
}