<?php
	$loader = new \Phalcon\Loader();
		
	/**
	 * Registering Directories. 
	 * Config get from configuration file.
	 */
	
	$loader->registerNamespaces(
		array(
			APP_NAMESPACE.'\Controllers'	=> $config->application->controllersDir,
			APP_NAMESPACE.'\Models' 		=> $config->application->modelsDir,
			APP_NAMESPACE.'\Library' 		=> $config->application->libraryDir,
		)
	);
	
	/*
	$loader->registerDirs(
	    array(
	        $config->application->controllersDir,
	        $config->application->modelsDir,
	        $config->application->libraryDir
	    )
	);
	*/
	
	/**
	 * Check application state, get debug status.
	 * If true, listen all the loader events.
	 */
	
	if ( $config->application->appState->inDevelopment ) {
		$eventsManager = new \Phalcon\Events\Manager();
		
		$debugLoader = $config->application->appState->debugLoader;
		if ( $debugLoader ) {
			
			$loaderLog = new Phalcon\Logger\Adapter\File(APP_PATH . "/app/logs/loader.log");
			
			$eventsManager->attach('loader', function($event, $loader) use ($loaderLog) {
			    if ($event->getType() == 'beforeCheckPath') {
			    	$loaderLog->log($loader->getCheckedPath(), \Phalcon\Logger::INFO);
			    }
			});
			
			$loader->setEventsManager($eventsManager);
		}
	}
	
	$loader->register();