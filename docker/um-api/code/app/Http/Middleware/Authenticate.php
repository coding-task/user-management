<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Firebase\JWT\ExpiredException;
use App\Exceptions\ResourceException;
use UM\Repositories\Contracts\UserRepositoryInterface;

class Authenticate
{
    /** @var UserRepositoryInterface */
    protected $userRepository;

    /** @var JWT */
    protected $jwt;

    /**
     * JwtMiddleware constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param JWT $jwt
     */
    public function __construct(UserRepositoryInterface $userRepository, JWT $jwt)
    {
        $this->userRepository = $userRepository;
        $this->jwt  = $jwt;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param array   $roles
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $token = $request->header('token');

        if ( ! $token) {
            throw new ResourceException(
                ResourceException::AUTH_ERROR_CODE,
                ['auth' => 'Invalid token.'],
                Response::HTTP_BAD_REQUEST);
        }

        try {
            $credentials = $this->jwt->decode($token, env('JWT_ENCRYPT_KEY'), ['HS256']);
        } catch (ExpiredException $e) {
            throw new ResourceException(
                ResourceException::AUTH_ERROR_CODE,
                ['auth' => 'Token expired.'],
                Response::HTTP_BAD_REQUEST
            );
        } catch (Exception $e) {
            throw new ResourceException(
                ResourceException::AUTH_ERROR_CODE,
                ['auth' => 'Cannot decode token.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $user = $this->userRepository->find($credentials->subject);
        $role = $user->group->first();

        if ( ! in_array($role->name, $roles)) {
            throw new ResourceException(
                ResourceException::AUTH_ERROR_CODE,
                ['auth' => 'Unauthorized.'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $request->user = $user;

        return $next($request);
    }
}
