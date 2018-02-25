<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    protected $fillable = [
        'user_id', 'locality_id', 'postal_code_id'
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

    public function locality()
    {
        return $this->belongsTo('App\Locality', "locality_id");
    }
}
