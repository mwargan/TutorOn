<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubjectLanguage extends Model
{
    protected $fillable = [
        'user_subject_id', 'language_id'
    ];

    /**
     * the owner of the file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function user_subject()
    {
        return $this->belongsTo('App\Components\Subject\Models\UserClass', "user_subject_id");
    }

    public function language()
    {
        return $this->hasOne('App\language', "language_id");
    }
}
