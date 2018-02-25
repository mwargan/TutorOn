<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLanguage extends Model
{
    protected $fillable = [
        'user_id', 'language_id'
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

    public function language()
    {
        return $this->hasOne('App\language', "language_id");
    }
}
