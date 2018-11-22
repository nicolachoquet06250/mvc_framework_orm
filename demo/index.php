<?php

use \mvc_framework\core\orm\mysqli;
use \mvc_framework\core\orm\json;

require_once __DIR__.'/autoload.php';

try {
	$data_type = 'json';

	if($data_type === 'mysqli') {
		$cnx = new mysqli(\mvc_framework\core\orm\connection_templates\mysqli::get(
			'mysql-nicolas-choquet.alwaysdata.net',
			143175,
			'2669NICOLAS2107',
			'nicolas-choquet_budgets'
		));
		$cnx->query('SELECT * FROM `?table`', [
			'table' => 'account',
		]);
		/** @var \mvc_framework\core\orm\dbcontext\AccountContext[] $accounts */
		$accounts = $cnx->fetch_object(\mvc_framework\core\orm\dbcontext\AccountContext::class);
		$accounts[0]->create_table(true);
	}
	elseif ($data_type === 'json') {
		$cnx = new json(\mvc_framework\core\orm\connection_templates\json::get(
			__DIR__.'/datas',
			'localhost',
			'nicolas-choquet_budgets'
		));
//		(new \mvc_framework\core\orm\dbcontext\AccountContext($cnx, \mvc_framework\core\orm\traits\data_format::$JSON))->create_table(true);
		$cnx->query('SELECT id_account id, email FROM `?table`', [
			'table' => 'account',
		]);
	}
}
catch (Exception $e) {
	echo $e->getMessage()."\n";
}