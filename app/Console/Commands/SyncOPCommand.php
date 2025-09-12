<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncOPCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'op:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from POPS system to eSMS system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Dispatch your job
        // GenerateDailyReport::dispatch();

        $this->info('Data job dispatched!');
    }
}
