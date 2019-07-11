<?php

namespace UM\Repositories\Contracts;

interface GroupRepositoryInterface
{
    /**
     * Check If User Exist in Group.
     *
     * @param int $id
     *
     * @return bool
     */
    public function userExistInGroup(int $id) : bool;
}
