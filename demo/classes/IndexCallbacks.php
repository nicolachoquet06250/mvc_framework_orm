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
		$account->to_array()->foreach(IndexCallbacks::class.'::FetchInterneCallback');
		if($id === 1)
			$account->set('pseudo', 'nouveau_pseudo2')
					->update();
	}

	/**
	 * @param ArrayContext $value
	 * @throws \ReflectionException
	 */
	public static function FetchCallback(ArrayContext $value) {
		$value->foreach(IndexCallbacks::class.'::FetchInterneCallback');
	}

	public static function FetchInterneCallback($key, $value) {
		var_dump($key, $value);
	}
}