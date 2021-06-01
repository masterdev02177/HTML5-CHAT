<?php

Class Language {
	private $file;
	private $prefix;

	function __construct($Current = 'eng_ENG') {
		if (!$this->checkJSON($Current)) {
			$Current = 'eng_ENG';
		}
		
		$this->file = dirname(__FILE__)."/languages/".$Current.".json";
		$this->prefix = $Current;
	}

	function checkJSON($Current) {
		return !empty($Current) && file_exists(dirname(__FILE__)."/languages/".$Current.".json");
	}

	function errorJSON() {
		switch (json_last_error()) {
	        case JSON_ERROR_NONE:
	            echo ' - No errors :)';
	        break;
	        case JSON_ERROR_DEPTH:
	            echo ' - Maximum stack depth reached';
	        break;
	        case JSON_ERROR_STATE_MISMATCH:
	            echo ' - Incorrect digits or mismatch of modes';
	        break;
	        case JSON_ERROR_CTRL_CHAR:
	            echo ' - Incorrect control character';
	        break;
	        case JSON_ERROR_SYNTAX:
	            echo ' - Syntax error, not valid JSON';
	        break;
	        case JSON_ERROR_UTF8:
	            echo ' - Incorrect UTF-8 characters, possibly incorrect encoding';
	        break;
	        default:
	            echo ' - Unknown error';
	        break;
	    }
	}

	function getData() {
		$string = file_get_contents($this->file);
		$string = preg_replace( "/\r|\n/", "", $string);

		$decode = json_decode($string, true);
		//$this->errorJSON();
		return $decode;
	}

	function getImgPath() {
		$path = 'images/';
		if ($this->prefix === 'eng_ENG')
			return $path;

		return $path.$this->prefix.'/';
	}

	function getSliderPath() {
		$path = 'slider/';
		if ($this->prefix === 'eng_ENG')
			return $path;

		return $path.$this->prefix.'/';	
	}
}

