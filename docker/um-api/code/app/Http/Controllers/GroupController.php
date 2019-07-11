<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use UM\Repositories\Contracts\GroupRepositoryInterface;

class GroupController extends Controller
{
    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /**
     * GroupController constructor.
     *
     * @param GroupRepositoryInterface $groupRepository
     */
    public function __construct(GroupRepositoryInterface $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * Group Index.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => $this->groupRepository->all(['name'])]);
    }

    /**
     * Create Group.
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request) : JsonResponse
    {
        $this->validate($request, [
            'name' => 'required|max:30|unique:groups',
        ]);

        return response()->json(['data' => $this->groupRepository->create($request->all())]);
    }

    /**
     * Update Group.
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
            'name' => 'required|max:30',
        ]);

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
     * @throws \App\Exceptions\RepositoryException
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
