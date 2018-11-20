<?php

namespace mvc_framework\core\orm\traits;


trait data_format {
	public static $JSON = 'json';
	public static $MYSQLI = 'mysqli';

	/**
	 * @var data_format|null $format
	 */
	protected $format = null;
	protected function select_format($format) {
		$structure = $this->get_structure();
		if(file_exists(__DIR__.'/../classes/formats/'.$format.'.php')) {
			require_once __DIR__.'/../classes/formats/'.$format.'.php';
			$format_class = '\mvc_framework\core\orm\data_formats\\'.$format;
			$this->format = new $format_class($structure);
		}
	}

	public function create_table($if_not_exisis = false) {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			$this->format->create_table($if_not_exisis);
		}
	}

	public function insert() {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			$this->format->insert();
		}
	}

	public function update() {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			$this->format->update();
		}
	}

	public function delete() {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			$this->format->delete();
		}
	}

	public function save() {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			$this->format->save();
		}
	}

	protected function refresh_structure($structure) {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			$this->format->refresh_structure($structure);
		}
	}
}