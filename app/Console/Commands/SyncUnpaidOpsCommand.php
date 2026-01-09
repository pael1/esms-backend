<?php

namespace App\Console\Commands;

use App\Jobs\ProcessUnpaidOP;
use Illuminate\Console\Command;
use App\Interface\Repository\SyncOpRepositoryInterface;

class SyncUnpaidOpsCommand extends Command
{
    protected $signature = 'sync:unpaid-ops';
    protected $description = 'Dispatch jobs to sync unpaid OPs from POPS.';

    public function handle(SyncOpRepositoryInterface $syncRepo): int
    {
        $unProcessed = $syncRepo->findAllUnprocess();
        if ($unProcessed->isNotEmpty()) {
            ProcessUnpaidOP::dispatch($unProcessed);
        }
        return self::SUCCESS;
    }
}
