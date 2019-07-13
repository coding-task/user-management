<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model
{
    const SUPER_ADMIN = 'super_admin';

    protected $fillable = [
        'name'
    ];

    /**
     * Users Group Relation.
     *
     * @return BelongsToMany
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups');
    }
}
