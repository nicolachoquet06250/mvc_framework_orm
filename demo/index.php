<?php

require_once __DIR__.'/autoload.php';

use \mvc_framework\core\orm\traits\data_format;
use \mvc_framework\core\orm\connection_templates\json;
use \mvc_framework\core\orm\connection_templates\mysqli;
use \mvc_framework\core\orm\dbcontext\AccountContext;

try {
	$data_type = data_format::$JSON;

	$supported_formats = [
		data_format::$JSON,
		data_format::$MYSQLI,
	];
	$supported_classes = [
		data_format::$JSON => '\mvc_framework\core\orm\json',
		data_format::$MYSQLI => '\mvc_framework\core\orm\mysqli',
	];
	$supported_connection_template = [
		data_format::$JSON => json::get(
			__DIR__.'/datas',
			'localhost',
			'nicolas-choquet_budgets'
		),
		data_format::$MYSQLI => mysqli::get(
			'mysql-nicolas-choquet.alwaysdata.net',
			143175,
			'2669NICOLAS2107',
			'nicolas-choquet_budgets'
		),
	];

	/** @var \mvc_framework\core\orm\traits\SQL $cnx */
	$cnx = new $supported_classes[$data_type]($supported_connection_template[$data_type]);
	$table = AccountContext::create($cnx);
	$table->create_table(true);

	$cnx->query('SELECT * FROM `?table`', [
		'table' => $table->get_table_name(),
	]);

	/** @var \mvc_framework\core\orm\dbcontext\AccountContext[] $accounts */
	$accounts = $cnx->fetch_object($table->get_class());

	if(empty($accounts)) {
		$accounts[] = \mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																	  ->set('id_account', 0)
																	  ->set('email', 'toto@toto.com')
																	  ->set('password', 'tetedecul')
																	  ->set('nom', 'Loubet')
																	  ->set('prenom', 'André')
																	  ->set('pseudo', 'HelloWorld')
																	  ->set('IP', '')
																	  ->insert();
		$accounts[] = \mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																	  ->set('id_account', 1)
																	  ->set('email', 'toto1@toto.com')
																	  ->set('password', 'yahoooooo')
																	  ->set('nom', 'Loubet')
																	  ->set('prenom', 'Karine')
																	  ->set('pseudo', 'HelloWorld1')
																	  ->set('IP', '')
																	  ->insert();
		$accounts[] = \mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																	  ->set('id_account', 2)
																	  ->set('email', 'toto2@toto.com')
																	  ->set('password', 'HarryPotter')
																	  ->set('nom', 'Choquet')
																	  ->set('prenom', 'Nicolas')
																	  ->set('pseudo', 'HelloWorld2')
																	  ->set('IP', '')
																	  ->insert();
		$accounts[] = \mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																	  ->set('id_account', 3)
																	  ->set('email', 'toto3@toto.com')
																	  ->set('password', 'Keen_v')
																	  ->set('nom', 'Choquet')
																	  ->set('prenom', 'Yann')
																	  ->set('pseudo', 'HelloWorld3')
																	  ->set('IP', '')
																	  ->insert();
	}

	foreach ($accounts as $id => $account) {
		var_dump($account->get('email'));
		var_dump($account->to_array());
		if($id === 1)
			$account->set('pseudo', 'nouveau_pseudo2')->update();
	}
}
catch (Exception $e) {
	echo $e->getMessage()."\n";
}