<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use UM\Repositories\Eloquent\UserRepository;

class UserController extends Controller
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * User Index.
     *
     * @return JsonResponse
     */
    public function index()
    {
       return response()->json($this->userRepository->all(['email', 'name']));
    }

    /**
     * Create User.
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \App\Exceptions\RepositoryException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request) : JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required'
        ]);

        return response()->json($this->userRepository->create($request->all()));
    }

    /**
     * Update User.
     *
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \App\Exceptions\RepositoryException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(int $id, Request $request) : JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',
        ]);

        return response()->json($this->userRepository->update($request->all(), $id));
    }

    /**
     * Show User.
     *
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \App\Exceptions\RepositoryException
     */
    public function show(int $id) : JsonResponse
    {
        return response()->json($this->userRepository->find($id));
    }

    /**
     * Delete User.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws \App\Exceptions\RepositoryException
     */
    public function delete(int $id) : JsonResponse
    {
        return response()->json($this->userRepository->delete($id));
    }
}
