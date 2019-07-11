<?php

namespace UM\Repositories\Eloquent;

use App\Group;
use App\User;
use UM\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
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

    /**
     * Detach User From.
     *
     * @param int $userId
     * @param int $groupId
     *
     * @return mixed
     * @throws \App\Exceptions\RepositoryException
     */
    public function detach(int $userId, int $groupId)
    {
        $user = $this->find($userId);

        return $user->group()->detach($groupId);
    }

    /**
     * Attach User To Group.
     *
     * @param int $userId
     * @param int $groupId
     *
     * @return mixed
     * @throws \App\Exceptions\RepositoryException
     */
    public function attach(int $userId, int $groupId)
    {
        $user = $this->find($userId);

        return $user->group()->sync($groupId);
    }

    /**
     * Check if user is admin.
     *
     * @param int $userId
     *
     * @return bool
     * @throws \App\Exceptions\RepositoryException
     */
    public function isAdmin(int $userId) : bool
    {
        $user = $this->find($userId);

        return $user->group()->where('name', '=',Group::ADMIN)->exists();
    }
}
