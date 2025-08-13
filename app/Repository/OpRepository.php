<?php

namespace App\Repository;

use App\Pops\Api;
use Carbon\Carbon;
use App\Models\StallOP;
use App\Models\Parameter;
use Illuminate\Support\Str;
use App\Interface\Repository\OpRepositoryInterface;

class OpRepository implements OpRepositoryInterface
{
    private $popsApi;

    public function __construct()
    {
        $this->popsApi = new Api;
    }

    public function findMany(object $payload)
    {
        return StallOP::where('OPRefId', $payload->oprefid)->get();
    }

    public function findById(string $id)
    {
        // return Parameter::where('fieldId', $id);
    }

    public function create(object $payload)
    {
        // $user = new User();
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function update(object $payload, string $id)
    {
        // $user = User::findOrFail($id);
        // $user->username = $payload->username;
        // $user->email = $payload->email;
        // $user->password = Hash::make($payload->password);
        // $user->save();

        // return $user->fresh();
    }

    public function delete(string $id)
    {
        // $user = User::findOrFail($id);
        // $user->delete();

        // return response()->json([
        //     //resource/lang/exception.php
        //     // 'message' => trans('exception.sucess.message')
        //     'message' => "successfully deleted"
        // ], Response::HTTP_OK);
    }

    public function OPDueDate($is_pdf = false)
    {
        // Step 1: Set target due date on 20th of current or next month
        $dueDate = Carbon::now()->day(20);

        // Optional: if today is past 20, move to next month
        if (now()->gt($dueDate)) {
            $dueDate->addMonth();
        }

        // Step 2: Philippine holidays (sample static list — update as needed)
        $holidays = [
            '2025-06-20',
            '2025-08-21',
            '2025-12-25',
            // Add more holidays here in Y-m-d format
        ];

        // Step 3: Adjust if weekend or holiday
        while (
            $dueDate->isWeekend() ||
            in_array($dueDate->format('Y-m-d'), $holidays)
        ) {
            $dueDate->addDay();
        }
        $dueDate = ($is_pdf) ? $dueDate->format('F d, Y') : $dueDate->format('Y-m-d');

        return $dueDate;
    }

    //check if the OP is already exists
    public function checkOP($payload, $payload_items)
    {
        $op = StallOP::where('ownerId', $payload->ownerId)
            ->latest('stallOPId')
            ->select('OPRefId')
            ->first();

        $op->oprefid = $op->OPRefId;
        
        // $items = collect(json_decode($payload_items, true))->map(function ($item) {
        $items = collect($payload_items)->map(function ($item) {
            return [
                'purpose' => trim($item['label']),
            ];
        });

        $latest_items = collect($this->findMany($op))->map(function ($item) {
            return [
                'purpose' => trim($item['purpose']),
            ];
        });

        // Sort for consistent order comparison
        $sorted_items = $items->sort()->values()->all();
        $sorted_latest = $latest_items->sort()->values()->all();

        // Exact match comparison
        return $sorted_items === $sorted_latest;
    }

    public function saveOP(object $payload)
    {
        // $stallprofile = json_decode($payload->stallprofile);

        $op = new StallOP();
        $op->OPRefId = $payload->OPRefId;
        $op->opDate = Carbon::now();
        $op->ownerId = $payload->ownerId;
        $op->stallNo = $payload->stallNo;
        $op->opPeriodFrom = Carbon::now();
        $op->opPeriodTo = $payload->duedate;
        $op->accountCode = $payload->accoutcodes;
        $op->amountBasic = $payload->amount;
        $op->postDateTime = Carbon::now();
        $op->postBy = $payload->postBy;
        $op->signatoryid = $payload->signatoryid;
        $op->purpose = $payload->purpose;
        $op->opTN = 'M' . str_replace('-', '', $payload->OPRefId);
        $op->fk = 0;
        //null if no data pass on payload
        $op->ORNum = $payload->ORNum ?? null;
        $op->ORDate = $payload->ORDate ?? null;
        $op->save();

        return $op->fresh();
    }

    public function getAccountCode(string $officeCode, string $description, string $description1)
    {
        $apiResponse = $this->popsApi->accountCodes('esms');
        $response = json_decode($apiResponse, true);

        // Filter dynamically based on params
         $result = collect($response['data'])->first(function ($item) use ($officeCode, $description, $description1) {
            return Str::contains($item['accountcode'], $officeCode) &&
                Str::contains(Str::lower($item['description']), Str::lower($description)) &&
                Str::contains(Str::lower($item['description']), Str::lower($description1));
        });

        return $result;
    }

    public function accountCodes(string $officeCode, bool $has_extension, string $value, string $sectionCode)
    {
        $accountCodes = $this->popsApi->accountCodes('esms');
        $matchedCode = null;
        $response = json_decode($accountCodes);
        
        //$value = current or previous
        $value = ($value == 'current') ? 'current' : 'previous';
        //get section using sectioncode
        $sectionCode = substr($sectionCode, 2, 2); // start at position 4, get 4 characters
        $section = Parameter::where(['fieldId' => 'SECTIONCODE', 'fieldValue' => $sectionCode])->first();
        $map = [
            'COLD STORAGE' => 'ICE STORAGE',
            'VEGETABLE AND FRUIT' => 'FRUIT&VEG',
            'VARIETY OR GROCERIES' => 'VARIETY',
            'RICE, CORN AND OTHER CEREALS' => 'RICE & CORN',
            'FOOD COURT/EATERY' => 'EATERY',
            'LIVESTOCK' => 'LIVE CHICKEN',
        ];

        $sectionCodeDes = $map[$section->fieldDescription] ?? $section->fieldDescription;

        foreach ($response->data as $item) {
            $parts = explode('-', $item->accountcode);
            if (isset($parts[1], $parts[2])) {
                $office_id = $parts[1] . '-' . $parts[2];
                $prevOrCurrent = Str::contains(Str::lower($item->description), $value);
                $sectionCode = Str::contains(Str::lower($item->description), Str::lower($sectionCodeDes));
                $extension = Str::contains(Str::lower($item->accountcode), $office_id . '-39');
                $fines = Str::contains(Str::lower($item->accountcode), $office_id . '-49');

                //get the accountcodes and desc
                if ($office_id === $officeCode && $prevOrCurrent && $sectionCode) {
                    $matchedCode[] = $item;
                }
                //get extension details
                if ($office_id === $officeCode && $has_extension && $extension) {
                    $matchedCode[] = $item;
                }
                //get fines details
                if ($office_id === $officeCode && $value === 'previous' && $fines) {
                    $matchedCode[] = $item;
                }
            }
        }

        return $matchedCode;
    }

    public function accountCodes1(string $officeCode, bool $has_extension, string $value, string $sectionCode)
    {
        $accountCodes = $this->popsApi->accountCodes('esms');
        $matchedCode = null;
        $response = json_decode($accountCodes);

        //$value = current or previous
        $value = ($value == 'current') ? 'current' : 'previous';
        //get section using sectioncode
        $sectionCode = substr($sectionCode, 2, 2); // start at position 4, get 4 characters
        $section = Parameter::where(['fieldId' => 'SECTIONCODE', 'fieldValue' => $sectionCode])->first();
        $map = [
            'COLD STORAGE' => 'ICE STORAGE',
            'VEGETABLE AND FRUIT' => 'FRUIT&VEG',
            'VARIETY OR GROCERIES' => 'VARIETY',
            'RICE, CORN AND OTHER CEREALS' => 'RICE & CORN',
            'FOOD COURT/EATERY' => 'EATERY',
            'LIVESTOCK' => 'LIVE CHICKEN',
        ];

        $sectionCodeDes = $map[$section->fieldDescription] ?? $section->fieldDescription;

        foreach ($response->data as $item) {
            $parts = explode('-', $item->accountcode);
            if (isset($parts[1], $parts[2])) {
                $office_id = $parts[1] . '-' . $parts[2];
                $prevOrCurrent = Str::contains(Str::lower($item->description), $value);
                $sectionCode = Str::contains(Str::lower($item->description), Str::lower($sectionCodeDes));
                $extension = Str::contains(Str::lower($item->accountcode), $office_id . '-39');
                $fines = Str::contains(Str::lower($item->accountcode), $office_id . '-49');

                //get the accountcodes and desc
                if ($office_id === $officeCode && $prevOrCurrent && $sectionCode) {
                    $matchedCode[] = $item;
                }
                //get extension details
                if ($office_id === $officeCode && $has_extension && $extension) {
                    $matchedCode[] = $item;
                }
                //get fines details
                if ($office_id === $officeCode && $value === 'previous' && $fines) {
                    $matchedCode[] = $item;
                }
            }
        }

        return $matchedCode;
    }
}
