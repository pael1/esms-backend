<?php

namespace App\Service;

use App\Http\Resources\ParameterResource;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Service\ParameterServiceInterface;

class ParameterService implements ParameterServiceInterface
{
    private $parameterRepository;

    public function __construct(ParameterRepositoryInterface $parameterRepository)
    {
        $this->parameterRepository = $parameterRepository;
    }

    public function findMany(object $payload)
    {
        $parameter = $this->parameterRepository->findMany($payload);

        return ParameterResource::collection($parameter);
    }

    public function findByFieldId(string $id)
    {
        // $parameter = $this->parameterRepository->findByFieldId($id);

        // return ParameterResource::collection($parameter); // for multiple data
        // return new ParameterResource($parameter); //for only 1 data
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
