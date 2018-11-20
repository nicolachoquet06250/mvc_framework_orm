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
	while (list($id, $email, $password, $nom, $prenom, $pseudo, $ip) = $mysqli->fetch_row()) {
		var_dump($id, $email, $password, $nom, $prenom, $pseudo, $ip);
	}

	$account = new \mvc_framework\core\orm\dbcontext\AccountContext();
	var_dump($account->get_structure());
}
catch (Exception $e) {
	echo $e->getMessage()."\n";
}