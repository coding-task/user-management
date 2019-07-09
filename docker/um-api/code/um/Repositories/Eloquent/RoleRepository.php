<?php

namespace UM\Repositories\Eloquent;

use App\Role;

class RoleRepository extends BaseRepository
{
    /**
     * Specify Model Class Name.
     *
     * @return string
     */
    function model()
    {
        return Role::class;
    }
}
