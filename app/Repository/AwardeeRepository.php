<?php

namespace App\Repository;

use App\Interface\Repository\AwardeeRepositoryInterface;
use App\Models\ExpirationDate;
use App\Models\Parameter;
use App\Models\StallOP;
use App\Models\Stallowner;
use App\Models\StallOwnerAccount;
use App\Models\StallOwnerChild;
use App\Models\StallOwnerEmp;
use App\Models\StallOwnerFiles;
use App\Models\StallProfileViews;
use App\Models\User;
use App\Models\UserAccount;
use App\Pops\Api;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AwardeeRepository implements AwardeeRepositoryInterface
{
    // private $popsApi;

    // public function __construct()
    // {
    //     $this->popsApi = new Api;
    // }

    public function findMany(object $payload)
    {
        $query = DB::table('stallowner as a')
            ->leftJoin('stallrentaldet as b', 'a.stallownerid', '=', 'b.STALLOWNER_stallOwnerId')
            ->leftJoin('stallprofile as c', 'b.stallno', '=', 'c.stallno')
            ->where('a.ownerStatus', 'ACTIVE')
            ->where('c.marketcode', $payload->marketcode)
            ->where('c.stallType', $payload->type)
            ->whereRaw('SUBSTRING(c.sectionCode, 3, 2) = ?', [$payload->section])
            ->where('c.stallNoId', '!=', '')
            ->whereNotNull('c.stallNoId')
            ->when($payload->name, function ($q) use ($payload) {
                $fullname = trim(preg_replace('/\s+/', ' ', $payload->name)); // normalize spaces
                $q->whereRaw("CONCAT(a.firstname, ' ', a.lastname) LIKE ?", ["%{$fullname}%"]);
            })
            ->orderBy('c.stallNoId', 'asc');

        return $query->paginate(10);
    }

    public function find_many_childrens(object $payload)
    {
        return StallOwnerChild::where('ownerId', $payload->ownerId)->paginate(10);
    }

    public function find_many_transactions(object $payload)
    {
        return StallOP::where('ownerId', $payload->ownerId)
            ->orderBy('postDateTime', 'desc')
            ->groupBy('OPRefId')
            ->paginate(10);
    }

    public function find_many_files(object $payload)
    {
        return StallOwnerFiles::where('ownerId', $payload->ownerId)->paginate(10);
    }

    public function find_many_employees_data(object $payload)
    {
        return StallOwnerEmp::where('ownerId', $payload->ownerId)->paginate(10);
    }

    public function findById(string $ownerID)
    {
        return Stallowner::with([
            'stallRentalDet',
            'stallRentalDet.stallProfile',
            'stallRentalDet.stallProfileViews',
            'stallRentalDet.stallProfile.signatory',
            'stallRentalDet.stallProfile.officecode',

        ])
            ->where('ownerId', $ownerID)
            ->first();
    }

    public function findByOwnerId(string $ownerID)
    {
        return Stallowner::with([
            'childrens',
            'ledger',
        ])->where('ownerId', $ownerID)->first();
    }

    // public function findByUsername(string $username)
    // {
    //     return UserAccount::with([
    //         // return User::with([
    //         'user_detail',
    //         // ])->where('username', $username)->first();
    //     ])->where('userid', $username)->first();
    // }

    // public function create(object $payload)
    // {
    //     $user = new User();
    //     $user->username = $payload->username;
    //     $user->email = $payload->email;
    //     $user->password = Hash::make($payload->password);
    //     $user->save();

    //     return $user->fresh();
    // }

    // public function update(object $payload, string $id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->username = $payload->username;
    //     $user->email = $payload->email;
    //     $user->password = Hash::make($payload->password);
    //     $user->save();

    //     return $user->fresh();
    // }

    // public function delete(string $id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();

    //     return response()->json([
    //         //resource/lang/exception.php
    //         // 'message' => trans('exception.sucess.message')
    //         'message' => 'successfully deleted',
    //     ], Response::HTTP_OK);
    // }

    // public function accountCodes(string $officeCode, bool $has_extension, string $value, string $sectionCode)
    // {
    //     $accountCodes = $this->popsApi->accountCodes("esms");
    //     $matchedCode = null;
    //     $response = json_decode($accountCodes);

    //     //$value = current or previous
    //     $value = ($value == 'current') ? 'current' : 'previous';
    //     //get section using sectioncode
    //     $sectionCode = substr($sectionCode, 2, 2); // start at position 4, get 4 characters
    //     $section = Parameter::where(['fieldId' => "SECTIONCODE", 'fieldValue' => $sectionCode])->first();
    //     $map = [
    //         'COLD STORAGE' => 'ICE STORAGE',
    //         'VEGETABLE AND FRUIT' => 'FRUIT&VEG',
    //         'VARIETY OR GROCERIES' => 'VARIETY',
    //         'RICE, CORN AND OTHER CEREALS' => 'RICE & CORN',
    //         'FOOD COURT/EATERY' => 'EATERY',
    //         'LIVESTOCK' => 'LIVE CHICKEN',
    //     ];

    //     $sectionCodeDes = $map[$section->fieldDescription] ?? $section->fieldDescription;

    //     foreach ($response->data as $item) {
    //         $parts = explode('-', $item->accountcode);
    //         if (isset($parts[1], $parts[2])) {
    //             $office_id = $parts[1] . '-' . $parts[2];
    //             $prevOrCurrent = Str::contains(Str::lower($item->description), $value);
    //             $sectionCode = Str::contains(Str::lower($item->description), Str::lower($sectionCodeDes));
    //             $extension = Str::contains(Str::lower($item->accountcode), $office_id . '-39');

    //             //get the accountcodes and desc
    //             if ($office_id === $officeCode && $prevOrCurrent && $sectionCode) {
    //                 $matchedCode[] = $item;
    //             }
    //             //get extension details
    //             if ($office_id === $officeCode && $has_extension && $extension) {
    //                 $matchedCode[] = $item;
    //             }
    //         }
    //     }
    //     return $matchedCode;
    // }

    // public function current_billing(object $payload)
    // {
    //     $stall_profile = StallProfileViews::where('stallNo', $payload->stallNo)->first();
    //     $expirationDate = ExpirationDate::where('marketcode', $payload->marketCode)->orderBy('idexpirationdate', 'desc')->first();

    //     $latestExpDay = Carbon::parse($expirationDate->date)->format('d');

    //     $dateNow = Carbon::now()->format('d');
    //     $days = 0;
    //     if ($dateNow <= $latestExpDay) {
    //         $days = Carbon::now()->addMonth()->daysInMonth;
    //     } else {
    //         $days = Carbon::now()->daysInMonth;
    //     }
    //     $stall_profile->total_amount = $stall_profile->RatePerDay * $days;
    //     $stall_profile->extension = $stall_profile->Total_extensionRate * $days;

    //     return $stall_profile;
    // }

    // public function saveOP(object $payload)
    // {
    //     $stallprofile = json_decode($payload->stallprofile);

    //     $op = new StallOP();
    //     $op->OPRefId = $payload->OPRefId;
    //     $op->opDate = Carbon::now();
    //     $op->ownerId = $payload->ownerId;
    //     $op->stallNo = $payload->stallNo;
    //     $op->opPeriodFrom = Carbon::now();
    //     $op->opPeriodTo = $payload->duedate;
    //     $op->accountCode = $payload->accoutcodes;
    //     $op->amountBasic = $payload->amount;
    //     $op->postDateTime = Carbon::now();
    //     $op->postBy = $payload->postBy;
    //     $op->signatoryid = $stallprofile->signatory->signatoryId;
    //     $op->purpose = $payload->purpose;
    //     $op->opTN = 'M' . str_replace('-', '', $payload->OPRefId);
    //     $op->fk = 0;
    //     $op->save();

    //     return $op->fresh();
    // }

    // public function OPDueDate($is_pdf = false)
    // {
    //     // Step 1: Set target due date on 20th of current or next month
    //     $dueDate = Carbon::now()->day(20);

    //     // Optional: if today is past 20, move to next month
    //     if (now()->gt($dueDate)) {
    //         $dueDate->addMonth();
    //     }

    //     // Step 2: Philippine holidays (sample static list — update as needed)
    //     $holidays = [
    //         '2025-06-20',
    //         '2025-08-21',
    //         '2025-12-25',
    //         // Add more holidays here in Y-m-d format
    //     ];

    //     // Step 3: Adjust if weekend or holiday
    //     while (
    //         $dueDate->isWeekend() ||
    //         in_array($dueDate->format('Y-m-d'), $holidays)
    //     ) {
    //         $dueDate->addDay();
    //     }
    //     $dueDate = ($is_pdf) ? $dueDate->format('F d, Y') : $dueDate->format('Y-m-d');
    //     return $dueDate;
    // }
}
