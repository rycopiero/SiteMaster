<?php

	namespace SiteMaster\Models;

use Phalcon\Mvc\Model\Validator\StringLength,
	Phalcon\Mvc\Model\Message;

	class Cities extends ModelBase
	{
		public $StateId;
	
		public $Code;
		public $Name;
		
		public function initialize()
	    {
	    	parent::initialize();
	    	
	        $this->belongsTo("StateId", "SiteMaster\Models\States", "Id", array(
            	'alias' => 'State'
       	 	));
	    }
		
		public function beforeValidation()
		{
			if ( $this->CountryId == 0 ){
		    	$this->appendMessage(new Message(
		    			"",
		    			"Country",
		    			"ForeignKeyConstraint")
		    		);
		    } 
		    
			if ( $this->StateId == 0 ){
		    	$this->appendMessage(new Message(
		    			"",
		    			"State",
		    			"ForeignKeyConstraint")
		    		);
		    } 
		}
		
		public function validation()
		{
	        $checkCityCode = Cities::find("Code = '".$this->Code."' AND DeletedDate IS NULL");
			if ( count($checkCityCode) ){
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
		            'max' => 5,
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
	