<?php

	namespace SiteMaster\Controllers;

use Phalcon\Translate\Adapter\NativeArray;

	class ControllerBase extends \Phalcon\Mvc\Controller
	{
		protected $jsExtGlobal;
		protected $jsExtForm;
        
        protected $pageTitle;
		protected $pageTitleExtensions;
        
		protected function initialize() 
	    {
            $this->prepareTitle();
	        
	        $this->loadAssets();
	        
			$this->loadMainTrans();
	    }
        
        private function prepareTitle()
        {
            $this->pageTitle = APP_NAMESPACE." | ".$this->pageTitleExtensions;
            $this->tag->setTitle($this->pageTitle);
        }
	    
	    public function loadAssets()
	    {
	    	/**
			 * Css Assets
			 */
			$this->assets
			    ->collection('cssMain')
			    ->addCss('css/bootstrap.css')
			    ->addCss('css/Style.css');
			
			/**
			 * Javascript Assets
			 */
			$this->assets
			    ->collection('jsMain')
			    ->addJs('js/jquery.min.js')
			    ->addJs('js/bootstrap.js')
			    ->addJs('js/Site.js');
	    }
	    
	    protected function _getTransPath()
	    {
	        $translationPath = APP_PATH . '/app/messages/';
	        $language = $this->session->get("language");
	        if ( !$language ) {
	            $this->session->set("language", "en");
	            $language = $this->session->get("language");
	        }

	        if ( in_array($language, $this->config->application->availableLang->toArray()) ){
	        	return $translationPath.$language.'/';
	        } else {
	            return $translationPath.'en/';
	        }
	    }
	    
	    /**
	     * Loads a translation for the whole site
	     */
	    public function loadMainTrans()
	    {
	        $translationPath = $this->_getTransPath();
	        require $translationPath."/main.php";
	
	        //Return a translation object
	        $mainTranslate = new NativeArray(array(
	            "content" => $messages
	        ));
	
	        //Set $mt as main translation object
	        $this->view->setVar("mt", $mainTranslate);
		}
		
		/**
		 * Loads a translation for the active controller
       	 */
	    public function loadCustomTrans($transFile)
	    {
	        $translationPath = $this->_getTransPath();
	        require $translationPath.'/'.$transFile.'.php';
	
	        //Return a translation object
	        $controllerTranslate = new NativeArray(array(
	            "content" => $messages
	        ));
	
	        //Set $t as controller's translation object
	        $this->view->setVar("t", $controllerTranslate);
	    }
	    
	    public function processMessages($messages)
	    {
	    	//Load constraint messages
	    	$language = $this->session->get("language");
	    	$constraintMessage = $this->constraintMessage;
	    	
	    	//Build message container for flash error
	    	$messageContainer = '<ul class="alert-list">';
        	foreach ($messages as $message) {
        		$field = $message->getField();
        		
        		switch ( $message->getType() ){
        			case "UniqueConstraint" : 
        				$messageContainer .= '<li>' . sprintf($constraintMessage[$message->getType()][$language], $field, $field)  . '</li>';
        				break;
        			case "ForeignKeyConstraint" :
        				$messageContainer .= '<li>' . sprintf($constraintMessage[$message->getType()][$language], $field)  . '</li>';
        				break;
        			default : 
        				$messageContainer .= '<li>' . $message . '</li>';
        				break;
        		}
        		
        		$this->jsExtForm .=  "FormStyle.setElementError('form', '" . $message->getField() . "'); ";
			}
			$messageContainer .= '</ul>';
			
			return $this->flash->error($messageContainer);
	    }
	    
	    public function createJsExtBuilder()
	    {
	    	//Build extended javascript
			$jsExtBuilder = empty($this->jsExtGlobal) && empty($this->jsExtForm) ? 
				'' : '<script type="text/javascript">' . $this->jsExtGlobal . $this->jsExtForm . '</script>';
			
			$this->view->setVar("jsViewBuilder", $jsExtBuilder);
	    }
	}