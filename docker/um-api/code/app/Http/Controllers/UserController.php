<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UM\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    /** @var UserRepositoryInterface  */
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
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
       return response()->json(['data' => $this->userRepository->all(['email', 'name'])]);
    }

    /**
     * Create User.
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request) : JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'name' => 'required|max:60',
            'password' => 'required'
        ]);

        return response()->json(['data' => $this->userRepository->create($request->all())]);
    }

    /**
     * Update User.
     *
     * @param int $id
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(int $id, Request $request) : JsonResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',
        ]);

        return response()->json(['data' => $this->userRepository->update($request->all(), $id)]);
    }

    /**
     * Show User.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        return response()->json(['data' => $this->userRepository->find($id)]);
    }

    /**
     * Delete User.
     *
     * @param int $id
     *
     * @return Response
     */
    public function delete(int $id)
    {
        if ($this->userRepository->isAdmin($id)) {
            return response()->json(['error' => 'Cannot Remove Admin User.']);
        }

        $this->userRepository->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Assign User to Group.
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function assignUserToGroup(Request $request) : Response
    {
        $this->validate($request, [
            'user_id' => 'required|int',
            'group_id' => 'required|int',
        ]);

        $this->userRepository->attach($request->get('user_id'), $request->get('group_id'));

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove User From Group.
     *
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function removeUserFromGroup(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|int',
            'group_id' => 'required|int',
        ]);

        if ($this->userRepository->isAdmin($request->get('user_id'))) {
            return response()->json(['error' => 'Cannot Remove Admin User.']);
        }

        $this->userRepository->detach($request->get('user_id'),  $request->get('group_id'));

        return response('', Response::HTTP_NO_CONTENT);
    }
}
