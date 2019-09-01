<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    public function people(){
		return $this->belongsToMany('App\Person', 'person_film_pivot', 'film_id', 'person_id');
	}
}
