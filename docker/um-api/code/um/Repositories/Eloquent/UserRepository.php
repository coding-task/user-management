<?php

namespace UM\Repositories\Eloquent;


use App\User;

class UserRepository extends BaseRepository
{
    /**
     * Specify Model Class Name.
     *
     * @return string
     */
    function model() : string
    {
       return User::class;
    }
}
