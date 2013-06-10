<?php

namespace Local\Utilities;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Timestamp implements UtilityInterface
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
			
			$timestamp = $date = null;
			
			switch($type) {
				case "timestamp" : $date = date("Y-m-d H:i:s", $request->request->get('timestamp')); break;
				case "date" : $timestamp = strtotime($request->request->get('date')); break;
			}

			return $app['twig']->render('utilities\timestamp.twig', array('timestamp' => $timestamp, 'date' => $date));
		}
		
		return $app['twig']->render('utilities\timestamp.twig');
	}
	
	public function getLink()
	{
		return '/timestamp';
	}

	public function getTitle()
	{
		return 'Unix Timestamp Convert';
	}
}
