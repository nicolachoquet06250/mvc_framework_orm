<?php

namespace mvc_framework\core\orm\traits;


trait data_format {
	public static $JSON = 'json';
	public static $MYSQLI = 'mysqli';

	/**
	 * @var data_format|null $format
	 */
	protected $format = null;
	protected $table;
	protected function select_format($format) {
		$structure = $this->get_structure();
		if(file_exists(__DIR__.'/../classes/formats/'.$format.'.php')) {
			require_once __DIR__.'/../classes/formats/'.$format.'.php';
			$format_class = '\mvc_framework\core\orm\data_formats\\'.$format;
			$this->format = new $format_class($structure, $this->get_table_name(), $this->connection);
		}
	}

	private function get_table_name() {
		$table = get_class($this);
		$table = explode('\\', $table)[count(explode('\\', $table))-1];
		$table = str_replace('Context', '', $table);
		$table = strtolower($table);
		return $table;
	}

	public function create_table($if_not_exists = false) {
		$this->if_format_exisis(function ($if_not_exists) {
			$this->format->create_table($if_not_exists);
		}, $if_not_exists);
	}

	public function insert() {
		$this->if_format_exisis(function () {
			$this->format->insert();
		});
	}

	public function update() {
		$this->if_format_exisis(function () {
			$this->format->update();
		});
	}

	public function delete() {
		$this->if_format_exisis(function () {
			$this->format->delete();
		});
	}

	public function save() {
		$this->if_format_exisis(function () {
			$this->format->save();
		});
	}

	protected function refresh_structure($structure) {
		$this->if_format_exisis(function ($structure) {
			$this->format->refresh_structure($structure);
		}, $structure);
	}

	protected function set_table($table) {
		$this->if_format_exisis(function ($table) {
			$this->format->set_table($table);
		}, $table);
	}

	protected function set_connection($connection) {
		$this->if_format_exisis(function ($connection) {
			$this->format->set_connection($connection);
		}, $connection);
	}

	private function if_format_exisis(callable $callback, $parameter = null) {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			if (is_null($parameter)) {
				$callback();
			} else {
				$callback($parameter);
			}
		}
	}
}