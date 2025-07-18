<?php

namespace App\Providers;

use App\Interface\Repository\AwardeeRepositoryInterface;
use App\Interface\Repository\ChildrenRepositoryInterface;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Repository\ReportRepositoryInterface;
use App\Interface\Repository\SignatoryRepositoryInterface;
use App\Interface\Repository\SyncOpRepositoryInterface;
use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Service\AuthServiceInterface;
use App\Interface\Service\AwardeeServiceInterface;
use App\Interface\Service\ChildrenServiceInterface;
use App\Interface\Service\LedgerServiceInterface;
use App\Interface\Service\OpServiceInterface;
use App\Interface\Service\ParameterServiceInterface;
use App\Interface\Service\ReportServiceInterface;
use App\Interface\Service\SyncOpServiceInterface;
use App\Interface\Service\UserServiceInterface;
use App\Repository\AwardeeRepository;
use App\Repository\ChildrenRepository;
use App\Repository\LedgerRepository;
use App\Repository\OpRepository;
use App\Repository\ParameterRepository;
use App\Repository\ReportRepository;
use App\Repository\SignatoryRepository;
use App\Repository\SyncOpRepository;
use App\Repository\UserRepository;
use App\Service\AuthService;
use App\Service\AwardeeService;
use App\Service\ChildrenService;
use App\Service\LedgerService;
use App\Service\OpService;
use App\Service\ParameterService;
use App\Service\ReportService;
use App\Service\SyncOpService;
use App\Service\UserService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //repository
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AwardeeRepositoryInterface::class, AwardeeRepository::class);
        $this->app->bind(LedgerRepositoryInterface::class, LedgerRepository::class);
        $this->app->bind(ChildrenRepositoryInterface::class, ChildrenRepository::class);
        $this->app->bind(ParameterRepositoryInterface::class, ParameterRepository::class);
        $this->app->bind(OpRepositoryInterface::class, OpRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(SignatoryRepositoryInterface::class, SignatoryRepository::class);
        $this->app->bind(SyncOpRepositoryInterface::class, SyncOpRepository::class);

        //services
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(AwardeeServiceInterface::class, AwardeeService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(LedgerServiceInterface::class, LedgerService::class);
        $this->app->bind(ChildrenServiceInterface::class, ChildrenService::class);
        $this->app->bind(ParameterServiceInterface::class, ParameterService::class);
        $this->app->bind(OpServiceInterface::class, OpService::class);
        $this->app->bind(ReportServiceInterface::class, ReportService::class);
        $this->app->bind(SyncOpServiceInterface::class, SyncOpService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
