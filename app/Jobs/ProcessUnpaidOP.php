<?php

namespace App\Jobs;

use App\Pops\Api;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessUnpaidOP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    private $popsApi;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->popsApi = new Api;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data as $item) {
            $response = $this->popsApi->checkORNumber($item->ornumber);
            $opDetails = $response->json();
            if (isset($opDetails['message']) && $opDetails['message'] === 'Success') {
                logger($opDetails);
            }
        }
    }

    public function failed()
    {
        logger("Job failed for data: " . $this->data);
    }
}
