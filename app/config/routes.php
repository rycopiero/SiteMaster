<?php
	
	$router = new Phalcon\Mvc\Router();
	
	$router->add("/set-language/{language:[a-z]+}", array(
	    'controller' => 'index',
	    'action' => 'setLanguage'
	));
	
	$router->add("/admin/reference/country/{Id:[0-9]+}", array( 'controller' => 'admin', 'action' => 'country' ));
	$router->add("/admin/reference/country", array( 'controller' => 'admin', 'action' => 'country' ));
	$router->add("/admin/reference/state/{Id:[0-9]+}", array( 'controller' => 'admin', 'action' => 'state' ));
	$router->add("/admin/reference/state", array( 'controller' => 'admin', 'action' => 'state' ));
	$router->add("/admin/reference/city/{Id:[0-9]+}", array( 'controller' => 'admin', 'action' => 'city' ));
	$router->add("/admin/reference/city", array( 'controller' => 'admin', 'action' => 'city' ));
	$router->add("/admin/reference/emailtype", array( 'controller' => 'admin', 'action' => 'emailtype' ));
	$router->add("/admin/reference/phonetype", array( 'controller' => 'admin', 'action' => 'phonetype' ));
	$router->add("/admin/reference/addresstype", array( 'controller' => 'admin', 'action' => 'addresstype' ));
	$router->add("/admin/reference/socialtype", array( 'controller' => 'admin', 'action' => 'socialtype' ));
	