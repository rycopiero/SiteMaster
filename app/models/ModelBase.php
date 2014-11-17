<?php

	namespace SiteMaster\Models;

	class ModelBase extends \Phalcon\Mvc\Model
	{
		public $Id;
		
		public $VirtualDelete;
		
		public $RecordStatus;
		public $RecordVersion;
		
		public $CreatedDate;
		public $CreatedBy;
		public $ModifiedDate;
		public $ModifiedBy;
		public $DeletedDate;
		public $DeletedBy;
		
		public function initialize()
	    {
	        $this->useDynamicUpdate(true);
	        
	        //Skips only when inserting
	        //$this->skipAttributesOnCreate(array('CreatedDate', 'RecordVersion'));
	
	        //Skips only when updating
	        //$this->skipAttributesOnUpdate(array('ModifiedDate'));
	    }
	    
		public function beforeValidationOnCreate()
	    {
	        //Set the creation date
	        $this->CreatedDate = date('Y-m-d H:i:s');
	        
	        $this->RecordVersion = 1;
	    }
	    
	    public function beforeUpdate()
	    {
	    	if ( !(isset($this->VirtualDelete)) || !($this->VirtualDelete) )
	    	{
	    		//Set the modification date
	        	$this->ModifiedDate = date('Y-m-d H:i:s');
	        	
	    		$this->RecordVersion += 1;
	    	}
	    	else 
	    	{
	    		//Set the deletion date
	    		$this->DeletedDate = date('Y-m-d H:i:s');
	    	}
	    }
	}
