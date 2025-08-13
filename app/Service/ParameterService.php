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

    public function findSubSection(object $payload)
    {
        $parameter = $this->parameterRepository->findSubSection($payload);

        return ParameterResource::collection($parameter);
    }

    // public function findByFieldIdFieldValue(string $fieldId, string $fieldValue)
    // {
    //     $parameter = $this->parameterRepository->findByFieldIdFieldValue($fieldId, $fieldValue);
    //     return new ParameterResource($parameter);
    // }

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
