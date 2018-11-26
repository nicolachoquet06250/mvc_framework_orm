<?php

namespace mvc_framework\core\orm;


use mvc_framework\core\orm\traits\data_format;
use mvc_framework\core\orm\traits\dbcontext;
use mvc_framework\core\orm\traits\SQL;
use PHPSQLParser\PHPSQLParser;

class json {
	use SQL;
	protected $connection;
	private $connection_dir;
	protected $current_request;
	protected $select_result;

	protected $database = '', $base_directory = '';

	/**
	 * @param array $array
	 * @return bool
	 * @throws \Exception
	 */
	protected function connect(array $array): bool {
		foreach ($array as $field => $value) if(!is_numeric($field)) $this->set_prop($field, $value);
		if(!is_dir($this->get_prop('base_directory').'/'.$this->get_prop('host'))) {
			mkdir($this->get_prop('base_directory').'/'.$this->get_prop('host'), 0777, true);
		}
		$this->connection_dir = $this->get_prop('base_directory').'/'.$this->get_prop('host');
		if(isset($array['database'])) $this->select_db($this->get_prop('database'));
		return $this->connection ? true : false;
	}

	public function query($request, $params = []) {
		if ($request !== '') {
			$request = $this->get_prepared_request($request, $params);
			$parser = new PHPSQLParser($request);
			$parsed_keys = array_keys($parser->parsed);

			if(in_array('CREATE', $parsed_keys) && in_array('TABLE', $parsed_keys)) {
				$if_not_exists = strstr($parser->parsed['CREATE']['base_expr'], 'IF NOT EXISTS') ? true : false;
				$table = $parser->parsed['TABLE']['no_quotes']['parts'][0];
				$structure = $parser->parsed['TABLE']['create-def']['sub_tree'];
				$this->create_table($if_not_exists, $table, $structure);
			}
			elseif (in_array('DROP', $parsed_keys)) {
				$table = $parser->parsed['DROP']['sub_tree'][1]['sub_tree'][0]['no_quotes']['parts'][0];
				$this->drop_table($table);
			}
			elseif (in_array('INSERT', $parsed_keys)) {
				$table = $parser->parsed['INSERT'][1]['no_quotes']['parts'][0];
				$fields_list = $parser->parsed['INSERT'][2]['sub_tree'];
				$values = $parser->parsed['VALUES'][0]['data'];
				$this->insert_datas($table, $fields_list, $values);
			}
			elseif (in_array('DELETE', $parsed_keys) && in_array('FROM', $parsed_keys)) {
				$table = $parser->parsed['FROM'][0]['no_quotes']['parts'][0];
				$where = $parser->parsed['WHERE'];
				$this->delete_line($table, $where);
			}
			elseif (in_array('UPDATE', $parsed_keys)) {
				$table = $parser->parsed['UPDATE'][0]['no_quotes']['parts'][0];
				$set = $parser->parsed['SET'];
				$where = $parser->parsed['WHERE'];
				$this->update_line($table, $set, $where);
			}
			elseif (in_array('SELECT', $parsed_keys)) {
				$table = $parser->parsed['FROM'][0]['no_quotes']['parts'][0];
				$this->select_table($table, $parser->parsed);
			}
		}
	}

	private function write_parsed_in_text($parsed) {
		ob_start();
		var_dump($parsed);
		$var_dump = ob_get_contents();
		ob_clean();
		file_put_contents(__DIR__.'/../test.txt', $var_dump);
	}

	private function create_table($if_not_exists, $table, $structure) {
		$struct = [];
		foreach ($structure as $field) {
			if($field['expr_type'] === 'column-def') {
				$field_name = $field['sub_tree'][0]['no_quotes']['parts'][0];
				$type_name = $field['sub_tree'][1]['sub_tree'][0]['base_expr'];
				$default_type_size = $field['sub_tree'][1]['sub_tree'][0]['length'];
				if($field['sub_tree'][1]['sub_tree'][1]['expr_type'] === 'bracket_expression')
					$type_size = intval($field['sub_tree'][1]['sub_tree'][1]['sub_tree'][0]['base_expr']);
				else $type_size = intval($default_type_size);

				$struct[$field_name] = [
					'type' => [
						'name' => $type_name,
						'size' => $type_size,
					],
				];
				$struct[$field_name]['null'] = $field['sub_tree'][1]['nullable'];
				if($field['sub_tree'][1]['unique'])
					$struct[$field_name]['unique'] = true;
				if($field['sub_tree'][1]['auto_inc'])
					$struct[$field_name]['autoincrement'] = true;
				if($field['sub_tree'][1]['primary'])
					$struct[$field_name]['key'] = 'primary';
				$struct[$field_name]['default'] = isset($field['sub_tree'][1]['default']) ? $field['sub_tree'][1]['default'] : null;
			}
			elseif ($field['expr_type'] === 'primary-key') {
				$field_name = $field['sub_tree'][2]['sub_tree'][0]['no_quotes']['parts'][0];
				$struct[$field_name]['key'] = 'primary';
			}
		}

		if($if_not_exists && !is_file($this->connection.'/'.$table.'.json'))
			file_put_contents($this->connection.'/'.$table.'.json', json_encode(
				[
					'structure' => $struct,
					'body' => [],
				]
			));
	}

