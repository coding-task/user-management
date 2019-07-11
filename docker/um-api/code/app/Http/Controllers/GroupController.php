<?php

namespace App\Http\Controllers;

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

        return response()->json(['data' => $this->groupRepository->create($request->all())]);
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

        return response()->json(['data' => $this->groupRepository->update($request->all(), $id)]);
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
            return response()->json(['error' => 'Group has users.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->groupRepository->delete($id);

        return response('', Response::HTTP_NO_CONTENT);
    }
}
