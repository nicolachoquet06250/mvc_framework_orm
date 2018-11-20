<?php

namespace mvc_framework\core\orm;


use mvc_framework\core\orm\traits\SQL;

class mysqli {
	use SQL;
	/**
	 * @var \mysqli $connection
	 */
	protected $connection;
	/**
	 * @var \mysqli_result $current_request
	 */
	protected $current_request;

	/**
	 * @param array $array
	 * @return bool
	 * @throws \Exception
	 */
	protected function connect(array $array): bool {
		foreach ($array as $field => $value) if(!is_numeric($field)) $this->set_prop($field, $value);
		$this->connection = new \mysqli($this->get_prop('host'), $this->get_prop('username'), $this->get_prop('password'));
		if(isset($array['db'])) $this->select_db($this->get_prop('db'));
		return $this->connection ? true : false;
	}

	public function query($request = '', $params = []) {
		if ($request !== '') {
			if(!empty($params)) {
				foreach ($params as $key => $param) {
					$request = str_replace('?'.$key, $param, $request);
				}
			}
			$this->query           = $request;
			$this->current_request = $this->connection->query($request);
		}
	}

	public function fetch_row() {
		return $this->current_request->fetch_row();
	}

	public function fetch_array($result_type = MYSQLI_BOTH) {
		return $this->current_request->fetch_array($result_type);
	}

	public function fetch_assoc() {
		return $this->current_request->fetch_assoc();
	}

	public function fetch_object($class_name = \stdClass::class, $params = []) {
		return $this->current_request->fetch_object($class_name, $params);
	}

	/**
	 * @param $db
	 * @throws \Exception
	 */
	public function select_db($db) {
		$this->set_prop('db', $db);
		$this->connection->select_db($this->get_prop('db'));
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

	public function from($table) {
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

	public function select($fields) {
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