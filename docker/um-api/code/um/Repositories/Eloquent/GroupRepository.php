<?php

namespace UM\Repositories\Eloquent;

use App\Group;
use UM\Repositories\Contracts\GroupRepositoryInterface;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
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

    /**
     * Check If User Exist in Group.
     *
     * @param int $id
     *
     * @return bool
     * @throws \App\Exceptions\RepositoryException
     */
    public function userExistInGroup(int $id) : bool
    {
        $group = $this->find($id);

        return count($group->users) === 0;
    }
}
