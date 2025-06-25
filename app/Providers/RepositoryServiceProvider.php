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
use App\Interface\Service\ChildrenServiceInterface;
use App\Interface\Service\LedgerServiceInterface;
use App\Repository\ChildrenRepository;
use App\Repository\LedgerRepository;
use App\Service\ChildrenService;
use App\Service\LedgerService;

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

        //services
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(AwardeeServiceInterface::class, AwardeeService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(LedgerServiceInterface::class, LedgerService::class);
        $this->app->bind(ChildrenServiceInterface::class, ChildrenService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
