<?php

namespace App\Http\Controllers;

use App\Exceptions\ResourceException;
use App\Group;
use App\Validators\GroupValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UM\Repositories\Contracts\GroupRepositoryInterface;

/**
 * Class GroupController
 *
 * @group User Group Management.
 *
 * @package App\Http\Controllers
 */
class GroupController extends Controller
{
    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var GroupValidator */
    private $validator;

    /**
     * GroupController constructor.
     *
     * @param GroupRepositoryInterface $groupRepository
     * @param GroupValidator $groupValidator
     */
    public function __construct(GroupRepositoryInterface $groupRepository, GroupValidator $groupValidator)
    {
        $this->groupRepository = $groupRepository;
        $this->validator       = $groupValidator;
    }

    /**
     * Group Index.
     *
     * @authenticated
     * @response 200
     * {
     *"data": [
     *{
    "id": 1,
    "name": "super_admin"
     *},
     *{
    "id": 2,
    "name": "customer"
     *},
     *{
    "id": 3,
    "name": "user"
     *}
     *]
     *}
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => $this->groupRepository->all(['id', 'name'])]);
    }

    /**
     * Create Group.
     *
     * @param Request $request
     * @authenticated
     * @bodyParam name string required Group Name
     *
     *@response 200 {
     *"data": {
     *"name": "users",
     *"updated_at": "2019-07-13 19:17:34",
     *"created_at": "2019-07-13 19:17:34",
     *"id": 12
     *}
     *}
     * @response 422 {
     * "errors": {
     * "name": [
     * "The name field is required."
     *      ]
     *    }
      * }
     *
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $this->validator->validateCreate($request->all());

        return response()->json(
            ['data' => $this->groupRepository->create($request->all())],
            Response::HTTP_CREATED
        );
    }

    /**
     * Update Group.
     *
     * @param int $id
     * @param Request $request
     *
     * @authenticated
     * @bodyParam name string Group Name
     * @queryParam id Group Id
     * @response 204
     * @response 422 {
     * "errors": {
     * "name": [
     * "The name field is required."
     *      ]
     *    }
     * }
     *
     * @return JsonResponse
     */
    public function update(int $id, Request $request) : JsonResponse
    {
        $this->validator->validateUpdate($request->all(), $id);

        $role = $this->groupRepository->find($id);

        if ($role->name === Group::SUPER_ADMIN) {
            throw new ResourceException(
                null,
                ['app_error' => 'Cannot Update Super Admin.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->groupRepository->update($request->all(), $id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Show Group.
     *
     * @param int $id
     *
     * @authenticated
     * @queryParam id Group Id
     *@response 200 {
     *"data": {
     *"id": 4,
     *"name": "super_admin",
     *"created_at": "2019-07-13 11:28:32",
     *"updated_at": "2019-07-13 11:28:32"
     *}
     *}
     * @response 404
     * @return JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        return response()->json(['data' => $this->groupRepository->find($id)]);
    }

    /**
     * Delete Group.
     *
     * @param int $id
     * @authenticated
     * @queryParam id Group Id
     * @response 201
     * @response 422
     * {
     *"errors": {
     *"app_error": [
    * "Cannot Delete Group. Group has Users."
     *]
     *}
     *}
     * @return mixed
     */
    public function delete(int $id)
    {
        if ( ! $this->groupRepository->userExistInGroup($id)) {
            throw new ResourceException(
                null,
                ['app_error' => 'Cannot Delete Group. Group has Users.'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $this->groupRepository->delete($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
