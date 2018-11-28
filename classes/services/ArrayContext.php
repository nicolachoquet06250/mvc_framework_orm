<?php

namespace mvc_framework\core\orm\services;


use ReflectionFunction;

class ArrayContext {
	private $array = [], $arrayOf = null, $classOfArray = null;
	private $array_rollback = [];
	public $id;

	private function __construct($arrayOf = 'mixed', $classOfArray = null) {
		$this->arrayOf = $arrayOf;
		$this->classOfArray = $classOfArray;
		$this->id = rand(0, 255);
	}

	public static function create($arrayOf = 'mixed', $classOfArray = null) {
		return new ArrayContext($arrayOf, $classOfArray);
	}

	public function reset() {
		$this->array = [];
	}

	public function __clone() {
		return $this;
	}

	public function push($mixed, $key = null) {
		if(gettype($mixed) === $this->arrayOf) {
			if ($this->arrayOf === 'object' && get_class($mixed) === $this->classOfArray) {
				if (!is_null($key)) $this->array[$key] = $mixed;
				else array_push($this->array, $mixed);
			}
			else {
				if(!is_null($key)) $this->array[$key] = $mixed;
				else array_push($this->array, $mixed);
			}
		}
		elseif ($this->arrayOf === 'integer') {
			$mixed = $this->clean_value($mixed);
			if($this->arrayOf === gettype($mixed)) {
				if (!is_null($key)) $this->array[$key] = $mixed;
				else array_push($this->array, $mixed);
			}
		}
		elseif($this->arrayOf === 'mixed') {
			if(!is_null($key)) $this->array[$key] = $this->clean_value($mixed);
			else array_push($this->array, $this->clean_value($mixed));
		}
		return $this;
	}

	public function get($key = null) {
		if(is_null($key)) return $this->array;
		return isset($this->array[$key]) ? $this->array[$key] : null;
	}

	/**
	 * @param callable|string $callback
	 * @param array ...$params
	 * @throws \ReflectionException
	 */
	public function foreach($callback, ...$params) {
		foreach ($this->array as $key => $value) {
			$params_array = [
				'key' => $key,
				'value' => $value
			];
			if(is_string($callback)) {
				$is_function = false;
				$is_method = false;
				if(strstr($callback, '::')) $is_method = true;
				else $is_function = true;

				if($is_function)
					$this->execute_callback(
						count((new ReflectionFunction($callback))->getParameters()),
						$callback,
						$params_array,
						$params
					);
				elseif ($is_method)
					$this->execute_callback(
						count((new \ReflectionMethod(
							explode('::', $callback)[0],
							explode('::', $callback)[1]
						))->getParameters()),
						$callback,
						$params_array,
						$params
					);
			}
			else
				$this->execute_callback(
					count((new ReflectionFunction($callback))->getParameters()),
					$callback,
					$params_array,
					$params
				);
		}
	}

	public function is_empty() {
		return empty($this->array);
	}

	/**
	 * @param callable|string $callback
	 */
	public function filter($callback) {
		$this->create_rollback();
		$this->array = array_filter($this->array, $callback);
	}

	/**
	 * @param callable|string $callback
	 */
	public function map(callable $callback) {
		$this->create_rollback();
		$this->array = array_map($callback, $this->array);
	}

	public function rollback() {
		$this->array = $this->array_rollback;
	}

	public function init(...$array) {
		if(count($array) === 1 && gettype($array[0]) === 'array')
			foreach ($array[0] as $key => $value)
				$this->push($this->clean_value($value), $key);
		else {
			$last_key = null;
			foreach ($array as $i => $item) {
				if ($i % 2 === 0)
					$last_key = $item;
				else
					$this->push($this->clean_value($item), $last_key);
			}
		}
		return $this;
	}

	private function create_rollback() {
		$this->array_rollback = $this->array;
	}

	private function execute_callback($nb_params, $callback, $params, $other_params) {
		if($nb_params === 1) $callback($params['value']);
		elseif ($nb_params === 2) $callback($params['key'], $params['value']);
		elseif ($nb_params === 3) $callback($params['key'], $params['value'], $other_params);
	}

	public function clean_value($value) {
		if(($value_cast = intval($value)) !== 0) $value = $value_cast;
		return $value;
	}
}