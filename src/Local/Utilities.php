<?php

namespace Local;

class Utilities implements \Iterator
{
	protected $utilities = array();
	
	protected $pointer;
	
	protected $app;
	
	public function __construct(\Silex\Application $app)
	{
		$this->app = $app;
	}
	
	public function register($utility)
	{
		$this->utilities[] = $utility;
		
		if(method_exists($utility, 'getRoutes')) {
			foreach($utility->getRoutes() as $route => $callback) {
				$this->app->match($route, $callback);
			}
		}
		
		return $this;
	}
	
	public function rewind()
	{
		$this->pointer = 0;
	}
	
	public function valid()
	{
		return isset($this->utilities[$this->pointer]);
	}
	
	public function next()
	{
		$this->pointer++;
	}
	
	public function current()
	{
		return $this->utilities[$this->pointer];
	}
	
	public function key()
	{
		return $this->pointer;
	}
}
