<?php
	namespace SiteMaster\Controllers;

use SiteMaster\Models\Countries,
	SiteMaster\Models\States,
	SiteMaster\Models\Cities,
	\Phalcon\Mvc\View,
	\Phalcon\Mvc\Model\Resultset\Simple as Resultset,
	\Phalcon\Text;

	class AdminController extends ControllerBase
	{
		public function initialize()
	    {
            $this->pageTitleExtensions = "Administrator";
            
	        parent::initialize();
	    }
	    
	    public function indexAction()
	    {

	    }
	    
	    public function countryAction()
	    {
			$this->view->pick("admin/reference/country");
			
			if ( $this->dispatcher->getParam('Id') )
	    	{
	    		$singleCountry = Countries::findFirst(
		    		array (
	                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL AND Id = '.$this->dispatcher->getParam('Id'), 
	                    'order' => ' Code ASC, Name ASC'
	            	)
		    	);
	    		$this->view->country = $singleCountry;
	    	}
	    	else
	    	{
	    		$this->view->country = new Countries();
	    	}
			
	    	if ($this->request->isPost()) 
	    	{	
	    		$country = new Countries();
	    		
	    		$country->Code = Text::upper($this->request->getPost('Code', 'trim'));
				$country->Name = $this->request->getPost('Name');
				$country->RecordStatus = "AC";
				$country->CreatedBy = "0_Admin_BROWSER";

		        if ( !($country->save()) ) 
		        {	
		        	if ( $country->getMessages() )
		        	{
		        		$this->processMessages($country->getMessages());
					}
					
					$this->view->country = $country;
				} 
				else 
				{
					$this->view->country = new Countries();
				}
			}
			
			$countries = Countries::find(
	    		array (
                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL', 
                    'order' => ' Code ASC, Name ASC'
            	)
	    	);
	    	$this->view->countries = $countries;
            
            $this->pageTitleExtensions .= $this->view->country->Id ? " - Country" : " - ".$this->view->country->Name;
            $this->prepareTitle();
			
			$this->createJsExtBuilder();
	    }
	    
	    public function stateAction()
	    {
	    	$countries = Countries::find(
	    			array (
	                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL', 
	                    'columns' => 'Id, CONCAT (Code, " - ", Name) AS CodeName',
	                    'order' => ' Code ASC, Name ASC'
                	)
                );
	    	
	    	$this->view->countries = $countries;
	    	
	    	if ( $this->dispatcher->getParam('Id') )
	    	{
	    		$singleState = States::findFirst(
		    		array (
	                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL AND Id = '.$this->dispatcher->getParam('Id'), 
	                    'order' => ' Code ASC, Name ASC'
	            	)
		    	);
	    		$this->view->state = $singleState;
	    		$this->tag->setDefault("Country", $singleState->CountryId);
	    	}
	    	else
	    	{
	    		$this->view->state = new States();
	    	}
	    	
			$this->view->pick("admin/reference/state");
			
	    	if ($this->request->isPost()) 
	    	{	
	    		$state = new States();
	    		
	    		$state->Code = Text::upper($this->request->getPost('Code', 'trim'));
				$state->Name = $this->request->getPost('Name');
				$state->CountryId = $this->request->getPost('Country');
				$state->RecordStatus = "AC";
				$state->CreatedBy = "0_Admin_BROWSER";

		        if ( !($state->save()) ) 
		        {	
		        	if ( $state->getMessages() )
		        	{
		        		$this->processMessages($state->getMessages());
					}
					
					$this->view->state = $state;
				} 
				else 
				{
					$this->view->state = new States();
					
					$constraintMessage = $this->constraintMessage;
					$language = $this->session->get("language");
					
	    			$messageContainer = sprintf($constraintMessage['ObjectSavedSuccess'][$language], 'State', $state->Name);
	    			
	    			$this->flash->success($messageContainer);
				}
			}
			
			$states = States::find(
	    		array (
                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL', 
                    'order' => ' Code ASC, Name ASC'
            	)
	    	);
	    	$this->view->states = $states;
			
			$this->createJsExtBuilder();
	    }
	    
	    public function cityAction()
	    {
	    	$countries = Countries::find(
	    			array (
	                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL', 
	                    'columns' => 'Id, CONCAT (Code, " - ", Name) AS CodeName',
	                    'order' => ' Code ASC, Name ASC'
                	)
                );
	    	
	    	$this->view->countries = $countries;
	    	
	    	if ( $this->dispatcher->getParam('Id') )
	    	{
	    		$singleCity = Cities::findFirst(
		    		array (
	                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL AND Id = '.$this->dispatcher->getParam('Id'), 
	                    'order' => ' Code ASC, Name ASC'
	            	)
		    	);
		    	
		    	$this->view->states = $this->getStates($singleCity->State->CountryId);
		    	
	    		$this->view->city = $singleCity;
	    		$this->tag->setDefault("Country", $singleCity->State->CountryId);
	    		$this->tag->setDefault("State", $singleCity->StateId);
	    	}
	    	else
	    	{
	    		$this->view->city = new Cities();
	    	}
	    	
	    	$this->view->pick("admin/reference/city");
	    	
	    	$saved = false;
	    	if ($this->request->isPost()) 
	    	{
	    		$city = new Cities();
	    		
	    		$city->Code = Text::upper($this->request->getPost('Code', 'trim'));
	    		$city->Name = $this->request->getPost('Name');
	    		$city->CountryId = $this->request->getPost('Country');
	    		$city->StateId = $this->request->getPost('State');
	    		$city->RecordStatus = "AC";
				$city->CreatedBy = "0_Admin_BROWSER";
	    		
	    		if ( !($city->save()) )
	    		{
	    			if ( $city->getMessages() )
		        	{
		        		$this->processMessages($city->getMessages());
					}
					
					$this->view->city = $city;
	    		}
	    		else
	    		{
	    			$this->view->city = new Cities();
	    			$this->tag->setDefault("Country", 0);
	    			
	    			$saved = true;
	    		}
	    	}
	    	
	    	if ( !($saved) ){
		    	if ( $this->request->getPost('Country') ) {
		    		$this->tag->setDefault("Country", $this->request->getPost('Country'));
		    		$this->view->states = $this->getStates($this->request->getPost('Country'));
		    	}
		    }
	    	
	    	$cities = Cities::find(
	    		array (
                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL', 
                    'order' => ' Code ASC, Name ASC'
            	)
	    	);
	    	$this->view->cities = $cities;
	    	
	    	$this->createJsExtBuilder();
	    }
	    
	    public function emailtypeAction()
	    {
	    	if ($this->request->isPost()) 
	    	{
	    		
	    	}
	    	
	    	$this->view->pick("admin/reference/emailtype");	
	    }
	    
	    public function phonetypeAction()
	    {
	    	$this->view->pick("admin/reference/phonetype");
	    }
	    
	    public function addresstypeAction()
	    {
	    	
	    	$this->view->pick("admin/reference/addresstype");
	    }
	    
	    public function socialtypeAction()
	    {
	    	
	    	$this->view->pick("admin/reference/socialtype");
	    }
	    
	    /**
	     * Function getStatesAction 
	     * to get states based on CountryId (post parameter)
	     * return states in json form
		 * @parameter NO
		 */
	    public function getStatesJsonAction()
	    {
	    	$this->view->setRenderLevel(View::LEVEL_NO_RENDER);
	    	
	    	$CountryId = $this->request->getPost("CountryId");
	    	
	    	$states = States::find(
	    			array (
	                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL AND CountryId = '.$CountryId, 
	                    'columns' => 'Id, CONCAT (Code, " - ", Name) AS CodeName',
	                    'order' => ' Code ASC, Name ASC'
	            	)
	    	);
	
			echo json_encode($states->toArray());
	    }
	    
	    /**
	     * Function getStates
	     * to get states based on CountryId (need parameter)
	     * return states in object array form
		 * @parameter $CountryId
		 */
	    private function getStates($CountryId)
	    {
	    	$states = States::find(
	    			array (
	                    'conditions' => 'RecordStatus = \'AC\' AND DeletedDate IS NULL AND CountryId = '.$CountryId, 
	                    'columns' => 'Id, CONCAT (Code, " - ", Name) AS CodeName',
	                    'order' => ' Code ASC, Name ASC'
	            	)
	    	);
	    	
	    	return $states;
	    }
	}
	