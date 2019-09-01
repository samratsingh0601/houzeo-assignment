<?php

namespace App\Api\Models;
use Config;

class GetFilms
{
	private $_responseCode;
	private $_status;
	private $_message;
	private $_films;
	
	public function __construct(){
		$this->_responseCode = Config::get('constants.response_code_not_found');
		$this->_status = Config::get('constants.status_false');
		$this->_message = Config::get('constants.something_went_wrong');
		$this->_films = array();
	}
	public function getJson(){
		return json_encode(array(
			'response_code'	=> $this->_responseCode,
			'status'		=> $this->_status,
			'message'		=> $this->_message,
			'films'			=> $this->_films,
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
	
	public function addFilmToList(int $id, string $title, int $episodeId, string $openingCrawl, string $director, string $producer, string $releaseDate){
		array_push($this->_films, array(
						'id'			=> $id,
						'title'			=> $title,
						'episode_id'	=> $episodeId,
						'opening_crawl'	=> $openingCrawl,
						'director'		=> $director,
						'producer'		=> $producer,
						'release_date'	=> $releaseDate,
					));
	}
}
