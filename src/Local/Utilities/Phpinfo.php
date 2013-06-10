<?php

namespace Local\Utilities;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Phpinfo implements UtilityInterface
{
	public function hasModal()
	{
		return false;
	}

	public function getRoutes()
	{
		return array(
			$this->getLink() => array($this, 'render')
		);
	}
	
	public function render(Application $app, Request $request)
	{
		ob_start();
			phpinfo();
		return ob_get_clean();
	}
	
	public function getLink()
	{
		return '/phpinfo';
	}

	public function getTitle()
	{
		return 'phpinfo';
	}
}
