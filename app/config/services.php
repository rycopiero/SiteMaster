<?php

use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Cache\Backend\File as FileCache;
use Phalcon\Db\Adapter\Pdo\Mysql as DatabaseConnection;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use SiteMaster\Library\TagHelpers as TagHelpers;


	$di->set('url', function() use ($config) {
		$url = new UrlResolver();
		if (!$config->application->appState->inDevelopment) {
			$url->setBaseUri($config->application->production->baseUri);
			$url->setStaticBaseUri($config->application->production->staticBaseUri);
		} else {
			$url->setBaseUri($config->application->development->baseUri);
			$url->setStaticBaseUri($config->application->development->staticBaseUri);
		}
		return $url;
	}, true);
	
	/**
	 * Setup the view service
	 */
	$di->set('view', function() use ($config) {
		$view = new View();
		$view->setViewsDir($config->application->viewsDir);
		return $view;
	}, true);
	
		/**
		 * View cache
		 */
		$di->set('viewCache', function() use ($config) {
		
			if ($config->application->appState->inDevelopment) {
				$frontCache = new \Phalcon\Cache\Frontend\None();
			} else {
				//Cache data for one day by default
				$frontCache = new \Phalcon\Cache\Frontend\Output(array(
					"lifetime" => 86400 * 30
				));
			}
		
			return new FileCache($frontCache, array(
				"cacheDir" => APP_PATH . "/app/cache/views/",
				"prefix"   => "php-cache-"
			));
		});

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	$di->set('db', function() use ($config) {
        $connection = new DatabaseConnection($config->database->toArray());
        
		if ( $config->application->appState->inDevelopment ) {
			$eventsManager = new \Phalcon\Events\Manager();
			
			$debugDb = $config->application->appState->debugDb;
			if ( $debugDb ) {
				$dbLog = new FileLogger(APP_PATH . "/app/logs/db.log");
				$eventsManager->attach('db', function($event, $connection) use ($dbLog) {
				    if ($event->getType() == 'beforeQuery') {
				    	$dbLog->log($connection->getSQLStatement(), \Phalcon\Logger::INFO);
				    }
				});
			}
            
            //Assign the eventsManager to the db adapter instance
			$connection->setEventsManager($eventsManager);
		}
		
		return $connection;
	});
	
	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function() {
		$session = new SessionAdapter();
		$session->start();
		return $session;
	}, true);
	
	/**
	 * Load router from external file
	 */
	$di->set('router', function(){
		require APP_PATH . '/app/config/routes.php';
		return $router;
	});
	
	/**
	 * Register the flash service with the Twitter Bootstrap classes
	 */
	$di->set('flash', function() {
		return new Phalcon\Flash\Direct(array(
			'error' => 'alert alert-danger',
			'success' => 'alert alert-success',
			'notice' => 'alert alert-info',
		));
	});
	
	/**
	 * Register the session flash service with the Twitter Bootstrap classes
	 */
	$di->set('flashSession', function() {
		return new Phalcon\Flash\Session(array(
			'error' => 'alert alert-danger',
			'success' => 'alert alert-success',
			'notice' => 'alert alert-info',
		));
	});
	
	$di->set('dispatcher', function() {
		$dispatcher = new MvcDispatcher();
		$dispatcher->setDefaultNamespace(APP_NAMESPACE.'\Controllers');
		return $dispatcher;
	});
	
	$di->set('tag', function() {
	    return new TagHelpers();
	});
	
	$di->set('config', $config);
	
	$di->set('constraintMessage', function() {
		$translationPath = APP_PATH . '/app/messages/';
		require $translationPath . 'constraint.php';
		return $constraintMessage;
	});
	
	$di->setShared('session', function() {
	    $session = new Phalcon\Session\Adapter\Files();
	    $session->start();
	    return $session;
	});