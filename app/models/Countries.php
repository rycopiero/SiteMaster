<?php

	namespace SiteMaster\Models;

use Phalcon\Mvc\Model\Validator\StringLength,
	Phalcon\Mvc\Model\Message;
	
	class Countries extends ModelBase
	{
		public $Code;
		public $Name;
		
		public function initialize()
	    {
	    	parent::initialize();
	    	
	        $this->hasMany("Id", "SiteMaster\Models\States", "CountryId");
	    }
		
		public function validation()
		{
			$checkCountryCode = Countries::find("Code = '".$this->Code."' AND DeletedDate IS NULL");
			if ( count($checkCountryCode) ){
				$this->appendMessage(new Message(
		    			"",
		    			"Code",
		    			"UniqueConstraint")
		    		);
			}
	        
	        $this->validate(new StringLength(
	        	array(
		            'field' => 'Code',
		            'min' => 3,
		            'max' => 3,
		    	)
		    ));
		    
		    $this->validate(new StringLength(
	        	array(
		            'field' => 'Name',
		            'min' => 3,
		            'max' => 100,
		    	)
		    ));
		    
		    return $this->validationHasFailed() != true;
		}
	}