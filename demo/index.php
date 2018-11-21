<?php

use \mvc_framework\core\orm\mysqli;
use \mvc_framework\core\orm\json;

require_once __DIR__.'/autoload.php';

try {
//	$mysqli = new mysqli(\mvc_framework\core\orm\connection_templates\mysqli::get(
//		'mysql-nicolas-choquet.alwaysdata.net',
//		143175,
//		'2669NICOLAS2107',
//		'nicolas-choquet_budgets'
//	));

	$json = new json(\mvc_framework\core\orm\connection_templates\json::get(
		__DIR__.'/datas',
		'localhost',
		'nicolas-choquet_budgets'
	));

//	/*$mysqli*/$json->query('SELECT * FROM `?table`', [
//		'table' => 'account',
//	]);
	/** @var \mvc_framework\core\orm\dbcontext\AccountContext[] $accounts */
//	$accounts = /*$mysqli*/$json->fetch_object(\mvc_framework\core\orm\dbcontext\AccountContext::class);
//	$accounts[0]->create_table(true);
	(new \mvc_framework\core\orm\dbcontext\AccountContext($json, \mvc_framework\core\orm\traits\data_format::$JSON))->create_table(true);
}
catch (Exception $e) {
	echo $e->getMessage()."\n";
}