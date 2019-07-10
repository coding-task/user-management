<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use UM\Repositories\Eloquent\GroupRepository;

class GroupController extends Controller
{
    /** @var GroupRepository */
    private $groupRepository;

    /**
     * GroupController constructor.
     *
     * @param GroupRepository $groupRepository
     */
    public function __construct(GroupRepository $groupRepository)
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
        return response()->json($this->groupRepository->all(['name']));
    }

    /**
     * Create Group.
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
            'name' => 'required|max:30',
        ]);

        return response()->json($this->groupRepository->create($request->all()));
    }

    /**
     * Update Group.
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
            'name' => 'required|max:30',
        ]);

        return response()->json($this->groupRepository->update($request->all(), $id));
    }

    /**
     * Show Group.
     *
     * @param int $id
     *
     * @return JsonResponse
     *
     * @throws \App\Exceptions\RepositoryException
     */
    public function show(int $id) : JsonResponse
    {
        return response()->json($this->groupRepository->find($id));
    }

    /**
     * Delete Group.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws \App\Exceptions\RepositoryException
     */
    public function delete(int $id) : JsonResponse
    {
        return response()->json($this->groupRepository->delete($id));
    }
}
