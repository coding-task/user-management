<?php

namespace App\Validators;

use App\Exceptions\ResourceException;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Http\Response;

abstract class AbstractValidator
{
    /** @var Validator */
    protected $validator;

    /**
     * AbstractValidator constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param array $params
     * @param array $rules
     * @param array $messages
     *
     * @throws ResourceException
     */
    public function validate(array $params, array $rules, $messages = [])
    {
        $validator = $this->validator->make($params, $rules, $messages);
        if ($validator->fails()) {
            throw new ResourceException(Response::HTTP_UNPROCESSABLE_ENTITY, $validator->messages());
        }
    }
}
