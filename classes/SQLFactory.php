<?php

namespace mvc_framework\core\orm;

use mvc_framework\core\orm\traits\connection_template;
use mvc_framework\core\orm\traits\SQL;

class SQLFactory {
	private $cnx, $format, $sql;
	private static $instance;
	public function __construct($cnx) {
		$this->set_connection($cnx);
		$this->set_format();
		$this->set_sql();
	}

	private function set_sql() {
		$class = '\mvc_framework\core\orm\\'.$this->format;
		$this->sql = new $class($this->cnx);
	}

	private function set_connection($cnx) {
		$this->cnx = $cnx;
	}

	private function set_format() {
		$class = get_class($this->cnx);
		$class = explode('\\', $class);
		$format = $class[count($class) -1];
		$this->format = $format;
	}

	private function get_instance() {
		return $this->sql;
	}

	/**
	 * @param null|connection_template $cnx
	 * @param bool $force
	 * @return SQL
	 */
	public static function get($cnx = null, $force = false) {
		if($force) self::$instance = null;
		if(is_null(self::$instance)) self::$instance = new SQLFactory($cnx);
		return self::$instance->get_instance();
	}
}