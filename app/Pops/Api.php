<?php

namespace App\Pops;

use Illuminate\Support\Facades\Http;

class Api
{
    private $apiEndpoint;

    public function __construct()
    {
        if (app()->environment('local', 'staging')) {
            $this->apiEndpoint = config('services.pops_url_staging');
        } else {
            $this->apiEndpoint = config('services.pops_url');
        }
    }

    public function connect()
    {
        $response = Http::get("{$this->apiEndpoint}/api/connection");

        if ($response['white_ip']) {
            return 'Success.';
        }

        return 'Failed.';
    }

    public function checkPopsStatus()
    {
        try {
            Http::timeout(10)->get("{$this->apiEndpoint}/api/op/OBPS-24-012345");

            return 'Up';
        } catch (RequestException $e) {
            return 'Down';
        } catch (\Exception $e) {
            return 'Down';
        }
    }

    public function accountCodes($office_code)
    {
        $response = Http::get("{$this->apiEndpoint}/api/accountcodes/{$office_code}");

        return $response;
    }

    public function checkORNumber($or_number)
    {
        $response = Http::get("{$this->apiEndpoint}/api/receipts/{$or_number}");

        return $response;
    }

    public function createPayment(object $payload)
    {
        $items = $payload->items;
        $stallProfile = json_decode($payload->stallprofile);
        $itemsJson = urlencode(json_encode($items));

        $oprefid = $payload->OPRefId;
        $ownerid = $payload->ownerId;
        $name = $payload->name;
        $address = $stallProfile->stallDescription;
        $purpose = 'Market Fee';
        $postedby = $payload->postBy;
        $duedate = $payload->duedate;

        $url = "{$this->apiEndpoint}/api/saveop"
            ."?oprefid={$oprefid}"
            .'&opsysid=ESMS'
            ."&acctrefid={$ownerid}"
            .'&name='.urlencode($name)
            .'&address='.urlencode($address)
            .'&detail='.urlencode($purpose)
            .'&postedby='.urlencode($postedby)
            .'&duedate='.urlencode($duedate)
            ."&items={$itemsJson}";

        $response = Http::get($url);

        return $response->body();
    }
}
