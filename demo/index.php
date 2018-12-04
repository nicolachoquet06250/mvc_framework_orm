<?php

require_once __DIR__.'/autoload.php';

use mvc_framework\core\orm\classes\IndexCallbacks;
use mvc_framework\core\orm\services\ArrayContext;
use \mvc_framework\core\orm\traits\data_format;
use \mvc_framework\core\orm\dbcontext\AccountContext;
use \mvc_framework\core\orm\traits\connection_template;

class index extends Runable {
	use \mvc_framework\core\orm\traits\singleton;
	public $format;
	public function __construct($format) {
		parent::__construct();
		$this->format = $format;
	}

	protected function start() {
		$supported_connection_template = ArrayContext::create('object', connection_template::class);
		$supported_connection_template->push(
			$this->loader->get_service_sql_connection()->get_json(
				__DIR__.'/datas',
				'localhost',
				'nicolas-choquet_budgets'
			),
			data_format::$JSON
		)->push(
			$this->loader->get_service_sql_connection()->get_mysqli(
				'mysql-nicolas-choquet.alwaysdata.net',
				143175,
				'2669NICOLAS2107',
				'nicolas-choquet_budgets'
			),
			data_format::$MYSQLI
		);
		$cnx = $this->loader->get_service_sql()->get_sql($supported_connection_template->get($this->format));
		$table = AccountContext::create($cnx);
		$table->create_table(true);

		$query = 'SELECT * FROM `?table` WHERE `id_account`="1"';
		$query_params = ArrayContext::create('string')->init('table', $table->get_table_name());

		/** @var ArrayContext $accounts_object */
		$accounts_object = $cnx->query($query, $query_params)->fetch_object($table->get_class());
		if($accounts_object->is_empty()) {
			$accounts_object->push(AccountContext::create($cnx)
												 ->set('id_account', 0)
												 ->set('eail', 'toto@toto.com')
												 ->set('password', 'tetedecul')
												 ->set('nom', 'Loubet')
												 ->set('prenom', 'André')
												 ->set('pseudo', 'HelloWorld')
												 ->set('IP', '')
												 ->insert()
												 ->to_array()
			)->push(AccountContext::create($cnx)
								  ->set('id_account', 1)
								  ->set('email', 'toto1@toto.com')
								  ->set('password', 'yahoooooo')
								  ->set('nom', 'Loubet')
								  ->set('prenom', 'Karine')
								  ->set('pseudo', 'HelloWorld1')
								  ->set('IP', '')
								  ->insert()
								  ->to_array()
			)->push(AccountContext::create($cnx)
								  ->set('id_account', 2)
								  ->set('email', 'toto2@toto.com')
								  ->set('password', 'HarryPotter')
								  ->set('nom', 'Choquet')
								  ->set('prenom', 'Nicolas')
								  ->set('pseudo', 'HelloWorld2')
								  ->set('IP', '')
								  ->insert()
								  ->to_array()
			)->push(AccountContext::create($cnx)
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
		$accounts_object = $cnx->query($query, $query_params)->fetch_object($table->get_class());

		/** @var ArrayContext $accounts_assoc */
		$accounts_assoc = $cnx->query($query, $query_params)->fetch_assoc();
		if($accounts_assoc->is_empty()) {
			$accounts_assoc->push(AccountContext::create($cnx)
												->set('id_account', 0)
												->set('email', 'toto@toto.com')
												->set('password', 'tetedecul')
												->set('nom', 'Loubet')
												->set('prenom', 'André')
												->set('pseudo', 'HelloWorld')
												->set('IP', '')
												->insert()
												->to_array()
			)->push(AccountContext::create($cnx)
								  ->set('id_account', 1)
								  ->set('email', 'toto1@toto.com')
								  ->set('password', 'yahoooooo')
								  ->set('nom', 'Loubet')
								  ->set('prenom', 'Karine')
								  ->set('pseudo', 'HelloWorld1')
								  ->set('IP', '')
								  ->insert()
								  ->to_array()
			)->push(AccountContext::create($cnx)
								  ->set('id_account', 2)
								  ->set('email', 'toto2@toto.com')
								  ->set('password', 'HarryPotter')
								  ->set('nom', 'Choquet')
								  ->set('prenom', 'Nicolas')
								  ->set('pseudo', 'HelloWorld2')
								  ->set('IP', '')
								  ->insert()
								  ->to_array()
			)->push(AccountContext::create($cnx)
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
			$accounts_array->push(AccountContext::create($cnx)
												->set('id_account', 0)
												->set('email', 'toto@toto.com')
												->set('password', 'tetedecul')
												->set('nom', 'Loubet')
												->set('prenom', 'André')
												->set('pseudo', 'HelloWorld')
												->set('IP', '')
												->insert()
												->to_array(false)
			)->push(AccountContext::create($cnx)
								  ->set('id_account', 1)
								  ->set('email', 'toto1@toto.com')
								  ->set('password', 'yahoooooo')
								  ->set('nom', 'Loubet')
								  ->set('prenom', 'Karine')
								  ->set('pseudo', 'HelloWorld1')
								  ->set('IP', '')
								  ->insert()
								  ->to_array(false)
			)->push(AccountContext::create($cnx)
								  ->set('id_account', 2)
								  ->set('email', 'toto2@toto.com')
								  ->set('password', 'HarryPotter')
								  ->set('nom', 'Choquet')
								  ->set('prenom', 'Nicolas')
								  ->set('pseudo', 'HelloWorld2')
								  ->set('IP', '')
								  ->insert()
								  ->to_array(false)
			)->push(AccountContext::create($cnx)
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

		try {
			$accounts_object->foreach(IndexCallbacks::class.'::FetchObjectCallback');
			$accounts_assoc->foreach(IndexCallbacks::class.'::FetchCallback');
			$accounts_array->foreach(IndexCallbacks::class.'::FetchCallback');
		}
		catch (ReflectionException $e) {
			echo $e->getMessage()."\n";
		}
	}
}

index::create(data_format::$JSON);