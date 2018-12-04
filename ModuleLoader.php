<?php

namespace mvc_framework\core\orm;

use mvc_framework\core\orm\services\ArrayContext;
use mvc_framework\core\orm\services\sql;
use mvc_framework\core\orm\services\sql_connection;
use PHPSQLParser\PHPSQLParser;

/**
 * Class ModuleLoader
 *
 * @package mvc_framework\core\orm
 *
 * @method PHPSQLParser get_tool_sql_parser()
 * @method sql get_service_sql()
 * @method sql_connection get_service_sql_connection()
 */
class ModuleLoader {
	protected $charged = [
		'service' => 'services',
		'tool' => 'utils',
		'model' => 'models',
		'controller' => 'controllers',
		'view' => 'views',
	];
	protected $services = [];
	protected $utils = [];
	protected $models = [];
	protected $controllers = [];
	protected $views = [];
	protected $router = [];

	public function __construct() {
		if(!is_null($this->services)) {
			$this->services = [
				'sql_parser' => PHPSQLParser::class,
				'sql' => sql::class,
				'sql_connection' => sql_connection::class
			];
			$this->utils = [
				'array' => ArrayContext::class,
			];
		}
	}

	public function __call($name, $arguments) {
		foreach ($this->charged as $_name => $prop) {
			if(strstr($name, 'get_'.$_name.'_')) {
				if(!is_null($this->$prop)) {
					$key = str_replace('get_'.$_name.'_', '', $name);
					if(isset($this->$prop[$key])) {
						return new $this->$prop[$key]();
					}
				}
			}
		}
		return null;
	}
}