	private function select_table($table, $complete_request) {
		$fields_to_select = [];
		foreach ($complete_request['SELECT'] as $field_to_select) {
			if($field_to_select['base_expr'] === '*') {
				$fields_to_select = '*';
				break;
			}
			else {
				$alias                    = $field_to_select['alias'] === false ? null : $field_to_select['alias']['no_quotes']['parts'][0];
				$field                    = $field_to_select['no_quotes']['parts'][0];
				$fields_to_select[$field] = $alias;
			}
		}
		if(is_string($fields_to_select)) {
			$class = '\mvc_framework\core\orm\dbcontext\\'.ucfirst($table).'Context';
			$structure = array_keys($class::create($this)->get_structure());
			foreach ($structure as $id => $field) {
				$structure[$field] = null;
				unset($structure[$id]);
			}
			$fields_to_select = $structure;
		}
		$table_content = json_decode(file_get_contents($this->connection.'/'.$table.'.json'), true);
		$body = $table_content['body'];
		if(!empty($body)) {
			$_body = [];
			foreach ($body as $line) {
				$_body[] = [];
				$max = count($_body)-1;
				foreach ($fields_to_select as $field => $alias)
					if(!is_null($alias))
						$_body[$max][$alias] = $line[$field];
					else
						$_body[$max][$field] = $line[$field];
			}
			$body = $_body;
		}
		$this->select_result = $body;
	}

	private function drop_table($table) {
		$path = $this->connection.'/'.$table.'.json';
		if(is_file($path)) unlink($path);
	}

	private function delete_line($table, $where) {
		$_where = [];
		$last_key = null;
		foreach ($where as $where_detail) {
			if($where_detail['expr_type'] === 'colref') {
				$last_key = $where_detail['no_quotes']['parts'][0];
			}
			elseif ($where_detail['expr_type'] === 'const') {
				if(!is_null($last_key) && $where_detail['base_expr'] !== '' && $where_detail['base_expr'] !== '""') {
					$_where[$last_key] = substr($where_detail['base_expr'], 0, 1) === '"' ? substr($where_detail['base_expr'], 1, strlen($where_detail['base_expr'])-2) : $where_detail['base_expr'];
				}
			}
		}
		$where = $_where;
		if(is_file($this->connection.'/'.$table.'.json')) {
			$table_content = json_decode(file_get_contents($this->connection.'/'.$table.'.json'), true);
			$body = $table_content['body'];
			foreach ($body as $id => $line) {
				$valid = false;
				foreach ($line as $field => $value) {
					if(isset($where[$field]) && $where[$field] === $value) {
						$valid = true;
						break;
					}
				}
				if($valid) unset($body[$id]);
			}
			$table_content['body'] = $body;
			file_put_contents($this->connection.'/'.$table.'.json', json_encode($table_content));
		}
	}

	private function update_line($table, $set, $where) {
		$_set = [];
		foreach ($set as $line) {
			$_set[$line['sub_tree'][0]['no_quotes']['parts'][0]] = substr($line['sub_tree'][2]['base_expr'], 0, 1) === '"' ? substr($line['sub_tree'][2]['base_expr'], 1, strlen($line['sub_tree'][2]['base_expr'])-2) : $line['sub_tree'][2]['base_expr'];
		}
//		var_dump($_set);
		$primary_key = $where[0]['no_quotes']['parts'][0];
		$primary_key_value = substr($where[2]['base_expr'], 0, 1) === '"' ? substr($where[2]['base_expr'], 1, strlen($where[2]['base_expr'])-2) : $where[2]['base_expr'];
		$table_content = json_decode(file_get_contents($this->connection.'/'.$table.'.json'), true);
		$body = $table_content['body'];
		foreach ($body as $id => $line) {
			if((string)$line[$primary_key] === (string)$primary_key_value) {
				foreach ($_set as $field => $value) $body[$id][$field] = $value;
				break;
			}
		}
		$table_content['body'] = $body;
		file_put_contents($this->connection.'/'.$table.'.json', json_encode($table_content));
	}

