<?php

namespace App\Interface\Repository;

interface ParameterRepositoryInterface
{
    public function findMany(object $payload);

    public function findSubSection(object $payload);

    public function findByFieldIdFieldValue(string $fieldId, string $fieldValue);

    public function create(object $payload);

    public function update(object $payload, string $id);

    public function getSection(string $section_code);

    public function delete(string $id);
}
