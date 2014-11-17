<?php
/**
 *  Configuration Master
 *	@author	: Eryco Putra
 *	@date	: 20140307
 */

	return new \Phalcon\Config( 
		array(
			'database' => 
				array(
					'adapter'	=> 'mysql',
					'host'		=> 'localhost',
					'username'	=> 'root',
					'password'	=> '',
					'dbname'	=> 'artroop',
				),
				
			'application' => 
				array(
					'controllersDir'	=> APP_PATH . '/app/controllers/',
					'modelsDir'			=> APP_PATH . '/app/models/',
					'viewsDir'			=> APP_PATH . '/app/views/',
					'libraryDir'		=> APP_PATH . '/app/library/',
					'pluginsDir'		=> APP_PATH . '/app/plugins/',
					'development'       => 
						array(
							'staticBaseUri' => '/SiteMaster/',
							'baseUri'       => '/SiteMaster/'
						),
					'production'        => 
						array(
							'staticBaseUri' => '',
							'baseUri'       => '/SiteMaster/'
						),
					'appState'			=>
						array(
							'inDevelopment'	=> true,
							'debugLoader'	=> false,
							'debugDb'		=> true,
						),
					'availableLang'		=>
						array(
							'en', 'id'
						),
				),
				
			'models' => 
				array(
			        'metadata' => 
			        	array(
				            'adapter' => 'Apc',
				    		'lifetime' => 86400
				        )
		    	)
		)
	);
