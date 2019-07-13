<?php

namespace App\Http\Controllers;

use App\Exceptions\ResourceException;
use App\Group;
use App\Validators\GroupValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UM\Repositories\Contracts\GroupRepositoryInterface;

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
     *
     * @return JsonResponse
     */
    public function create(Request $request) : JsonResponse
    {
        $this->validator->validateCreate($request->all());

        return response()->json([
            'data' => $this->groupRepository->create($request->all())],
            Response::HTTP_CREATED
        );
    }

    /**
     * Update Group.
     *
     * @param int $id
     * @param Request $request
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
     * @return JsonResponse
     *
     */
    public function show(int $id) : JsonResponse
    {
        return response()->json(['data' => $this->groupRepository->find($id)]);
    }

    /**
     * Delete Group.
     *
     * @param int $id
     *
     * @return mixed
     *
     */
    public function delete(int $id)
    {
        if (!$this->groupRepository->userExistInGroup($id)) {
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
