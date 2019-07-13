<?php

namespace App\Http\Controllers;

use App\Validators\AuthValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use UM\Services\AuthService;

class AuthenticateController extends Controller
{
    /** @var AuthService */
    private $authService;

    /** @var AuthValidator */
    private $validator;

    public function __construct(AuthService $authService, AuthValidator $validator)
    {
        $this->authService = $authService;
        $this->validator   = $validator;
    }

    /**
     * Authenticate user.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function authenticate(Request $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $this->validator->validateAuthentication($credentials);

        return $this->authService->authenticate($credentials);
    }
}
