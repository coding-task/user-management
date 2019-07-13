<?php

namespace App\Validators;

class GroupValidator extends AbstractValidator
{
    /**
     * Validate Create Group.
     *
     * @param array $group
     */
    public function validateCreate(array $group)
    {
        $this->validate($group, [
            'name' => 'required|max:30|unique:groups',
        ]);
    }

    /**
     * Validate Update Group.
     *
     * @param array $group
     * @param int $id
     */
    public function validateUpdate(array $group, int $id)
    {
        $this->validate($group, [
            'name' => 'required|max:30|unique:groups,name,' . $id,
        ]);
    }
}
