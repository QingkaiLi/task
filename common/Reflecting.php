<?php

abstract class Reflecting {
	protected $reflection = null;
	
	protected function reflection() {
		if ($this->reflection == null) {
			$this->reflection = new ReflectionClass($this);
		}

		return $this->reflection;
	}
	
	public function hasConstant($name) {
		$r = $this->reflection(); 
		return $r->hasConstant($name);
	} 
	
	public function hasProperty($name) {
		$r = $this->reflection(); 
		return $r->hasProperty($name);
	}
	
	public function getConstant($name) {
		$r = $this->reflection();
		return $r->getConstant($name); 
	}
	
	public function getStaticPropertyValue($name, $default) {
		$r = $this->reflection();
		return $r->getStaticPropertyValue($name, $default);
	}
	
	public function isSubclassOf($class) {
		$r = $this->reflection();
		return $r->isSubclassOf($class);
	}

	function getMethod($name) {
		$r = $this->reflection(); 
		return $r->getMethod($name);
	}
	
	public function implementsInterface($int) {
		$r = $this->reflection();
		return $r->implementsInterface($int);
	}
	
//	public function __call($func_name, $argv) {
//		$r = $this->reflectioin();
//		return call_user_func_array(array($r, $func_name), $argv);
//	}
	
}
?>
