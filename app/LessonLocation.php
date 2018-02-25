<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class LessonLocation extends Model
{
    use SpatialTrait;

    protected $fillable = [
        'lesson_id', 'location', 'locality_id', 'postal_code_id', 'updated_by'
    ];

    protected $spatialFields = [
        'location'
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
