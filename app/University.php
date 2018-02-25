<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'webometric_universities';

    protected $primaryKey = "uni-id";

    public $timestamps = false;

}
