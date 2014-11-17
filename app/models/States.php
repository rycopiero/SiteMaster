<?php

	namespace SiteMaster\Models;

use Phalcon\Mvc\Model\Validator\StringLength,
	Phalcon\Mvc\Model\Message;

	class States extends ModelBase
	{
		public $CountryId;
		
		public $Code;
		public $Name;
		
		public function initialize()
	    {
	    	parent::initialize();
	    	
	        $this->hasMany("Id", "SiteMaster\Models\Cities", "StateId");
	        $this->belongsTo("CountryId", "SiteMaster\Models\Countries", "Id");
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
		}
		
		public function afterValidationOnCreate()
		{
			$checkStateCode = States::find("Code = '".$this->Code."' AND DeletedDate IS NULL");
			if ( count($checkStateCode) ){
				$this->appendMessage(new Message(
		    			"",
		    			"Code",
		    			"UniqueConstraint")
		    		);
			}
			
			return $this->validationHasFailed() != true;
		}
		
		public function validation()
		{
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
	