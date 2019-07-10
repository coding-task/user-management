<?php

namespace UM\Repositories\Eloquent;

use App\Group;

class GroupRepository extends BaseRepository
{
    /**
     * Specify Model Class Name.
     *
     * @return string
     */
    function model()
    {
        return Group::class;
    }
}
