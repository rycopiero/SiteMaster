<?php
	namespace SiteMaster\Library;

	class TagHelpers extends \Phalcon\Tag
	{
	    //Override an existing method
	    static public function textFieldFor($parameters)
	    {
	        // Converting parameters to array if it is not
	        if (!is_array($parameters)) {
	            $parameters = array($parameters);
	        }
	        
	        // Give default class
	        if ( !(isset($parameters["class"])) ) {
	            $parameters["class"] = "form-control";
	        } else {
	        	if ( !(strpos($parameters["class"], "form-control")) ){
	            	$parameters["class"] = "form-control ".$parameters["class"];
	            }
	        }
	        
	        return \Phalcon\Tag::textField($parameters);
	    }
	    
	    static public function selectFor($parameters)
	    {
	        // Converting parameters to array if it is not
	        if (!is_array($parameters)) {
	            $parameters = array($parameters);
	        }
	        
	        // Give default class
	        if ( !(isset($parameters["class"])) ) {
	            $parameters["class"] = "form-control";
	        } else {
	        	if ( !(strpos($parameters["class"], "form-control")) ){
	            	$parameters["class"] = "form-control ".$parameters["class"];
	            }
	        }
	        
	        return \Phalcon\Tag::select($parameters);
	    }
	    
	    static public function formFor($parameters)
	    {
	        // Converting parameters to array if it is not
	        if (!is_array($parameters)) {
	            $parameters = array($parameters);
	        }
	        
	        // Give default class
	        if ( !(isset($parameters["class"])) ) {
	            $parameters["class"] = "form-horizontal";
	        } else {
	        	if ( !(strpos($parameters["class"], "form-horizontal")) ){
	            	$parameters["class"] = "form-horizontal ".$parameters["class"];
	            }
	        }
	        
	        return \Phalcon\Tag::form($parameters);
	    }
	}