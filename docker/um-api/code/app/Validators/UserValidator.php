<?php

namespace App\Validators;

class UserValidator extends AbstractValidator
{
    public function validateCreate(array $user)
    {
        $this->validate($user, [
            'email' => 'required|email|unique:users',
            'name' => 'required|max:60',
            'password' => 'required'
        ]);
    }

    public function validateUpdate(array $user, int $id)
    {
        $this->validate($user, [
            'email' => 'required|email|unique:users,email,'. $id,
            'name' => 'required',
            'password' => 'required|sometimes',
        ]);
    }

    /**
     * Validate User Group.
     *
     * @param array $userGroup
     */
    public function validateUserGroup(array $userGroup)
    {
        $this->validate($userGroup, [
            'user_id' => 'required|int',
            'group_id' => 'required|int',
        ]);
    }
}
