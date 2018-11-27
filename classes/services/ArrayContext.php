<?php

namespace mvc_framework\core\orm\services;


use ReflectionFunction;

class ArrayContext {
	private $array = [], $arrayOf = null, $classOfArray = null;
	private $array_rollback = [];

	private function __construct($arrayOf, $classOfArray = null) {
		$this->arrayOf = $arrayOf;
		$this->classOfArray = $classOfArray;
	}

	public static function create($arrayOf, $classOfArray = null) {
		return new ArrayContext($arrayOf, $classOfArray);
	}

	public function reset() {
		$this->array = [];
	}

	public function push($mixed, $key = null) {
		if(gettype($mixed) === $this->arrayOf) {
			if ($this->arrayOf === 'object') {
				if (get_class($mixed) === $this->classOfArray) {
					if (!is_null($key)) $this->array[$key] = $mixed;
					else array_push($this->array, $mixed);
				}
			}
			else {
				if(!is_null($key)) $this->array[$key] = $mixed;
				else array_push($this->array, $mixed);
			}
		}
	}

	public function get($key = null) {
		if(is_null($key)) return $this->array;
		return $this->array[$key];
	}

	/**
	 * @param callable|string $callback
	 * @throws \ReflectionException
	 */
	public function foreach($callback) {
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
						$params_array
					);
				elseif ($is_method)
					$this->execute_callback(
						count((new \ReflectionMethod(
							explode('::', $callback)[0],
							explode('::', $callback)[1]
						))->getParameters()),
						$callback,
						$params_array
					);
			}
			else
				$this->execute_callback(
					count((new ReflectionFunction($callback))->getParameters()),
					$callback,
					$params_array
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

	private function create_rollback() {
		$this->array_rollback = $this->array;
	}

	private function execute_callback($nb_params, $callback, $params) {
		if($nb_params === 1) $callback($params['value']);
		elseif ($nb_params === 2) $callback($params['key'], $params['value']);
	}
}