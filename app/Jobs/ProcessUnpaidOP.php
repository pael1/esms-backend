<?php

namespace App\Jobs;

use App\Pops\Api;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\SyncOpRepositoryInterface;
use App\Interface\Repository\AwardeeRepositoryInterface;

class ProcessUnpaidOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $unprocessedSync;
    private $popsApi;
    private $opRepository;
    private $ledgerRepository;
    private $syncRepository;
    private $awardeeRepository;

    /**
     * Create a new job instance.
     */
    public function __construct($unprocessedSync)
    {
        $this->unprocessedSync = $unprocessedSync;
        $this->popsApi = new Api;
    }

    /**
     * Execute the job.
     */
    public function handle(OpRepositoryInterface $opRepository, SyncOpRepositoryInterface $syncRepository, LedgerRepositoryInterface $ledgerRepository, AwardeeRepositoryInterface $awardeeRepository): void
    {
        $this->opRepository = $opRepository;
        $this->ledgerRepository = $ledgerRepository;
        $this->syncRepository = $syncRepository;
        $this->awardeeRepository = $awardeeRepository;
        
        foreach ($this->unprocessedSync as $item) {
            $response = $this->popsApi->checkORNumber($item->ornumber);
            if (isset($response['message']) && $response['message'] === 'Success') {
                $oprefId = $response['pops']['oprefid'];
                $ornum = $response['pops']['ornum'];
                $ordate = $response['pops']['ordate'];

                //fetch awardee details
                $awardee = $this->awardeeRepository->findById($item->ownerid);
                $awardeeDetails = json_decode($awardee);

                foreach ($response['pops']['items'] as $op) {
                    $payload = (object) [
                        'OPRefId' => $oprefId,
                        'ownerId' => $awardeeDetails->ownerId,
                        'signatoryid' => $awardeeDetails->stall_rental_det->stall_profile->signatory->signatoryId,
                        'stallNo' => $awardeeDetails->stall_rental_det->stallNo,
                        'duedate' => $ordate,
                        'ORNum' => $ornum,
                        'ORDate' => $ordate,
                        'accoutcodes' => $op['accountcode'],
                        'amount' => $op['amount'],
                        'postBy' => 'Sync',
                        'fk' => 01,
                        'purpose' => 'Sync'
                    ];

                    //sync to ledger
                    $this->opRepository->saveOP($payload, '');
                }

                //update ledger
                $ledgerPayload = [
                    'oprefId' => $oprefId,
                    'ornum'   => $ornum,
                    'ordate'  => $ordate,
                    'months'  => $item->months,
                ];
                $this->ledgerRepository->updateLedgerSync($ledgerPayload);

                //update sync status
                $this->syncRepository->updateById($item->id, '1');
            } else {
                //update sync status to Not Found on POPS
                $this->syncRepository->updateById($item->id, '2');
            }
        }
    }

    public function failed()
    {
        logger("Job failed for data: " . $this->unprocessedSync);
    }
}
