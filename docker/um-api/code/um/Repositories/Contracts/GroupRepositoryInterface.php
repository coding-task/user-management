<?php

namespace UM\Repositories\Contracts;

interface GroupRepositoryInterface extends BaseRepositoryInterface
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
