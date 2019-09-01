<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Config;

use App\Api\Models\CommonApi;
use App\Api\Models\GetCharacters;
use App\Person;
use App\Film;
class FilmController extends Controller
{
    /**
     * Add Person
     *
     * @return [json] object
     */
    public function saveFilm(Request $request)
    {
        $commonApi = new CommonApi();
        $validator = Validator::make($request->all(),[
			'film_url' => 'required|string',
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
				CURLOPT_URL => $request->film_url,
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
				$filmResult = json_decode($response);
				$film = Film::where('api_url', $filmResult->url)->first();
				if(empty($film)){
					$film = new Film();
				}
				$film->title = $filmResult->title;
				$film->episode_id = $filmResult->episode_id;
				$film->opening_crawl = $filmResult->opening_crawl;
				$film->director = $filmResult->director;
				$film->producer = $filmResult->producer;
				$film->release_date = $filmResult->release_date;
				$film->api_url = $filmResult->url;
				$film->save();
				
				$peopleIds = Person::whereIn('api_url', $filmResult->characters)->pluck('id')->toArray();
				$film->people()->sync($peopleIds);
				
				$commonApi->setMessage(Config::get('constants.successfully_saved'));
				$commonApi->setResponseCode(Config::get('constants.response_code_success'));
				$commonApi->setStatus(Config::get('constants.status_true'));
			}
		}
        return $commonApi->getJson();
    }
	
	/**
     * get film characters from database
     *
     * @return [json] object
     */
    public function getCharacters(Request $request)
    {
        $getCharacters = new GetCharacters();
        $validator = Validator::make($request->all(),[
			'film_url' => 'required|string',
        ]);
		if ($validator->fails()) {
			$errorMessage = "";
			$errorArray = json_decode($validator->messages());
			foreach($errorArray as $key => $value) {
				$errorMessage = $errorMessage.$value[0].", ";
			}
			$errorMessage = substr($errorMessage,0,strlen($errorMessage)-2);
			$getCharacters->setMessage($errorMessage);
			$getCharacters->setResponseCode(Config::get('constants.response_code_server_error'));
			$getCharacters->setStatus(Config::get('constants.status_false'));
		}else{
			$film = Film::where('api_url', $request->film_url)->first();
			if(empty($film)){
				$getCharacters->setMessage(Config::get('constants.something_went_wrong'));
				$getCharacters->setResponseCode(Config::get('constants.response_code_server_error'));
				$getCharacters->setStatus(Config::get('constants.status_false'));
			} else {
				foreach($film->people as $person){
					$getCharacters->addCharacterToList($person->id, $person->name, $person->height, $person->mass, $person->hair_color, $person->skin_color, $person->eye_color, $person->birth_year, $person->gender, $person->homeworld);
				}
				
				$getCharacters->setMessage(Config::get('constants.list_fetch_success'));
				$getCharacters->setResponseCode(Config::get('constants.response_code_success'));
				$getCharacters->setStatus(Config::get('constants.status_true'));
			}
		}
        return $getCharacters->getJson();
    }
}
