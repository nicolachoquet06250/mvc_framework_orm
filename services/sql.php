<?php

namespace mvc_framework\core\orm\services;

use mvc_framework\core\orm\SQLFactory;
use mvc_framework\core\orm\traits\connection_template;

class sql {
	public function get_sql(connection_template $cnx) {
		return SQLFactory::get($cnx);
	}
}