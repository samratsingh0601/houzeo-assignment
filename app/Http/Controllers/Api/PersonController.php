<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Config;

use App\Api\Models\CommonApi;
use App\Api\Models\GetFilms;
use App\Person;
use App\Film;
class PersonController extends Controller
{
    /**
     * Add Person
     *
     * @return [json] object
     */
    public function savePerson(Request $request)
    {
        $commonApi = new CommonApi();
        $validator = Validator::make($request->all(),[
			'person_url' => 'required|string',
        ]);
		if ($validator->fails()) {
			$errorMessage = "";
			$errorArray = json_decode($validator->messages());
			foreach($errorArray as $key => $value) {
				$errorMessage = $errorMessage.$value[0].", ";
			}
			$errorMessage = substr($errorMessage,0,strlen($errorMessage)-2);
			$commonApi->setMessage($errorMessage);
			$commonApi->setResponseCode(Config::get('constants.response_code_server_error'));
			$commonApi->setStatus(Config::get('constants.status_false'));
		}else{
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $request->person_url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_TIMEOUT => 30000,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					// Set Here Your Requesred Headers
					'Content-Type: application/json',
				),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) {
				$commonApi->setMessage(Config::get('constants.something_went_wrong'));
				$commonApi->setResponseCode(Config::get('constants.response_code_server_error'));
				$commonApi->setStatus(Config::get('constants.status_false'));
			} else {
				$personResult = json_decode($response);
				$person = Person::where('api_url', $personResult->url)->first();
				if(empty($person)){
					$person = new Person();
				}
				$person->name = $personResult->name;
				$person->height = $personResult->height;
				$person->mass = $personResult->mass;
				$person->hair_color = $personResult->hair_color;
				$person->skin_color = $personResult->skin_color;
				$person->eye_color = $personResult->eye_color;
				$person->birth_year = $personResult->birth_year;
				$person->gender = $personResult->gender;
				$person->homeworld = $personResult->homeworld;
				$person->api_url = $personResult->url;
				$person->save();
				
				$filmsIds = Film::whereIn('api_url', $personResult->films)->pluck('id')->toArray();
				$person->films()->sync($filmsIds);
				
				$commonApi->setMessage(Config::get('constants.successfully_saved'));
				$commonApi->setResponseCode(Config::get('constants.response_code_success'));
				$commonApi->setStatus(Config::get('constants.status_true'));
			}
		}
        return $commonApi->getJson();
    }
	
	/**
     * get person films from database
     *
     * @return [json] object
     */
    public function getFilms(Request $request)
    {
        $getFilms = new GetFilms();
        $validator = Validator::make($request->all(),[
			'person_url' => 'required|string',
        ]);
		if ($validator->fails()) {
			$errorMessage = "";
			$errorArray = json_decode($validator->messages());
			foreach($errorArray as $key => $value) {
				$errorMessage = $errorMessage.$value[0].", ";
			}
			$errorMessage = substr($errorMessage,0,strlen($errorMessage)-2);
			$getFilms->setMessage($errorMessage);
			$getFilms->setResponseCode(Config::get('constants.response_code_server_error'));
			$getFilms->setStatus(Config::get('constants.status_false'));
		}else{
			$person = Person::where('api_url', $request->person_url)->first();
			if(empty($person)){
				$getFilms->setMessage(Config::get('constants.something_went_wrong'));
				$getFilms->setResponseCode(Config::get('constants.response_code_server_error'));
				$getFilms->setStatus(Config::get('constants.status_false'));
			} else {
				foreach($person->films as $film){
					$getFilms->addFilmToList($film->id, $film->title, $film->episode_id, $film->opening_crawl, $film->director, $film->producer, $film->release_date);
				}
				
				$getFilms->setMessage(Config::get('constants.list_fetch_success'));
				$getFilms->setResponseCode(Config::get('constants.response_code_success'));
				$getFilms->setStatus(Config::get('constants.status_true'));
			}
		}
        return $getFilms->getJson();
    }
}
