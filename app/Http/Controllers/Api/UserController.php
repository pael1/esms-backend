<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Interface\Service\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        return $this->userService->findManyUsers($request);
    }

    public function store(UserStoreRequest $request)
    {
        return $this->userService->createUser($request);
    }

    public function show(string $id)
    {
        return $this->userService->findUserById($id);
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        return $this->userService->updateUser($request, $id);
    }

    public function destroy(string $id)
    {
        return $this->userService->deleteUser($id);
    }
}
