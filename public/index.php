<?php
	
	error_reporting(E_ALL);
	
	define('APP_PATH', realpath('..'));
	define('APP_NAMESPACE', 'SiteMaster');
	
	try 
	{
		/**
		 *	Read Configuration File
		 */
		 
		$config =  include APP_PATH . '/app/config/config.php';
		
		/**
		 * Include the loader
		 */
		require APP_PATH . "/app/config/loader.php";
		
		/**
	 	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 	 */
		$di = new \Phalcon\DI\FactoryDefault();
		
		/**
		 * Include the application services
		 */
		require APP_PATH . "/app/config/services.php";
		
		$application = new \Phalcon\Mvc\Application();
		$application->setDI($di);
		echo $application->handle()->getContent();
	} 
	catch (Phalcon\Exception $e) 
	{
		echo $e->getMessage();
	}
	catch (PDOException $e) 
	{
		echo $e->getMessage();
	}
