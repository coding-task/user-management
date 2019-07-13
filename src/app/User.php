<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * User Group Relation.
     *
     * @return BelongsToMany
     */
    public function group() : BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups');
    }

    /**
     * Set Password Attribute.
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