	private function insert_datas($table, $fields_list, $values) {
		$class = '\mvc_framework\core\orm\dbcontext\\'.ucfirst($table).'Context';
		$structure = array_keys($class::create($this, data_format::$JSON)->get_structure());
		foreach ($structure as $id => $field) {
			$structure[$field] = null;
			unset($structure[$id]);
		}
		$fields = [];
		foreach ($fields_list as $field_detail) {
			if(in_array($field_detail['no_quotes']['parts'][0], array_keys($structure)))
				$fields[$field_detail['no_quotes']['parts'][0]] = null;
			else {
				$fields = false;
				break;
			}
		}
		if($fields) {
			foreach ($values as $id => $field_detail) {
				$key = array_keys($fields)[$id];
				$fields[$key] = substr($field_detail['base_expr'], 0, 1) === '"' ? substr($field_detail['base_expr'], 1, strlen($field_detail['base_expr'])-2) : $field_detail['base_expr'];
			}

			$table_content = json_decode(file_get_contents($this->connection.'/'.$table.'.json'), true);
			$body = $table_content['body'];
			$body[] = $fields;
			$table_content['body'] = $body;
			file_put_contents($this->connection.'/'.$table.'.json', json_encode($table_content));
		}
	}

	public function fetch_row() {
		return $this->fetch_array();
	}

	public function fetch_array() {
		$body = $this->select_result;
		$_body = [];
		foreach ($body as $id => $line) {
			$_body[$id] = [];
			foreach ($line as $value) {
				$_body[$id][] = $value;
			}
		}
		return $_body;
	}

	public function fetch_assoc() {
		return $this->select_result;
	}

	public function fetch_object($class_name = \stdClass::class, $params = []) {
		if(class_exists($class_name)) {
			$objs = [];
			foreach ($this->fetch_assoc() as $line) {
				/**
				 * @var dbcontext $data_obj
				 */
				$data_obj = $class_name::create($this);
				foreach ($line as $field => $value) {
					$data_obj->set($field, $value);
				}
				$objs[] = $data_obj;
			}
			return $objs;
		}
		return null;
	}

	/**
	 * @param $db
	 * @throws \Exception
	 */
	public function select_db($db) {
		if(!is_dir($this->connection_dir.'/'.$db)) {
			mkdir($this->connection_dir.'/'.$db, 0777, true);
		}
		$this->connection = $this->connection_dir.'/'.$db;
	}

	public function add()
	{
		// TODO: Implement add() method.
	}

	public function constraint()
	{
		// TODO: Implement constraint() method.
	}

	public function alter()
	{
		// TODO: Implement alter() method.
	}

	public function column()
	{
		// TODO: Implement column() method.
	}

	public function table()
	{
		// TODO: Implement table() method.
	}

	public function all()
	{
		// TODO: Implement all() method.
	}

	public function and()
	{
		// TODO: Implement and() method.
	}

	public function any()
	{
		// TODO: Implement any() method.
	}

	public function as()
	{
		// TODO: Implement as() method.
	}

	public function asc()
	{
		// TODO: Implement asc() method.
	}

	public function backup_database()
	{
		// TODO: Implement backup_database() method.
	}

	public function between()
	{
		// TODO: Implement between() method.
	}

	public function case()
	{
		// TODO: Implement case() method.
	}

	public function check()
	{
		// TODO: Implement check() method.
	}

	public function create()
	{
		// TODO: Implement create() method.
	}

	public function database()
	{
		// TODO: Implement database() method.
	}

	public function index()
	{
		// TODO: Implement index() method.
	}

	public function or_replace()
	{
		// TODO: Implement or_replace() method.
	}

	public function procedure()
	{
		// TODO: Implement procedure() method.
	}

	public function unique()
	{
		// TODO: Implement unique() method.
	}

	public function view()
	{
		// TODO: Implement view() method.
	}

	public function default()
	{
		// TODO: Implement default() method.
	}

	public function delete()
	{
		// TODO: Implement delete() method.
	}

	public function desc()
	{
		// TODO: Implement desc() method.
	}

	public function distinct()
	{
		// TODO: Implement distinct() method.
	}

	public function drop()
	{
		// TODO: Implement drop() method.
	}

	public function exec()
	{
		// TODO: Implement exec() method.
	}

	public function exists()
	{
		// TODO: Implement exists() method.
	}

	public function foreign()
	{
		// TODO: Implement foreign() method.
	}

