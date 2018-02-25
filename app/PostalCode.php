<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    public $timestamps = false;
        protected $fillable = [
        'postal_code', 'locality_id'
    ];
}
