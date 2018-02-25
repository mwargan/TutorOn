<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
	    public $timestamps = false;

        protected $fillable = [
        'name', 'native_name', 'iso639_1'
    ];

}
