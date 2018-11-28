<?php

namespace mvc_framework\core\orm\data_formats;


use mvc_framework\core\orm\traits\data_format;
use mvc_framework\core\orm\traits\SQL;

class mysqli {
	use data_format;
	protected $structure;
	protected $table;
	/**
	 * @var SQL $connection
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
		$query_create.= "\n".') ENGINE=MyISAM DEFAULT CHARSET=utf8;';

		$query_drop = 'DROP TABLE `'.$this->table.'`';

		$query_create = $if_not_exists ? str_replace('${if_not_exists}', 'IF NOT EXISTS ', $query_create) : str_replace('${if_not_exists}', '', $query_create);

		if(!$if_not_exists) $this->connection->query($query_drop);
		$this->connection->query($query_create);
		return $this;
	}

	public function insert() {
		$query_insert = 'INSERT INTO `?table` (?fields) VALUES(?values)';
		$fields = [];
		$values = [];
		foreach ($this->structure as $field_name => $field_detail) {
			$fields[] = $field_name;
			$values[] = $field_detail['value'];
		}
		$this->connection->query($query_insert, [
			'table' => $this->table,
			'fields' => '`'.implode('`, `', $fields).'`',
			'values' => '"'.implode('", "', $values).'"',
		]);
		return $this;
	}

	public function update() {
		$query_update = 'UPDATE `?table` SET ?new_values WHERE `?primary_key`="?primary_key_value"';
		$primary_key = '';
		$primary_key_value = null;
		foreach ($this->structure as $field_name => $field) {
			if(isset($field['key']) && $field['key'] === 'primary') {
				$primary_key = $field_name;
				$primary_key_value = $field['value'];
				break;
			}
		}
		$this->connection->query('SELECT * FROM `?table`', [
			'table' => $this->table,
		]);
		$result_before_update = $this->connection->fetch_assoc();
		$before_update = [];
		foreach ($result_before_update as $id => $line) {
			if((string)$line[$primary_key] === (string)$this->structure[$primary_key]['value']) {
				$before_update = $line;
				break;
			}
		}
		$to_update = [];
		if(!empty($before_update)) {
			foreach ($before_update as $field => $value) {
				if((string)$value !== (string)$this->structure[$field]['value']) {
					$to_update[$field] = $this->structure[$field]['value'];
				}
			}
		}
		$new_values = '';
		$max = count($to_update)-1;
		$i = 0;
		foreach ($to_update as $field => $value) {
			$new_values .= '`'.$field.'`="'.$value.'"';
			if($i < $max) {
				$new_values .= ', ';
			}
			$i++;
		}
		$this->connection->query($query_update, [
			'table' => $this->table,
			'new_values' => $new_values,
			'primary_key_value' => $primary_key_value,
			'primary_key' => $primary_key,
		]);
		return $this;
	}

	public function delete() {
		$query_delete = 'DELETE FROM `?table` WHERE ';
		$max = count($this->structure);
		$i = 0;
		foreach ($this->structure as $field_name => $field_detail) {
			$query_delete .= '`'.$field_name.'`="'.$field_detail['value'].'"';
			if($i < $max) {
				$query_delete .= ' AND';
			}
			$i++;
		}
		$this->connection->query($query_delete, [
			'table' => $this->get_table_name(),
		]);
		return $this;
	}

	public function drop() {
		$this->connection->query('DROP TABLE IF EXISTS `?table`', [
			'table' => $this->get_table_name()
		]);
		return $this;
	}

	public function save() {
		var_dump($this->structure);
		return $this;
	}

	public function get_where($prop, $value, $current_class) {
		$this->connection->query('SELECT * FROM `?table` WHERE `?prop`="?value"', [
			'table' => $this->table,
			'prop' => $prop,
			'value' => $value,
		]);
		return $this->connection->fetch_object($current_class);
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