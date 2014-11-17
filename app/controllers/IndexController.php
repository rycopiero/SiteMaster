<?php
	namespace SiteMaster\Controllers;

	class IndexController extends ControllerBase
	{
		public function initialize()
	    {
	        $this->tag->setTitle('Welcome');
	        //$this->view->setTemplateAfter('main');
	        
	        parent::initialize();
	    }
	    
	    public function indexAction()
	    {
	        $language = $this->session->get('language');
	        $this->flash->notice('Index');
	    }
	}