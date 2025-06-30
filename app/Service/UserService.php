<?php

namespace App\Service;

use App\Http\Resources\UserAccountResource;
use App\Http\Resources\UserResource;
use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Service\UserServiceInterface;

class UserService implements UserServiceInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findManyUsers(object $payload)
    {
        // dd($payload);
        $users = $this->userRepository->findMany($payload);

        return UserAccountResource::collection($users);
    }

    public function findUserById(string $UserId)
    {

        $user = $this->userRepository->findById($UserId);

        // return UserResource::collection($user); for multiple data
        return new UserAccountResource($user); //for only 1 data
    }

    public function findUserByEmail(string $email)
    {

        $user = $this->userRepository->findByEmail($email);

        // return UserResource::collection($user); // for multiple data
        return new UserResource($user); //for only 1 data
    }

    public function createUser(object $payload)
    {
        $user = $this->userRepository->create($payload);

        return new UserResource($user);
    }

    public function updateUser(object $payload, string $id)
    {
        $user = $this->userRepository->update($payload, $id);

        return new UserResource($user);
    }

    public function deleteUser(string $id)
    {
        return $this->userRepository->delete($id);
    }
}
