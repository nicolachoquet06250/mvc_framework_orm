<?php

namespace mvc_framework\core\orm\traits;


trait connection_template {
	public $to_array;
	abstract public function to_array();
	abstract public static function get();
}