	public function from($table)
	{
		// TODO: Implement from() method.
	}

	public function full()
	{
		// TODO: Implement full() method.
	}

	public function join()
	{
		// TODO: Implement join() method.
	}

	public function group()
	{
		// TODO: Implement group() method.
	}

	public function by()
	{
		// TODO: Implement by() method.
	}

	public function having()
	{
		// TODO: Implement having() method.
	}

	public function in()
	{
		// TODO: Implement in() method.
	}

	public function inner()
	{
		// TODO: Implement inner() method.
	}

	public function insert()
	{
		// TODO: Implement insert() method.
	}

	public function into()
	{
		// TODO: Implement into() method.
	}

	public function select($fields)
	{
		// TODO: Implement select() method.
	}

	public function is()
	{
		// TODO: Implement is() method.
	}

	public function null()
	{
		// TODO: Implement null() method.
	}

	public function not()
	{
		// TODO: Implement not() method.
	}

	public function left()
	{
		// TODO: Implement left() method.
	}

	public function like()
	{
		// TODO: Implement like() method.
	}

	public function limit()
	{
		// TODO: Implement limit() method.
	}

	public function or()
	{
		// TODO: Implement or() method.
	}

	public function order()
	{
		// TODO: Implement order() method.
	}

	public function outer()
	{
		// TODO: Implement outer() method.
	}

	public function primary()
	{
		// TODO: Implement primary() method.
	}

	public function key()
	{
		// TODO: Implement key() method.
	}

	public function right()
	{
		// TODO: Implement right() method.
	}

	public function rownum()
	{
		// TODO: Implement rownum() method.
	}

	public function top()
	{
		// TODO: Implement top() method.
	}

	public function set()
	{
		// TODO: Implement set() method.
	}

	public function truncate()
	{
		// TODO: Implement truncate() method.
	}

	public function union()
	{
		// TODO: Implement union() method.
	}

	public function values()
	{
		// TODO: Implement values() method.
	}

	public function where()
	{
		// TODO: Implement where() method.
	}

	public function update()
	{
		// TODO: Implement update() method.
	}

	public static function ascii()
	{
		// TODO: Implement ascii() method.
	}

	public static function char_length()
	{
		// TODO: Implement char_length() method.
	}

	public static function character_length()
	{
		// TODO: Implement character_length() method.
	}

	public static function concat()
	{
		// TODO: Implement concat() method.
	}

	public static function concat_ws()
	{
		// TODO: Implement concat_ws() method.
	}

	public static function field()
	{
		// TODO: Implement field() method.
	}

	public static function find_in_set()
	{
		// TODO: Implement find_in_set() method.
	}

	public static function format()
	{
		// TODO: Implement format() method.
	}

	public static function _insert()
	{
		// TODO: Implement _insert() method.
	}

	public static function instr()
	{
		// TODO: Implement instr() method.
	}

	public static function lcase()
	{
		// TODO: Implement lcase() method.
	}

	public static function _left()
	{
		// TODO: Implement _left() method.
	}

	public static function length()
	{
		// TODO: Implement length() method.
	}

	public static function locate()
	{
		// TODO: Implement locate() method.
	}

	public static function lower()
	{
		// TODO: Implement lower() method.
	}

	public static function lpad()
	{
		// TODO: Implement lpad() method.
	}

	public static function ltrim()
	{
		// TODO: Implement ltrim() method.
	}

	public static function mid()
	{
		// TODO: Implement mid() method.
	}

	public static function position()
	{
		// TODO: Implement position() method.
	}

	public static function pepeat()
	{
		// TODO: Implement pepeat() method.
	}

	public static function replace()
	{
		// TODO: Implement replace() method.
	}

	public static function reverse()
	{
		// TODO: Implement reverse() method.
	}

	public static function _right()
	{
		// TODO: Implement _right() method.
	}

	public static function rpad()
	{
		// TODO: Implement rpad() method.
	}

	public static function rtrim()
	{
		// TODO: Implement rtrim() method.
	}

	public static function space()
	{
		// TODO: Implement space() method.
	}

	public static function strcmp()
	{
		// TODO: Implement strcmp() method.
	}

	public static function substr()
	{
		// TODO: Implement substr() method.
	}

	public static function substring()
	{
		// TODO: Implement substring() method.
	}

	public static function substring_index()
	{
		// TODO: Implement substring_index() method.
	}

	public static function trim()
	{
		// TODO: Implement trim() method.
	}

	public static function ucase()
	{
		// TODO: Implement ucase() method.
	}

	public static function upper()
	{
		// TODO: Implement upper() method.
	}
}