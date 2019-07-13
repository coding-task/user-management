<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceException extends HttpException
{
    /** @var string */
    const AUTH_ERROR_CODE = 'auth_error';

    /**
     * MessageBag errors.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Create a new resource exception instance.
     *
     * @param string                               $errorCode
     * @param \Illuminate\Support\MessageBag|array $errors
     * @param \Exception                           $previous
     * @param array                                $headers
     * @param int                                  $code
     *
     * @return void
     */
    public function __construct($errorCode = null, $errors = null, $code = 0, Exception $previous = null, $headers = [])
    {
        if (is_null($errors)) {
            $this->errors = new MessageBag;
        } else {
            $this->errors = is_array($errors) ? new MessageBag($errors) : $errors;
        }
        parent::__construct(
            $code > 0 ? $code : Response::HTTP_UNPROCESSABLE_ENTITY,
            $errorCode,
            $previous,
            $headers,
            $code
        );
    }

    /**
     * Get the errors message bag.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Determine if message bag has any errors.
     *
     * @return bool
     */
    public function hasErrors()
    {
        return ! $this->errors->isEmpty();
    }
}
