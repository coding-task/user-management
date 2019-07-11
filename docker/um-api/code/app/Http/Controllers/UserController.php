<?php

namespace App\Http\Controllers;

use App\Validators\UserValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UM\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    /** @var UserRepositoryInterface  */
    private $userRepository;

    /** @var UserValidator */
    private $validator;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param UserValidator $userValidator
     */
    public function __construct(UserRepositoryInterface $userRepository, UserValidator $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->validator      = $userValidator;
    }

    /**
     * User Index.
     *
     * @return JsonResponse
     */
    public function index()
    {
       return response()->json(['data' => $this->userRepository->all(['id', 'email', 'name'])]);
    }

    /**
     * Create User.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $this->validator->validateCreate($request->all());

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
     */
    public function update(int $id, Request $request) : JsonResponse
    {
        $this->validator->validateUpdate($request->all(), $id);

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
     */
    public function assignUserToGroup(Request $request) : Response
    {
        $this->validator->validateUserGroup($request->all());

        $this->userRepository->attach($request->get('user_id'), $request->get('group_id'));

        return response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove User From Group.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function removeUserFromGroup(Request $request)
    {
        $this->validator->validateUserGroup($request->all());

        if ($this->userRepository->isAdmin($request->get('user_id'))) {
            return response()->json(
                [
                    'errors' => 'Cannot Remove Admin User.'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->userRepository->detach($request->get('user_id'),  $request->get('group_id'));

        return response('', Response::HTTP_NO_CONTENT);
    }
}
