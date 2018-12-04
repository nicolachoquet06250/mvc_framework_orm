<?php

namespace mvc_framework\core\orm\services;


use mvc_framework\core\orm\connection_templates\json;
use mvc_framework\core\orm\connection_templates\mysqli;

class sql_connection {
	public function get_from_conf_id($conf_id) {}

	/**
	 * @param $base_directory
	 * @param $host
	 * @param $database
	 * @return json
	 */
	public function get_json($base_directory, $host, $database) {
		return json::get($base_directory, $host, $database);
	}

	private function init_cnx($cnx, $array) {
		foreach ($array as $prop => $value) {
			$cnx->{'set_'.$prop}($value);
		}
		return $cnx;
	}

	/**
	 * @param $host
	 * @param $username
	 * @param $password
	 * @param $database
	 * @return mysqli
	 */
	public function get_mysqli($host, $username, $password, $database = null) {
		return mysqli::get($host, $username, $password, $database);
	}
}