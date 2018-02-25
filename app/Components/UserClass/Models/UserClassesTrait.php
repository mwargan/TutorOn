<?php
/**
 * Created by PhpStorm.
 * UserClasses: darryl
 * Date: 5/27/2017
 * Time: 7:55 AM
 */

namespace App\Components\UserClass\Models;
use Hash;

/**
 * Class UserClassesTrait
 * @package App\SOLAR\UserClasses\Models
 */
trait UserClassesTrait
{
    /**
     * hashes password
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * serializes permission attribute on the fly before saving to database
     *
     * @param $permissions
     */
    public function setPermissionsAttribute($permissions)
    {
        $this->attributes['permissions'] = serialize($permissions);
    }

    /**
     * unserializes permissions attribute before spitting out from database
     *
     * @return mixed
     */
    public function getPermissionsAttribute()
    {
        if(empty($this->attributes['permissions']) || is_null($this->attributes['permissions'])) return [];

        return unserialize($this->attributes['permissions']);
    }

    /**
     * returns the groups of the userclasses
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class,'userclasses_group_pivot_table','class_id');
    }

    /**
     * the userclassess subject
     *
     * @return mixed
     */

    public function class()
    {
        return $this->belongsTo('App\Components\Subject\Models\UserClass', "class_id");
    }

    /**
     * the userclassess subject
     *
     * @return mixed
     */

    public function days()
    {
        return $this->hasMany('App\LessonDay', "lesson_id");
    }

      /**
     * the userclassess subject
     *
     * @return mixed
     */

    public function location()
    {
        return $this->hasOne('App\LessonLocation', "lesson_id");
    }

    /**
     * the userclassess meta
     *
     * @return mixed
     */
    public function meta()
    {
        return $this->hasMany(UserClassesMeta::class,'class_id');
    }

    /**
     * just a helper to parse a meta value from an array to object properties
     * so it will be easy to deal in UI portion
     *
     * @return \StdClass
     */

     public function getParseMeta()
    {
        $metaClass = new \StdClass();

        // we will iterate through all of its meta relations
        // and put it as a property so it will be easy in front end
        $this->meta->each(function($meta) use (&$metaClass)
        {
            $metaClass->{$meta['key']} = $meta['value'];
        });

        // we will check if the declared meta data key
        // already exist in the $metaClass as property, if not
        // we will declare it and put value null as default
        foreach (UserClassesMeta::$META_DATA as $k => $v)
        {
            if(!property_exists($metaClass,$v)) $metaClass->{$v} = null;
        }

        return $metaClass;
    }

    /**
     * check if the userclasses is superuserclasses
     *
     * @return bool
     */
    public function isSuperUserClasses()
    {
        return $this->hasPermission('superuserclasses');
    }

    /**
     * adds a new permission or if permission already exist, just update the value
     *
     * @param int|Permission $permission
     * @param int $value the permission value (allow=1, inherit=0, deny=-1)
     * @return bool
     */
    public function addPermission($permission, $value)
    {
        $userclassesCurrentPermissions = $this->permissions;
        $updateOnly = false;

        // maybe a permission ID
        if(is_int($permission))
        {
            $Permission = Permission::find($permission);

            if(!$Permission) return false;

            // loop through current permissions if already exist,
            // if so, we will just update the value
            foreach ($userclassesCurrentPermissions as $index => $p)
            {
                if($p['permission'] == $Permission->permission)
                {
                    $updateOnly = true;
                    $userclassesCurrentPermissions[$index]['value'] = $value;
                }
            }

            // if not found yet, lets add it
            if(!$updateOnly)
            {
                $userclassesCurrentPermissions[] = [
                    'permission' => $Permission->permission,
                    'title' => $Permission->title,
                    'description' => $Permission->description,
                    'value' => $value,
                ];
            }
        }

        // maybe a permission object
        elseif ($permission instanceof Permission)
        {
            // loop through current permissions if already exist,
            // if so, we will just update the value
            foreach ($userclassesCurrentPermissions as $index => $p)
            {
                if($p['permission'] == $permission->permission)
                {
                    $updateOnly = true;
                    $userclassesCurrentPermissions[$index]['value'] = $value;
                }
            }

            // if not found yet, lets add it
            if(!$updateOnly)
            {
                $userclassesCurrentPermissions[] = [
                    'permission' => $permission->permission,
                    'title' => $permission->title,
                    'description' => $permission->description,
                    'value' => $value,
                ];
            }
        }

        // invalid
        else
        {
            return false;
        }

        // assign the new permissions value
        $this->permissions = $userclassesCurrentPermissions;

        return $this->save();
    }

    /**
     * @param int|Permission $permission
     * @return bool
     */
    public function removePermission($permission)
    {
        $userclassesCurrentPermissions = $this->permissions;

        if(is_int($permission))
        {
            $Permission = Permission::find($permission);

            if(!$Permission) return false;

            foreach ($userclassesCurrentPermissions as $index => $p)
            {
                if($p['permission'] == $Permission->permission) unset($userclassesCurrentPermissions[$index]);
            }
        }

        elseif ($permission instanceof Permission)
        {
            foreach ($userclassesCurrentPermissions as $index => $p)
            {
                if($p['permission'] == $permission->permission) unset($userclassesCurrentPermissions[$index]);
            }
        }

        else
        {
            return false;
        }

        // re-index
        array_values($userclassesCurrentPermissions);

        // assign new values
        $this->permissions = $userclassesCurrentPermissions;

        return $this->save();
    }

