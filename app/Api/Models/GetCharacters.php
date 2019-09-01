<?php

namespace App\Api\Models;
use Config;

class GetCharacters
{
	private $_responseCode;
	private $_status;
	private $_message;
	private $_characters;
	
	public function __construct(){
		$this->_responseCode = Config::get('constants.response_code_not_found');
		$this->_status = Config::get('constants.status_false');
		$this->_message = Config::get('constants.something_went_wrong');
		$this->_characters = array();
	}
	public function getJson(){
		return json_encode(array(
			'response_code'	=> $this->_responseCode,
			'status'		=> $this->_status,
			'message'		=> $this->_message,
			'characters'	=> $this->_characters,
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
	
	public function addCharacterToList(int $id, string $name, int $height, int $mass, string $hairColor, string $skinColor, string $eyeColor, string $birthYear, string $gender, string $homeworld){
		array_push($this->_characters, array(
						'id'			=> $id,
						'name'			=> $name,
						'mass'			=> $mass,
						'hair_color'	=> $hairColor,
						'skin_color'	=> $skinColor,
						'eye_color'		=> $eyeColor,
						'birth_year'	=> $birthYear,
						'gender'		=> $gender,
						'homeworld'		=> $homeworld,
					));
	}
}
