<?php

abstract class Runable {
	protected $loader;
	public function __construct() {
		$this->loader = new \mvc_framework\core\orm\ModuleLoader();
	}

	abstract protected function start();
	public function run() {
		$this->start();
	}
}