# mvc_framework_orm

- Grm adaptatif sur n'importe quel projet.
- Gère les formats JSON et MYSQL.
- Gère les multiconnections.
- Gère le renvoie du résultat d'un select en objet directement en passant une classe en paramètre de la méthode. ( cette classe devra utiliser le trait **dbcontext** )
`$mysqli->query('SELECT * FROM `?table`', [
 	'table' => 'account',
 ]);
 /** @var \mvc_framework\core\orm\dbcontext\AccountContext[] $accounts */
 $accounts = $mysqli->fetch_object(\mvc_framework\core\orm\dbcontext\AccountContext::class);
 $accounts[0]->create_table(true);`