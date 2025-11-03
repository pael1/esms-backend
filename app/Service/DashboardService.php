<?php

namespace App\Service;

use App\Interface\Repository\ChildrenRepositoryInterface;
use App\Interface\Repository\DashboardRepositoryInterface;
use App\Interface\Service\DashboardServiceInterface;

class DashboardService implements DashboardServiceInterface
{
    private $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getTotalRevenue(object $payload)
    {
        $revenue = $this->dashboardRepository->getTotalRevenue($payload);

        return $revenue;
    }
}
