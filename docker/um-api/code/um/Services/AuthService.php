<?php

namespace UM\Services;

use App\Exceptions\ResourceException;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use UM\Repositories\Contracts\UserRepositoryInterface;

class AuthService
{
    /** @var JWT */
    protected $jwt;

    /** @var UserRepositoryInterface */
    protected $userRepository;

    /**
     * AuthService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param JWT $jwt
     */
    public function __construct(UserRepositoryInterface $userRepository, JWT $jwt)
    {
        $this->jwt            = $jwt;
        $this->userRepository = $userRepository;
    }
    /**
     * @param array $credentials
     *
     * @return JsonResponse
     */
    public function authenticate(array $credentials) : JsonResponse
    {
        $user = $this->userRepository->findByField('email', $credentials['email']);

        if (app('hash')->check($credentials['password'], $user->password)) {
            $payload = [
                'issuer' => env('APP_NAME'),
                'subject' => $user->id,
                'issued_at' => Carbon::now()->toDateTimeString(),
                'expired_at' => Carbon::now()->addHour()->toDateTimeString(),
            ];

            return response()->json([
                'data' => [
                    'token' => $this->jwt->encode($payload, env('JWT_ENCRYPT_KEY')),
                ],
            ], Response::HTTP_OK);
        }

        throw new ResourceException(
            ['auth' => 'Wrong credentials.'],
            Response::HTTP_BAD_REQUEST);
    }
}
