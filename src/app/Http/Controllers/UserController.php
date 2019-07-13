<?php

namespace App\Http\Controllers;

use App\Exceptions\ResourceException;
use App\Validators\UserValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UM\Repositories\Contracts\UserRepositoryInterface;

/**
 * Class UserController
 *
 * @group User Management
 */
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
     * @authenticated
     * @response 200
     * {
     *"data": [
     *{
    "id": 1,
    "name": "super_admin",
     "email": "superadmin@admin.com"
     *},
     *{
    "id": 2,
    "name": "customer",
    "email": "customer@mail.com"
     *},
     *{
    "id": 3,
    "name": "user",
    "email": "user@mail.com"
     *}
     *]
     *}
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
     * @bodyparam email string required User email
     * @bodyParam name string required User Name
     * @bodyParam password string required User Password
     * @response
     * {
     *"data": {
     *"name": "Ramesh Sharma",
     *"email": "ssss@gmail.com",
     *"updated_at": "2019-07-13 19:10:24",
     *"created_at": "2019-07-13 19:10:24",
     *"id": 13
     *}
     *}
     * @response 422
     * {
     *"errors": {
     *"email": [
     *"The email field is required."
     *]
     *}
     *}
     *
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $this->validator->validateCreate($request->all());

        return response()->json(
            ['data' => $this->userRepository->create($request->all())],
            Response::HTTP_CREATED
        );
    }

    /**
     * Update User.
     *
     * @param int $id
     * @param Request $request
     * @authenticated
     * @bodyparam email string required User email
     * @bodyParam name string required User Name
     * @bodyParam password string required User Password
     * @queryParam id User Id
     * @response 204
     * @response 422
     * {
     *"errors": {
     *"email": [
     *"The email field is required."
     *]
     *}
     *}
     *
     * @return JsonResponse
     *
     */
    public function update(int $id, Request $request) : JsonResponse
    {
        $this->validator->validateUpdate($request->all(), $id);

        $this->userRepository->update($request->all(), $id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Show User.
     *
     * @param int $id
     * @authenticated
     * @queryParam id int User Id
     * @response
     * {
     *"data": {
     *"name": "Ramesh Sharma",
     *"email": "ssss@gmail.com",
     *"updated_at": "2019-07-13 19:10:24",
     *"created_at": "2019-07-13 19:10:24",
     *"id": 13
     *}
     *}
     * @response 404
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
     * @authenticated
     * @queryParam id User Id
     * @response 201
     * @response 422
     * {
     *"errors": {
     *"app_error": [
     * "Cannot delete Super Admin"
     *]
     *}
     *}
     *
     * @return Response
     */
    public function delete(int $id)
    {
        if ($this->userRepository->isAdmin($id)) {
            throw new ResourceException(
                null,
                ['app_error' => 'Cannot delete Super Admin.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->userRepository->delete($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Assign User to Group.
     *
     * @param Request $request
     *
     * @authenticated
     * @bodyParam user_id int required User Id
     * @bodyParam group int required Group Id
     * @response 204
     *
     * @return Response
     */
    public function assignUserToGroup(Request $request) : Response
    {
        $this->validator->validateUserGroup($request->all());

        $this->userRepository->attach($request->get('user_id'), $request->get('group_id'));

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Remove User From Group.
     *
     * @param Request $request
     *
     * @authenticated
     * @bodyParam user_id int required User Id
     * @bodyParam group int required Group Id
     * @response 204
     * @response 422
     * {
     *"errors": {
     *"app_error": [
     * "Cannot Remove Super Admin."
     *]
     *}
     *}
     *
     * @return mixed
     */
    public function removeUserFromGroup(Request $request)
    {
        $this->validator->validateUserGroup($request->all());

        if ($this->userRepository->isAdmin($request->get('user_id'))) {
            throw new ResourceException(
                null,
                ['app_error' => 'Cannot Remove Super Admin.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
                );
        }

        $this->userRepository->detach($request->get('user_id'), $request->get('group_id'));

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
