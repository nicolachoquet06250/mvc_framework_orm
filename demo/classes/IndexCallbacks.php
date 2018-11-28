<?php

namespace mvc_framework\core\orm\classes;

use mvc_framework\core\orm\dbcontext\AccountContext;
use mvc_framework\core\orm\services\ArrayContext;

class IndexCallbacks {
	/**
	 * @param $id
	 * @param AccountContext $account
	 * @throws \ReflectionException
	 */
	public static function FetchObjectCallback($id, AccountContext $account) {
//		var_dump($account->to_array()->get());
		if($id === 1)
			$account->set('pseudo', 'nouveau_pseudo2')
					->update();

		$__ = $account->get_where('pseudo', 'nouveau_pseudo2');

		var_dump($__);
	}

	/**
	 * @param ArrayContext $value
	 */
	public static function FetchCallback(ArrayContext $value) {
//		var_dump($value->get());
	}
}