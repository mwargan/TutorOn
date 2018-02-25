<?php

namespace App\Components\UserClass\Models;

use Illuminate\Database\Eloquent\Model;

class UserClassesMeta extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_class_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key','value'];

    /**
     * the rules of the Group for validation before persisting
     *
     * @var array
     */
    public static $rules = [
        'key' => 'required',
        'value' => 'required'
    ];

    /**
     * the user owner of this meta data
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(UserClasses::class, 'class_id');
    }

    /**
     * get user meta keys
     *
     * @return array
     */
    public static function getKeys()
    {
        return [
            ['key'=>'profile_image_url', 'value'=>'http://', 'label'=>'User Profile Image', 'field_type'=>'text'],
            ['key'=>'phone', 'value'=>'N/A', 'label'=>'User Phone Number', 'field_type'=>'text'],
            ['key'=>'address', 'value'=>'N/A', 'label'=>'User Address', 'field_type'=>'text'],
        ];
    }
}
