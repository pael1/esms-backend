<?php

namespace App\Service;

use App\Http\Resources\UserResource;
use App\Http\Resources\StallOwnerEmpResource;
use App\Http\Resources\StallOwnerChildResource;
use App\Http\Resources\StallOwnerFilesResource;
use App\Interface\Service\FileServiceInterface;
use App\Interface\Service\EmployeeServiceInterface;
use App\Interface\Repository\FileRepositoryInterface;
use App\Interface\Repository\ChildrenRepositoryInterface;
use App\Interface\Repository\EmployeeRepositoryInterface;

class FileService implements FileServiceInterface
{
    private $fileRepository;

    public function __construct(FileRepositoryInterface $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function findMany(object $payload)
    {
        $employee = $this->fileRepository->findMany($payload);

        return StallOwnerFilesResource::collection($employee);
    }

    public function create(object $payload)
    {
        $user = $this->fileRepository->create($payload);

        return StallOwnerFilesResource::make($user);
    }

    public function show(string $id) {}

    public function update(object $payload, string $id)
    {
        $user = $this->fileRepository->update($payload, $id);

        return new UserResource($user);
    }

    public function delete(string $id)
    {
        return $this->fileRepository->delete($id);
    }
}
