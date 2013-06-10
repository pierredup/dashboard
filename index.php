<?php

	const ROOT = __DIR__;

	require_once ROOT . '/vendor/autoload.php'; 

	use Symfony\Component\HttpFoundation\Request;

	$app = new Silex\Application();
	$app['debug'] = false;
	
	if($app['debug']) {
		$app->register(new Whoops\Provider\Silex\WhoopsServiceProvider);
	}
	
	$app['utilities'] = $app->share(function(Silex\Application $app) {
			return new Local\Utilities($app);
		});
	
	$app['utilities']->register(new Local\Utilities\Base64);
	$app['utilities']->register(new Local\Utilities\Timestamp);
	$app['utilities']->register(new Local\Utilities\Phpinfo);

	$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => ROOT . '/views',
		'twig.options' => array(
								'cache' => ROOT . '/cache/twig'
							)
	));
	$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

	$app->get('/', function() use($app) { 
		 return $app['twig']->render('home.twig', array(
			"projects" => json_decode(file_get_contents(ROOT . "/config/projects.json")),
			"sites" => json_decode(file_get_contents(ROOT . "/config/sites.json"))
		 ));
	})->bind('home');
	
	$app->post('/projects/add', function(Request $request) use($app) { 
		$project = $request->request->get('project');
		
		$project['url'] = 'http://' . $project['url'];
		
		$values = (array) json_decode(file_get_contents(ROOT . "/config/projects.json"));
		
		$values[] = (object) $project;
		
		file_put_contents(ROOT . "/config/projects.json", json_encode($values));
		
		return $app->json($project, 201);
		
	})->bind('add_project');
	
	$app->post('/sites/add', function(Request $request) use($app) { 
		$site = $request->request->get('site');
		
		$site['url'] = 'http://' . $site['url'];
		
		$values = (array) json_decode(file_get_contents(ROOT . "/config/sites.json"));
		
		$values[] = (object) $site;
		
		file_put_contents(ROOT . "/config/sites.json", json_encode($values));
		
		return $app->json($site, 201);
		
	})->bind('add_site');
	
	$projects = json_decode(file_get_contents(ROOT . "/config/projects.json"));
	
	$app->post("/projects/delete", function(Request $request) use($app) {
		$title = $request->request->get('title');
		$url = $request->request->get('url');
		
		$projectsList = json_decode(file_get_contents(ROOT . "/config/projects.json"));
				
		$i = 0;
		$projects = array();
		foreach($projectsList as $key => $project) {
			if($project->title === $title && $project->url === $url) {
				unset($projectsList[$key]);
				continue;
			}
			$projects[$i++] = $project;
		}
		
		file_put_contents(ROOT . "/config/projects.json", json_encode($projects));
		
		return $app->json(array('status' => 'success'));
		
	})->bind("delete_project");
	
	$app->post("/sites/delete", function(Request $request) use($app) {
		$title = $request->request->get('title');
		$url = $request->request->get('url');
		
		$sitesList = json_decode(file_get_contents(ROOT . "/config/sites.json"));
				
		$i = 0;
		$sites = array();
		foreach($sitesList as $key => $site) {
			if($site->title === $title && $site->url === $url) {
				unset($sitesList[$key]);
				continue;
			}
			$sites[$i++] = $site;
		}
		
		file_put_contents(ROOT . "/config/sites.json", json_encode($sites));

		return $app->json(array('status' => 'success'));
		
	})->bind("delete_site");

	$app->run();
