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
		return $this->if_format_exists(function ($if_not_exists) {
			return $this->format->create_table($if_not_exists);
		}, $if_not_exists);
	}

	public function insert() {
		$this->if_format_exists(function () {
			$this->format->insert();
		});
		return $this;
	}

	public function update() {
		$this->if_format_exists(function () {
			$this->format->update();
		});
		return $this;
	}

	public function delete() {
		$this->if_format_exists(function () {
			$this->format->delete();
		});
		return $this;
	}

	public function drop() {
		$this->if_format_exists(function () {
			$this->format->drop();
		});
		return $this;
	}

	public function save() {
		$this->if_format_exists(function () {
			$this->format->save();
		});
		return $this;
	}

	public function get_where($prop, $value) {
		return $this->if_format_exists(function ($params) {
			return $this->format->get_where($params['prop'], $params['value'], $this->get_class());
		}, ['prop' => $prop, 'value' => $value]);
	}

	protected function refresh_structure($structure) {
		$this->if_format_exists(function ($structure) {
			$this->format->refresh_structure($structure);
		}, $structure);
	}

	protected function set_table($table) {
		$this->if_format_exists(function ($table) {
			$this->format->set_table($table);
		}, $table);
	}

	protected function set_connection($connection) {
		$this->if_format_exists(function ($connection) {
			$this->format->set_connection($connection);
		}, $connection);
	}

	private function if_format_exists(callable $callback, $parameter = null) {
		if(!is_null($this->format)) {
			$this->format->refresh_structure($this->get_structure());
			if (is_null($parameter)) {
				return $callback();
			} else {
				return $callback($parameter);
			}
		}
		return null;
	}
}