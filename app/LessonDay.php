<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonDay extends Model
{
    protected $fillable = [
        'lesson_id', 'weekday_id', 'meeting_time', 'lesson_duration', 'updated_by'
    ];
    /**
     * the owner of the file
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function lesson()
    {
        return $this->belongsTo('App\Components\UserClass\Models\UserClasses', "lesson_id");
    }
}
