<?php

namespace App\Jobs;

use App\Interface\Repository\LedgerRepositoryInterface;
use App\Pops\Api;
use App\Models\Awardee;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Repository\SyncOpRepositoryInterface;

class ProcessUnpaidOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $awardee;
    private $popsApi;
    private $opRepository;
    private $ledgerRepository;
    private $syncRepository;

    /**
     * Create a new job instance.
     */
    public function __construct($data, $awardee)
    {
        $this->data = $data;
        $this->awardee = $awardee;
        $this->popsApi = new Api;
    }

    /**
     * Execute the job.
     */
    public function handle(OpRepositoryInterface $opRepository, SyncOpRepositoryInterface $syncRepository, LedgerRepositoryInterface $ledgerRepository): void
    {
        $this->opRepository = $opRepository;
        $this->ledgerRepository = $ledgerRepository;
        $this->syncRepository = $syncRepository;
        
        foreach ($this->data as $item) {
            logger($item);
            $response = $this->popsApi->checkORNumber($item->ornumber);
            $opDetails = $response->json();
            if (isset($opDetails['message']) && $opDetails['message'] === 'Success') {
                $oprefId = $opDetails['pops']['oprefid'];
                $ornum = $opDetails['pops']['ornum'];
                $ordate = $opDetails['pops']['ordate'];
                $data = json_decode($this->awardee);
                foreach ($opDetails['pops']['items'] as $op) {
                    $payload = (object) [
                        'OPRefId' => $oprefId,
                        'ownerId' => $data->ownerId,
                        'signatoryid' => $data->stall_rental_det->stall_profile->signatory->signatoryId,
                        'stallNo' => $data->stall_rental_det->stallNo,
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
                    $this->opRepository->saveOP($payload);
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
                $this->syncRepository->updateById($item->id);
            }
        }
    }

    public function failed()
    {
        logger("Job failed for data: " . $this->data);
    }
}
