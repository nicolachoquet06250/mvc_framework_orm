<?php

namespace mvc_framework\core\orm\traits;


trait SQL {
	protected $is_connected = false;
	protected $host = '', $username = '', $password = '', $db = '';
	protected $query = [];

	public static $ADD = '+';
	public static $SUBTRACT = '-';
	public static $MULTIPLY = '*';
	public static $DIVIDE = '/';
	public static $MODULO = '%';
	public static $AND = '&';
	public static $OR = '|';
	public static $OR_EXCLU = '^';
	public static $EQUAL = '=';
	public static $GREATER_THAN = '>';
	public static $LESS_THAN = '<';
	public static $GREATER_THAN_OR_EQUAL = '>=';
	public static $LESS_THAN_OR_EQUAL = '<=';
	public static $NOT_EQUAL = '<>';

	/**
	 * @param $key
	 * @param $value
	 * @return SQL
	 * @throws \Exception
	 */
	public function set_prop($key, $value) {
		if(isset($this->$key) ||
		   (is_string($this->$key) && $this->$key === '') ||
		   (is_array($this->$key) && empty($this->$key)) ||
		   (is_bool($this->$key) && $this->$key === false)) {
			$this->$key = $value;
			return $this;
		}
		else throw new \Exception('Fatal : '.__CLASS__.'::$'.$key.' not found !');
	}

	/**
	 * @param $key
	 * @return mixed
	 * @throws \Exception
	 */
	public function get_prop($key) {
		if(isset($this->$key)) return $this->$key;
		else throw new \Exception('Fatal : '.__CLASS__.'::$'.$key.' not found !');
	}

	/**
	 * SQL constructor.
	 *
	 * @param array|connection_template $array
	 */
	public function __construct($array) {
		if(is_object($array)) $array = $array->to_array;
		$this->is_connected = $this->connect($array);
	}
	/**
	 * @return boolean
	 */
	public function is_connected() {
		return $this->is_connected;
	}
	public function get_query() {
		return $this->query;
	}

	abstract protected function connect(array $array): bool ;

	abstract public function query($request, $params = []);

	abstract public function fetch_row();
	abstract public function fetch_array($result_type = MYSQLI_BOTH);
	abstract public function fetch_assoc();
	abstract public function fetch_object($class_name = \stdClass::class, $params = []);
	abstract public function select_db($db);

	// keywords
	abstract public function add();
	abstract public function constraint();
	abstract public function alter();
	abstract public function column();
	abstract public function table();
	abstract public function all();
	abstract public function and();
	abstract public function any();
	abstract public function as();
	abstract public function asc();
	abstract public function backup_database();
	abstract public function between();
	abstract public function case();
	abstract public function check();
	abstract public function create();
	abstract public function database();
	abstract public function index();
	abstract public function or_replace();
	abstract public function procedure();
	abstract public function unique();
	abstract public function view();
	abstract public function default();
	abstract public function delete();
	abstract public function desc();
	abstract public function distinct();
	abstract public function drop();
	abstract public function exec();
	abstract public function exists();
	abstract public function foreign();
	abstract public function from($table);
	abstract public function full();
	abstract public function join();
	abstract public function group();
	abstract public function by();
	abstract public function having();
	abstract public function in();
	abstract public function inner();
	abstract public function insert();
	abstract public function into();
	abstract public function select($fields);
	abstract public function is();
	abstract public function null();
	abstract public function not();
	abstract public function left();
	abstract public function like();
	abstract public function limit();
	abstract public function or();
	abstract public function order();
	abstract public function outer();
	abstract public function primary();
	abstract public function key();
	abstract public function right();
	abstract public function rownum();
	abstract public function top();
	abstract public function set();
	abstract public function truncate();
	abstract public function union();
	abstract public function values();
	abstract public function where();
	abstract public function update();

	// strings functions
	abstract public static function ascii();
	abstract public static function char_length();
	abstract public static function character_length();
	abstract public static function concat();
	abstract public static function concat_ws();
	abstract public static function field();
	abstract public static function find_in_set();
	abstract public static function format();
	abstract public static function _insert();
	abstract public static function instr();
	abstract public static function lcase();
	abstract public static function _left();
	abstract public static function length();
	abstract public static function locate();
	abstract public static function lower();
	abstract public static function lpad();
	abstract public static function ltrim();
	abstract public static function mid();
	abstract public static function position();
	abstract public static function pepeat();
	abstract public static function replace();
	abstract public static function reverse();
	abstract public static function _right();
	abstract public static function rpad();
	abstract public static function rtrim();
	abstract public static function space();
	abstract public static function strcmp();
	abstract public static function substr();
	abstract public static function substring();
	abstract public static function substring_index();
	abstract public static function trim();
	abstract public static function ucase();
	abstract public static function upper();


	// TODO numeric functions
	// TODO dates functions
	// TODO advenced functions

	// TODO https://www.w3schools.com/sql/sql_operators.asp
}