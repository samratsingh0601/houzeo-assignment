<?php

namespace App\Api\Models;
use Config;

class CommonApi
{
	private $_responseCode;
	private $_status;
	private $_message;
	
	public function __construct(){
		$this->_responseCode = Config::get('constants.response_code_not_found');
		$this->_status = Config::get('constants.status_false');
		$this->_message = Config::get('constants.something_went_wrong');
	}
	public function getJson(){
		return json_encode(array(
			'response_code'	=> $this->_responseCode,
			'status'		=> $this->_status,
			'message'		=> $this->_message,
		));
	}
	
	public function setResponseCode(int $responseCode){
		$this->_responseCode = $responseCode;
	}
	
	public function setStatus(bool $status){
		$this->_status = $status;
	}
	
	public function setMessage(string $message){
		$this->_message = $message;
	}
}
