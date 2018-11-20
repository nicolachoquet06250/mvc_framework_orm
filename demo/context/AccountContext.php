<?php

namespace mvc_framework\core\orm\dbcontext;


use mvc_framework\core\orm\traits\dbcontext;

class AccountContext {
	use dbcontext;

	protected $id_account = [
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
	], $IP = [
		'type' => [
			'name' => 'varchar',
			'size' => 255,
		],
		'default' => null,
		'null' => false,
	];
}