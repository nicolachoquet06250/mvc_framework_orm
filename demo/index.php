<?php

require_once __DIR__.'/autoload.php';

use mvc_framework\core\orm\classes\IndexCallbacks;
use mvc_framework\core\orm\services\ArrayContext;
use \mvc_framework\core\orm\traits\data_format;
use \mvc_framework\core\orm\connection_templates\json;
use \mvc_framework\core\orm\connection_templates\mysqli;
use \mvc_framework\core\orm\dbcontext\AccountContext;

try {
	$data_type = data_format::$JSON;
	$supported_connection_template = ArrayContext::create('object', \mvc_framework\core\orm\traits\connection_template::class);
	$supported_connection_template->push(
		json::get(
			__DIR__.'/datas',
			'localhost',
			'nicolas-choquet_budgets'
		),
		data_format::$JSON
	)->push(
		mysqli::get(
			'mysql-nicolas-choquet.alwaysdata.net',
			143175,
			'2669NICOLAS2107',
			'nicolas-choquet_budgets'
		),
		data_format::$MYSQLI
	);

	$cnx = \mvc_framework\core\orm\SQLFactory::get($supported_connection_template->get($data_type));
	$table = AccountContext::create($cnx);
	$table->create_table(true);

	$query = 'SELECT * FROM `?table` WHERE `id_account`="1"';
	$query_params = ArrayContext::create('string')->init('table', $table->get_table_name());

	/** @var ArrayContext $accounts_object */
	$accounts_object = $cnx->query($query, $query_params)->fetch_object($table->get_class());
	if($accounts_object->is_empty()) {
		$accounts_object->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																			   ->set('id_account', 0)
																			   ->set('email', 'toto@toto.com')
																			   ->set('password', 'tetedecul')
																			   ->set('nom', 'Loubet')
																			   ->set('prenom', 'André')
																			   ->set('pseudo', 'HelloWorld')
																			   ->set('IP', '')
																			   ->insert()
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 1)
																->set('email', 'toto1@toto.com')
																->set('password', 'yahoooooo')
																->set('nom', 'Loubet')
																->set('prenom', 'Karine')
																->set('pseudo', 'HelloWorld1')
																->set('IP', '')
																->insert()
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 2)
																->set('email', 'toto2@toto.com')
																->set('password', 'HarryPotter')
																->set('nom', 'Choquet')
																->set('prenom', 'Nicolas')
																->set('pseudo', 'HelloWorld2')
																->set('IP', '')
																->insert()
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 3)
																->set('email', 'toto3@toto.com')
																->set('password', 'Keen_v')
																->set('nom', 'Choquet')
																->set('prenom', 'Yann')
																->set('pseudo', 'HelloWorld3')
																->set('IP', '')
																->insert()
		);
	}
	$accounts_object = $cnx->query($query, $query_params)->fetch_object($table->get_class());

	/** @var ArrayContext $accounts_assoc */
	$accounts_assoc = $cnx->query($query, $query_params)->fetch_assoc();
	if($accounts_assoc->is_empty()) {
		$accounts_assoc->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																			  ->set('id_account', 0)
																			  ->set('email', 'toto@toto.com')
																			  ->set('password', 'tetedecul')
																			  ->set('nom', 'Loubet')
																			  ->set('prenom', 'André')
																			  ->set('pseudo', 'HelloWorld')
																			  ->set('IP', '')
																			  ->insert()
																			  ->to_array()
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 1)
																->set('email', 'toto1@toto.com')
																->set('password', 'yahoooooo')
																->set('nom', 'Loubet')
																->set('prenom', 'Karine')
																->set('pseudo', 'HelloWorld1')
																->set('IP', '')
																->insert()
																->to_array()
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 2)
																->set('email', 'toto2@toto.com')
																->set('password', 'HarryPotter')
																->set('nom', 'Choquet')
																->set('prenom', 'Nicolas')
																->set('pseudo', 'HelloWorld2')
																->set('IP', '')
																->insert()
																->to_array()
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 3)
																->set('email', 'toto3@toto.com')
																->set('password', 'Keen_v')
																->set('nom', 'Choquet')
																->set('prenom', 'Yann')
																->set('pseudo', 'HelloWorld3')
																->set('IP', '')
																->insert()
																->to_array()
		);
	}

	/** @var ArrayContext $accounts_array */
	$accounts_array = $cnx->query($query, $query_params)->fetch_array();
	if($accounts_array->is_empty()) {
		$accounts_array->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																			  ->set('id_account', 0)
																			  ->set('email', 'toto@toto.com')
																			  ->set('password', 'tetedecul')
																			  ->set('nom', 'Loubet')
																			  ->set('prenom', 'André')
																			  ->set('pseudo', 'HelloWorld')
																			  ->set('IP', '')
																			  ->insert()
																			  ->to_array(false)
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 1)
																->set('email', 'toto1@toto.com')
																->set('password', 'yahoooooo')
																->set('nom', 'Loubet')
																->set('prenom', 'Karine')
																->set('pseudo', 'HelloWorld1')
																->set('IP', '')
																->insert()
																->to_array(false)
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 2)
																->set('email', 'toto2@toto.com')
																->set('password', 'HarryPotter')
																->set('nom', 'Choquet')
																->set('prenom', 'Nicolas')
																->set('pseudo', 'HelloWorld2')
																->set('IP', '')
																->insert()
																->to_array(false)
		)->push(\mvc_framework\core\orm\dbcontext\AccountContext::create($cnx)
																->set('id_account', 3)
																->set('email', 'toto3@toto.com')
																->set('password', 'Keen_v')
																->set('nom', 'Choquet')
																->set('prenom', 'Yann')
																->set('pseudo', 'HelloWorld3')
																->set('IP', '')
																->insert()
																->to_array(false)
		);
	}

	$accounts_object->foreach(IndexCallbacks::class.'::FetchObjectCallback');
	$accounts_assoc->foreach(IndexCallbacks::class.'::FetchCallback');
	$accounts_array->foreach(IndexCallbacks::class.'::FetchCallback');
}
catch (Exception $e) {
	echo $e->getMessage()."\n";
}