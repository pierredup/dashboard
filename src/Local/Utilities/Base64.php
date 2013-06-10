<?php

namespace Local\Utilities;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Base64 implements UtilityInterface
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
		if($request->isMethod('POST')) {
			$type = $request->request->get('type');
			$data = $request->request->get('data');
			
			switch($type) {
				case "encode" : $content = base64_encode($data); break;
				case "decode" : $content = base64_decode($data); break;
			}

			return $app['twig']->render('utilities\base64.twig', array('content' => $content));
		}
		
		return $app['twig']->render('utilities\base64.twig');
	}
	
	public function getLink()
	{
		return '/base64';
	}

	public function getTitle()
	{
		return 'Base64 Encode/Decode';
	}
}
