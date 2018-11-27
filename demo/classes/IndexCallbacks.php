<?php

namespace mvc_framework\core\orm\classes;

use mvc_framework\core\orm\dbcontext\AccountContext;

class IndexCallbacks {
	public static function ForeachCallback($id, AccountContext $account) {
	var_dump($account->get('email'));
	var_dump($account->to_array());
	if($id === 1)
		$account->set('pseudo', 'nouveau_pseudo2')
				->update();
}
}