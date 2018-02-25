<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    public $timestamps = false;
        protected $fillable = [
        'name', 'country_id'
    ];
}
