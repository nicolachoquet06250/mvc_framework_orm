<?php

namespace mvc_framework\core\orm\data_formats;


use mvc_framework\core\orm\traits\data_format;

class json {
	use data_format;
	protected $structure;
	protected $table;
	/**
	 * @var \mvc_framework\core\orm\json $connection
	 */
	protected $connection;

	public function __construct($structure, $table, $connection) {
		$this->refresh_structure($structure);
		$this->set_table($table);
		$this->set_connection($connection);
	}

	public function create_table($if_not_exists = false) {
		$structure = $this->structure;
		$query_create = 'CREATE TABLE ${if_not_exists}`'.$this->table.'` ('."\n";
		foreach ($structure as $field_name => $field_caracteristics) {
			$query_create .= "\t".$field_name.' '.$field_caracteristics['type']['name'].'('.$field_caracteristics['type']['size'].')';
			if($field_caracteristics['default'] === null && !$field_caracteristics['null']) {
				$query_create .= ' NOT NULL';
			}
			elseif ($field_caracteristics['default'] === null && $field_caracteristics['null']) {
				$query_create .= ' DEFAULT NULL';
			}
			elseif($field_caracteristics['default'] !== null && $field_caracteristics['null']) {
				$query_create .= ' NOT NULL DEFAULT '.($field_caracteristics['type']['name'] === 'numeric' || $field_caracteristics['type']['name'] === 'integer' || $field_caracteristics['type']['name'] === 'int' ? intval($field_caracteristics['default']) : '"'.$field_caracteristics['default'].'"');
			}
			else {
				$query_create .= ' DEFAULT '.($field_caracteristics['type']['name'] === 'numeric' || $field_caracteristics['type']['name'] === 'integer' || $field_caracteristics['type']['name'] === 'int' ? intval($field_caracteristics['default']) : '"'.$field_caracteristics['default'].'"');
			}
			if(isset($field_caracteristics['autoincrement']) && $field_caracteristics['autoincrement']) {
				$query_create .= ' AUTO_INCREMENT';
			}
			$query_create .= ', '."\n";
		}
		$max = count($structure);
		$i = 0;
		foreach ($structure as $field_name => $field_caracteristics) {
			if(isset($field_caracteristics['key'])) {
				$query_create .= "\t".strtoupper($field_caracteristics['key']).' KEY (`'.$field_name.'`)';
			}
			elseif (isset($field_caracteristics['unique'])) {
				$query_create .= "\t".'UNIQUE (`'.$field_name.'`)';
			}
			if($max < $i ) $query_create .= ', '."\n";
			$i++;
		}
		$query_create.= "\n".')';

		$query_drop = 'DROP TABLE `'.$this->table.'`';

		$query_create = $if_not_exists ? str_replace('${if_not_exists}', 'IF NOT EXISTS ', $query_create) : str_replace('${if_not_exists}', '', $query_create);

		if(!$if_not_exists) $this->connection->query($query_drop);
		$this->connection->query($query_create);
	}

	public function insert() {
		var_dump($this->structure);
	}

	public function update() {
		var_dump($this->structure);
	}

	public function delete() {
		var_dump($this->structure);
	}

	public function save() {
		var_dump($this->structure);
	}

	public function set_table($table) {
		$this->table = $table;
	}

	public function set_connection($connection) {
		$this->connection = $connection;
	}

	public function refresh_structure($structure) {
		$this->structure = $structure;
	}
}