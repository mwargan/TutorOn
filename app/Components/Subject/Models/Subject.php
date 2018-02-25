<?php

namespace App\Components\Subject\Models;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'tags';

    protected $primaryKey = "tag-id";

    public $timestamps = false;

    protected $fillable = [
        't-data'
    ];

    /**
     * the owner of the file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classes()
    {
        return $this->hasMany(UserClass::class, "subject_id");
    }

    public function users()
    {
        return $this->hasManyThrough("App\Components\User\Models\User", UserClass::class, "user_id", "id", "user_id", "subject_id");
    }

}
