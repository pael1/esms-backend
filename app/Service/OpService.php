<?php

namespace App\Service;

use App\Http\Resources\ParameterResource;
use App\Http\Resources\StallOPResource;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Service\OpServiceInterface;

class OpService implements OpServiceInterface
{
    private $opRepository;

    public function __construct(OpRepositoryInterface $opRepository)
    {
        $this->opRepository = $opRepository;
    }

    public function findMany(object $payload)
    {
        $op = $this->opRepository->findMany($payload);

        return StallOPResource::collection($op);
    }

    public function findById(string $id)
    {
        // $op = $this->opRepository->findByFieldId($id);

        // return ParameterResource::collection($op); // for multiple data
        // return new ParameterResource($op); //for only 1 data
    }

    public function create(object $payload)
    {
        // $user = $this->userRepository->create($payload);

        // return new UserResource($user);
    }

    public function update(object $payload, string $id)
    {
        // $user = $this->userRepository->update($payload, $id);

        // return new UserResource($user);
    }

    public function delete(string $id)
    {
        // return $this->userRepository->delete($id);
    }
}
