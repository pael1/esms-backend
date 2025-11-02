<?php

namespace App\Interface\Repository;

interface DashboardRepositoryInterface
{
    public function getTotalRevenue(object $payload);
}
