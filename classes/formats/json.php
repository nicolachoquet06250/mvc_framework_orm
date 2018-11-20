<?php

namespace mvc_framework\core\orm\data_formats;


use mvc_framework\core\orm\traits\data_format;

class json {
	use data_format;
	public $structure;

	public function __construct($structure) {
		$this->structure = $structure;
	}

	public function create_table($if_not_exisis = false) {
		var_dump($this->structure);
	}

	public function insert() {
		var_dump($this->structure);
	}

	public function update() {
		var_dump($this->structure);
	}

	public function delete() {
		var_dump($this->structure);
	}

	public function select() {
		var_dump($this->structure);
	}

	public function save() {
		var_dump($this->structure);
	}

	public function refresh_structure($structure) {
		$this->structure = $structure;
	}

}