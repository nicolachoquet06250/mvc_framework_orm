<?php

namespace mvc_framework\core\orm\traits;


trait singleton {
	private static $instance;
	public static function create($param = null) {
		$class = self::class;
		if(is_null(self::$instance)) {
			self::$instance = (!is_null($param)) ? new $class($param) : new $class();
		}
		return self::$instance;
	}
}