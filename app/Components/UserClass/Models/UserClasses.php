<?php

namespace App\Components\UserClass\Models;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;


class UserClasses extends Model
{
    use Notifiable, UserClassesTrait;

    protected $connection = 'mysql';

    protected $table = 'user_class';

    protected $fillable = [
        'user_id', 'class_id'
    ];

    /**
     * the owner of the file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user()
    {
        return $this->belongsTo('App\Components\User\Models\User', "user_id");
    }

}
