<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interface\Repository\SyncOpRepositoryInterface;

class SyncUnpaidOpsCommand extends Command
{
    protected $signature = 'sync:unpaid-ops';
    protected $description = 'Dispatch jobs to sync unpaid OPs from POPS.';

    public function handle(SyncOpRepositoryInterface $syncRepo): int
    {
        // Example: get ownerIds that have unpaid ops

        $unpaid = $syncRepo->findAllUnprocess();

        if ($unpaid->isNotEmpty()) {
            ProcessUnpaidOP::dispatch(
                $unpaid->pluck('id')->all())->onQueue('sync');
        }

        return self::SUCCESS;
    }
}
