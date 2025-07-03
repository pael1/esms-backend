<?php

namespace App\Providers;

use App\Service\AuthService;
use App\Service\UserService;
use App\Service\AwardeeService;
use App\Repository\UserRepository;
use App\Repository\AwardeeRepository;
use Illuminate\Support\ServiceProvider;
use App\Interface\Service\AuthServiceInterface;
use App\Interface\Service\UserServiceInterface;
use App\Interface\Service\AwardeeServiceInterface;
use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Repository\AwardeeRepositoryInterface;
use App\Interface\Repository\ChildrenRepositoryInterface;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Service\ChildrenServiceInterface;
use App\Interface\Service\LedgerServiceInterface;
use App\Interface\Service\OpServiceInterface;
use App\Interface\Service\ParameterServiceInterface;
use App\Repository\ChildrenRepository;
use App\Repository\LedgerRepository;
use App\Repository\OpRepository;
use App\Repository\ParameterRepository;
use App\Service\ChildrenService;
use App\Service\LedgerService;
use App\Service\OpService;
use App\Service\ParameterService;

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

        //services
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(AwardeeServiceInterface::class, AwardeeService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(LedgerServiceInterface::class, LedgerService::class);
        $this->app->bind(ChildrenServiceInterface::class, ChildrenService::class);
        $this->app->bind(ParameterServiceInterface::class, ParameterService::class);
        $this->app->bind(OpServiceInterface::class, OpService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
