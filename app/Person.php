<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    public function films(){
		return $this->belongsToMany('App\Film', 'person_film_pivot', 'person_id', 'film_id');
	}
}
