<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\StallOwnerAccount;

class UpdateUnpaiLedgerFines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-unpai-ledger-fines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add interest (2%) and surcharge (25%) for unpaid stall owner accounts that are past due.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        $this->info('Running interest/surcharge update for unpaid stall owner accounts...');
        $this->info('Today: ' . $today->toDateString());

        $totalUpdated = 0;

        StallOwnerAccount::whereNull('ORNum')          // not paid
            ->whereNull('ORDate')                     // not paid
            ->whereDate('expdate', '<', $today)       // past expiration
            ->where(function ($q) {                    // isadded is empty or '0'
                $q->whereNull('isadded')
                ->orWhere('isadded', '')
                ->orWhere('isadded', '0');
            })                    // not yet processed
            ->chunkById(100, function ($accounts) use (&$totalUpdated) {
                foreach ($accounts as $account) {
                    $basic = (float) $account->amountBasic;

                    // If basic is 0 or null, don't bother
                    if ($basic <= 0) {
                        continue;
                    }

                    // 2% interest, 25% surcharge based on basic
                    $interest  = $basic * 0.02;
                    $surcharge = $basic * 0.25;

                    // Add on top of existing values (if any)
                    $account->amountInt  = (float) $account->amountInt + $interest;
                    $account->amountSurc = (float) $account->amountSurc + $surcharge;

                    // Mark as already added so we DON'T add again tomorrow
                    $account->isadded = 1;

                    $account->save();

                    $totalUpdated++;
                }
            });

        $this->info("Done. Updated {$totalUpdated} stall owner account record(s).");

        return Command::SUCCESS;
    }
}
