<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Http\Resources\StallOwnerChildResource;
use App\Http\Resources\StallOwnerEmpResource;
use App\Interface\Service\EmployeeServiceInterface;
use App\Interface\Repository\ChildrenRepositoryInterface;
use App\Interface\Repository\EmployeeRepositoryInterface;

class EmployeeService implements EmployeeServiceInterface
{
    private $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function findMany(object $payload)
    {
        $employee = $this->employeeRepository->findMany($payload);

        return StallOwnerEmpResource::collection($employee);
    }

    public function create(object $payload)
    {
        $user = $this->employeeRepository->create($payload);

        return new UserResource($user);
    }

    public function show(string $id) {}

    public function update(object $payload, string $id)
    {
        $user = $this->employeeRepository->update($payload, $id);

        return new UserResource($user);
    }

    public function delete(string $id)
    {
        return $this->employeeRepository->delete($id);
    }
}
