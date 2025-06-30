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
        // Logger($payload->OPRefId);
        // Logger($payload->ownerId);
        // Logger($payload->name);
        // $stallProfile = json_decode($payload->stallprofile);
        // Logger($stallProfile->stallDescription);
        // Logger($payload->purpose);
        // Logger($payload->postBy);
        // Logger($payload->duedate);
        // Logger($payload->items);
        // dd();
        $items = $payload->items;
        $stallProfile = json_decode($payload->stallprofile);

        Logger($items);

        $itemsJson = urlencode(json_encode($items));

        Logger($itemsJson);
        // dd();

        $oprefid = $payload->OPRefId;
        $ownerid = $payload->ownerId;
        $name = $payload->name;
        $address = $stallProfile->stallDescription;
        // $purpose = $payload->purpose;
        $purpose = 'Market Fee';
        $postedby = $payload->postBy;
        $duedate = $payload->duedate;

        $url = "{$this->apiEndpoint}/saveop"
            . "?oprefid={$oprefid}"
            . '&opsysid=ESMS'
            . "&acctrefid={$ownerid}"
            . '&name=' . urlencode($name)
            . '&address=' . urlencode($address)
            . '&detail=' . urlencode($purpose)
            . '&postedby=' . urlencode($postedby)
            . '&duedate=' . urlencode($duedate)
            . "&items={$itemsJson}";

        Logger($url);
        // dd();
        $response = Http::get($url);

        return $response->body();
    }
}
