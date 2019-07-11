<?php

namespace UM\Repositories\Contracts;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Detach User From.
     *
     * @param int $userId
     * @param int $groupId
     *
     * @return mixed
     */
    public function detach(int $userId, int $groupId);

    /**
     * Attach User To Group.
     *
     * @param int $userId
     * @param int $groupId
     *
     * @return mixed
     */
    public function attach(int $userId, int $groupId);

    /**
     * Check if user is admin.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function isAdmin(int $userId) : bool;
}
