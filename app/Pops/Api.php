<?php

namespace App\Pops;

use Illuminate\Support\Facades\Http;

class Api
{
    private $apiEndpoint;

    public function __construct()
    {
        $this->apiEndpoint = config('services.pops_url');
    }

    public function connect()
    {
        $response = Http::get("{$this->apiEndpoint}/connection");

        if ($response['white_ip']) {
            return 'Success.';
        }

        return 'Failed.';
    }

    public function checkPopsStatus()
    {
        try {
            Http::timeout(10)->get("{$this->apiEndpoint}/op/OBPS-24-012345");

            return 'Up';
        } catch (RequestException $e) {
            return 'Down';
        } catch (\Exception $e) {
            return 'Down';
        }
    }

    public function accountCodes($office_code)
    {
        $response = Http::get("{$this->apiEndpoint}/accountcodes/{$office_code}");

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

        $url = "{$this->apiEndpoint}/saveop"
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
