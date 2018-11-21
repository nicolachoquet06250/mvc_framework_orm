<?php

namespace mvc_framework\core\orm\connection_templates;


use mvc_framework\core\orm\traits\connection_template;

class json {
	use connection_template;
	private $base_directory, $host, $database;

	public function __construct($base_directory, $host, $database) {
		$this->set_base_directory($base_directory);
		$this->set_host($host);
		$this->set_database($database);
		$this->to_array();
	}

	public function to_array() {
		$this->to_array = [
			'base_directory' => $this->get_base_directory(),
			'host' => $this->get_host(),
		];
		if(!is_null($this->get_database())) {
			$this->to_array['database'] = $this->get_database();
		}
	}

	public static function get($base_directory, $host, $database = null) {
		return new json($base_directory, $host, $database);
	}

	/**
	 * @return string
	 */
	public function get_base_directory() {
		return $this->base_directory;
	}

	/**
	 * @param string $base_directory
	 */
	public function set_base_directory($base_directory) {
		$this->base_directory = $base_directory;
	}

	/**
	 * @return string
	 */
	public function get_host() {
		return $this->host;
	}

	/**
	 * @param string $host
	 */
	public function set_host($host) {
		$this->host = $host;
	}

	/**
	 * @return string
	 */
	public function get_database() {
		return $this->database;
	}

	/**
	 * @param string $database
	 */
	public function set_database($database) {
		$this->database = $database;
	}
}