    /**
     * check if userclasses has permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $superUserClasses = array_get($this->getCombinedPermissions(), 'superuserclasses');

        if( $superUserClasses === UserClasses::PERMISSION_ALLOW ) return true;

        foreach($this->getCombinedPermissions() as $p => $v)
        {
            if( $p == $permission )
            {
                return $v == UserClasses::PERMISSION_ALLOW;
            }
        }

        return false;
    }

    /**
     * check if has any permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions)
    {
        if( $this->isSuperUserClasses() ) return true;

        $hasPermission = false;

        foreach($permissions as $permission)
        {
            if( $this->hasPermission($permission) )
            {
                $hasPermission = true;
            }
        }

        return $hasPermission;
    }

    /**
     * check if userclasses is in a group
     *
     * @param $group
     * @return bool
     */
    public function inGroup($group)
    {
        $found = false;

        if( is_string($group) )
        {
            $this->groups->each(function($g) use ($group, &$found)
            {
                if( $g->name == $group )
                {
                    $found = true;
                }
            });

            return $found;
        }
        else if ( is_int($group) )
        {
            $this->groups->each(function($g) use ($group, &$found)
            {
                if( $g->id == $group )
                {
                    $found = true;
                }
            });

            return $found;
        }
        else if ( is_object($group) )
        {
            $this->groups->each(function($g) use ($group, &$found)
            {
                if( $g->name == $group->name )
                {
                    $found = true;
                }
            });

            return $found;
        }
        else
        {
            return $found;
        }
    }

    /**
     * check if a userclasses is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active !== null;
    }

    /**
     * the over all permissions of the userclasses
     *
     * @return array
     */
    public function getCombinedPermissions()
    {
        // the userclasses group permissions, if userclasses has many groups, it will combine all the groups permissions
        $groupPermissions = $this->getGroupPermissions();

        // if the userclasses is a super userclasses, give the userclasses all the permissions
        if($this->inGroup(Group::SUPER_USERCLASSES_GROUP_ID))
        {
            $availablePermissions = Permission::all();

            $allPermissions = [];

            $availablePermissions->each(function($p) use (&$allPermissions)
            {
                $allPermissions[$p->permission] = 1;
            });

            return array_merge($allPermissions, $groupPermissions);
        }

        // the userclasses specific assigned permissions
        $userclassesSpecificPermissions = $this->getSpecialPermissions();

        foreach($userclassesSpecificPermissions as $uPermission => $uValue)
        {
            // if the permission is inherit
            if( $uValue == UserClasses::PERMISSION_INHERIT )
            {
                // we will check if this permission exists in his group permissions,
                // if so, we will get the value from that group permissions and we will use it as its value
                // if it does not exist on its group permissions, just deny it
                if( array_key_exists($uPermission, $groupPermissions) )
                {
                    $userclassesSpecificPermissions[$uPermission] = $groupPermissions[$uPermission];
                    unset($groupPermissions[$uPermission]);
                }
                else
                {
                    $userclassesSpecificPermissions[$uPermission] = UserClasses::PERMISSION_DENY;
                }
            }

            // if the value is allow or deny, we will check if this permission also existed on his group permissions
            // if it does, we will just remove it from there, we don't need it as it exist on userclassess permissions
            // and it is more prioritize that permissions on the group
            else
            {
                if( array_key_exists($uPermission, $groupPermissions) )
                {
                    unset($groupPermissions[$uPermission]);
                }
            }
        }

        return array_merge($userclassesSpecificPermissions, $groupPermissions);
    }

    /**
     * get all the special permissions of this userclasses
     *
     * @return array
     */
    public function getSpecialPermissions()
    {
        $permissions = array();

        foreach ($this->permissions as $sp)
        {
            $permissions[$sp['permission']] = $sp['value'];
        }

        return $permissions;
    }

    /**
     * get all the permissions of this userclasses, this is the combined permissions
     * across all groups that the userclasses is belong
     *
     * @return array
     */
    public function getGroupPermissions()
    {
        $permissions = array();

        $groups = $this->groups;

        $groups->each(function($group) use (&$permissions)
        {
            foreach($group->permissions as $gp)
            {
                // if the current permission is already on the permissions array
                // we will check if the value of the next same permission is a deny
                // if so, we will overwrite the value of the duplicated one
                // because if two groups has the same permission but different values,
                // the deny value will be prioritize
                if( array_key_exists($gp['permission'], $permissions) )
                {
                    if( $gp['value'] == UserClasses::PERMISSION_DENY )
                    {
                        $permissions[$gp['permission']] = $gp['value'];
                    }
                }
                else
                {
                    $permissions[$gp['permission']] = $gp['value'];
                }
            }
        });

        return $permissions;
    }

    /**
     * logs last login date of the userclasses
     */
    public function logLastLogin()
    {
        $this->last_login = $this->freshTimestamp();
        $this->save();
    }

    /**
     * get validation rules
     *
     * @return array
     */
    public function getValidationRules()
    {
        return self::$rules;
    }

    public function scopeOfName($query, $name)
    {
        if( $name === null || $name === '' ) return false;

        return $query->where('name','LIKE',"%{$name}%");
    }
    public function scopeOfEmail($query, $email)
    {
        if( $email === null || $email === '' ) return false;

        return $query->where('email','=',$email);
    }
}