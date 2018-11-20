<?php

namespace mvc_framework\core\orm\connection_templates;


use mvc_framework\core\orm\traits\connection_template;

class mysqli {
	use connection_template;
	private $host, $username, $password, $db = null;

	public function __construct($host, $username, $password, $db = null) {
		$this->set_host($host);
		$this->set_username($username);
		$this->set_password($password);
		$this->set_db($db);
		$this->to_array();
	}

	public function to_array() {
		$this->to_array = [
			'host' => $this->get_host(),
			'username' => $this->get_username(),
			'password' => $this->get_password()
		];
		if(!is_null($this->get_db())) {
			$this->to_array['db'] = $this->get_db();
		}
	}

	public static function get($host, $username, $password, $db = null) {
		return new mysqli($host, $username, $password, $db);
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
	public function set_host(string $host) {
		$this->host = $host;
	}

	/**
	 * @return string
	 */
	public function get_username() {
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function set_username(string $username) {
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function get_password() {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function set_password(string $password) {
		$this->password = $password;
	}

	/**
	 * @return null|string
	 */
	public function get_db() {
		return $this->db;
	}

	/**
	 * @param null|string $db
	 */
	public function set_db($db) {
		$this->db = $db;
	}

}