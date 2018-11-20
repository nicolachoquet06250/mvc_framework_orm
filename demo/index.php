<?php

use \mvc_framework\core\orm\mysqli;

require_once __DIR__.'/autoload.php';

try {
	$mysqli = new mysqli(\mvc_framework\core\orm\connection_templates\mysqli::get(
		'mysql-nicolas-choquet.alwaysdata.net',
		143175,
		'2669NICOLAS2107',
		'nicolas-choquet_budgets'
	));

	$mysqli->query('SELECT * FROM `?table`', [
		'table' => 'account',
	]);
	/**
	 * @var \mvc_framework\core\orm\dbcontext\AccountContext[] $accounts
	 */
	$accounts = $mysqli->fetch_object(\mvc_framework\core\orm\dbcontext\AccountContext::class);
	var_dump($accounts[0]->get('id_account'));
//	$accounts[0]->create_table(true);
}
catch (Exception $e) {
	echo $e->getMessage()."\n";
}