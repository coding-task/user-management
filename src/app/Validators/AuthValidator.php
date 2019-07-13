<?php

namespace App\Validators;

class AuthValidator extends AbstractValidator
{
    /**
     * Validate authentication.
     *
     * @param array $data
     */
    public function validateAuthentication(array $data)
    {
        $this->validate($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
}
