<?php

namespace App\Components\Subject\Models;

use Illuminate\Database\Eloquent\Model;

class UserClass extends Model
{
    protected $connection = 'mysql';
    /**
     * The valid permission values
     * 1 means allow and 0 means deny
     *
     * @var array
     */

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_subject_pivot_table';

    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'subject_id', 'price', 'description'];

    /**
     * the rules of the Group for validation before persisting
     *
     * @var array
     */
    public static $rules = array(
        'user_id' => 'required',
        'subject_id' => 'required',
        'price' => 'required',
        'description' => 'required'
    );

    /**
     * get validation rules
     *
     * @return array
     */
    public function getValidationRules()
    {
        return self::$rules;
    }

    /**
     * returns the subjects on this group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subject()
    {
        return $this->belongsTo('App\Components\Subject\Models\Subject', "subject_id");
    }

    public function user()
    {
        return $this->belongsTo('App\Components\User\Models\User', "user_id");
    }

    public function lessons()
    {
        return $this->hasMany('App\Components\UserClass\Models\UserClasses', "class_id", "id");
    }

    public function languages()
    {
        return $this->hasMany('App\UserSubjectLanguage', "user_subject_id");
    }
    public function students()
    {
        return $this->hasManyThrough('App\Components\User\Models\User', 'App\Components\UserClass\Models\UserClasses','class_id', 'id');
    }

}
