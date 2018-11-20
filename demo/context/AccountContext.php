<?php

namespace mvc_framework\core\orm\dbcontext;


use mvc_framework\core\orm\traits\data_format;
use mvc_framework\core\orm\traits\dbcontext;

class AccountContext {
	use dbcontext;
	use data_format;

	protected $id = [
		'type' => [
			'name' => 'integer',
			'size' => 11,
		],
		'default' => null,
		'null' => false,
		'autoincrement' => true,
		'key' => 'primary',
	], $email = [
		'type' => [
			'name' => 'varchar',
			'size' => 255,
		],
		'default' => null,
		'null' => false,
	], $password = [
		'type' => [
			'name' => 'varchar',
			'size' => 255,
		],
		'default' => null,
		'null' => false,
	], $nom = [
		'type' => [
			'name' => 'varchar',
			'size' => 255,
		],
		'default' => null,
		'null' => false,
	], $prenom = [
		'type' => [
			'name' => 'varchar',
			'size' => 255,
		],
		'default' => null,
		'null' => false,
	], $pseudo = [
		'type' => [
			'name' => 'varchar',
			'size' => 255,
		],
		'default' => null,
		'null' => false,
	], $ip = [
		'type' => [
			'name' => 'varchar',
			'size' => 255,
		],
		'default' => null,
		'null' => false,
	];

	public function __construct() {
		$this->select_format(data_format::$JSON);
	}
}