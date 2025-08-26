<?php

namespace App\Providers;

use App\Service\OpService;
use App\Service\AuthService;
use App\Service\UserService;
use App\Service\StallService;
use App\Service\LedgerService;
use App\Service\ReportService;
use App\Service\SyncOpService;
use RecursiveIteratorIterator;
use App\Service\AwardeeService;
use RecursiveDirectoryIterator;
use App\Repository\OpRepository;
use App\Service\ChildrenService;
use App\Service\ParameterService;
use App\Repository\UserRepository;
use App\Repository\StallRepository;
use Illuminate\Support\Facades\Log;
use App\Repository\LedgerRepository;
use App\Repository\ReportRepository;
use App\Repository\SyncOpRepository;
use App\Repository\AwardeeRepository;
use App\Repository\ChildrenRepository;
use App\Repository\ParameterRepository;
use App\Repository\SignatoryRepository;
use Illuminate\Support\ServiceProvider;
use App\Interface\Service\OpServiceInterface;
use App\Interface\Service\AuthServiceInterface;
use App\Interface\Service\UserServiceInterface;
use App\Interface\Service\StallServiceInterface;
use App\Interface\Service\LedgerServiceInterface;
use App\Interface\Service\ReportServiceInterface;
use App\Interface\Service\SyncOpServiceInterface;
use App\Interface\Service\AwardeeServiceInterface;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Service\ChildrenServiceInterface;
use App\Interface\Service\ParameterServiceInterface;
use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Repository\StallRepositoryInterface;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\ReportRepositoryInterface;
use App\Interface\Repository\SyncOpRepositoryInterface;
use App\Interface\Repository\AwardeeRepositoryInterface;
use App\Interface\Repository\ChildrenRepositoryInterface;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Repository\SignatoryRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    // public function register(): void
    // {
    //     //repository
    //     $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    //     $this->app->bind(AwardeeRepositoryInterface::class, AwardeeRepository::class);
    //     $this->app->bind(LedgerRepositoryInterface::class, LedgerRepository::class);
    //     $this->app->bind(ChildrenRepositoryInterface::class, ChildrenRepository::class);
    //     $this->app->bind(ParameterRepositoryInterface::class, ParameterRepository::class);
    //     $this->app->bind(OpRepositoryInterface::class, OpRepository::class);
    //     $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
    //     $this->app->bind(SignatoryRepositoryInterface::class, SignatoryRepository::class);
    //     $this->app->bind(SyncOpRepositoryInterface::class, SyncOpRepository::class);
    //     $this->app->bind(StallRepositoryInterface::class, StallRepository::class);

    //     //services
    //     $this->app->bind(UserServiceInterface::class, UserService::class);
    //     $this->app->bind(AwardeeServiceInterface::class, AwardeeService::class);
    //     $this->app->bind(AuthServiceInterface::class, AuthService::class);
    //     $this->app->bind(LedgerServiceInterface::class, LedgerService::class);
    //     $this->app->bind(ChildrenServiceInterface::class, ChildrenService::class);
    //     $this->app->bind(ParameterServiceInterface::class, ParameterService::class);
    //     $this->app->bind(OpServiceInterface::class, OpService::class);
    //     $this->app->bind(ReportServiceInterface::class, ReportService::class);
    //     $this->app->bind(SyncOpServiceInterface::class, SyncOpService::class);
    //     $this->app->bind(StallServiceInterface::class, StallService::class);
    // }

    public function register(): void
    {
        // Auto-bind repositories
        $this->bindByNamespace(
            "App\\Interface\\Repository",
            "App\\Repository"
        );

        // Auto-bind services
        $this->bindByNamespace(
            "App\\Interface\\Service",
            "App\\Service"
        );
    }

    protected function bindByNamespace(string $interfaceNamespace, string $implementationNamespace): void
    {
        // Remove the "App\" root part because app_path() already points to /app
        $relative = str_replace('App\\', '', $implementationNamespace);
        $path = app_path(str_replace('\\', '/', $relative));

        foreach ($this->getPhpFiles($path) as $file) {
            $class = pathinfo($file, PATHINFO_FILENAME);

            $interface = $interfaceNamespace . "\\" . $class . "Interface";
            $implementation = $implementationNamespace . "\\" . $class;

            if (interface_exists($interface) && class_exists($implementation)) {
                $this->app->bind($interface, $implementation);
            } else {
                if (!interface_exists($interface)) {
                    Log::warning("⚠️ Missing interface: {$interface}");
                }
                if (!class_exists($implementation)) {
                    Log::warning("⚠️ Missing implementation: {$implementation}");
                }
            }
        }
    }

    private function getPhpFiles(string $path): array
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $files = [];

        foreach ($rii as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
