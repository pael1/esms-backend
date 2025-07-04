<?php

namespace App\Service;

use App\Http\Resources\AwardeeDetailsResource;
use App\Http\Resources\AwardeeListResource;
use App\Http\Resources\StallOPResource;
use App\Http\Resources\StallOwnerAccountResource;
use App\Http\Resources\StallOwnerChildResource;
use App\Http\Resources\StallOwnerEmpResource;
use App\Http\Resources\StallOwnerFilesResource;
use App\Http\Resources\UserResource;
use App\Interface\Repository\AwardeeRepositoryInterface;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Service\AwardeeServiceInterface;
use App\Models\StallOP;
use App\Pops\Api;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AwardeeService implements AwardeeServiceInterface
{
    private $awardeeRepository;

    private $popsApi;

    private $opRepository;

    public function __construct(AwardeeRepositoryInterface $awardeeRepository, Api $popsApi, OpRepositoryInterface $opRepository)
    {
        $this->awardeeRepository = $awardeeRepository;
        $this->popsApi = $popsApi;
        $this->opRepository = $opRepository;
    }

    public function findManyAwardee(object $payload)
    {
        $awardees = $this->awardeeRepository->findMany($payload);

        return AwardeeListResource::collection($awardees);
    }

    // public function findManyLedger(object $payload)
    // {
    //     $ledger = $this->awardeeRepository->findManyLedger($payload);
    //     return StallOwnerAccountResource::collection($ledger);
    // }

    public function find_many_childrens(object $payload)
    {
        $childrens = $this->awardeeRepository->find_many_childrens($payload);

        return StallOwnerChildResource::collection($childrens);
    }

    public function find_many_transactions(object $payload)
    {
        $transactions = $this->awardeeRepository->find_many_transactions($payload);

        return StallOPResource::collection($transactions);
    }

    public function find_many_files(object $payload)
    {
        $files = $this->awardeeRepository->find_many_files($payload);

        return StallOwnerFilesResource::collection($files);
    }

    public function find_many_employees_data(object $payload)
    {
        $employees_data = $this->awardeeRepository->find_many_employees_data($payload);

        return StallOwnerEmpResource::collection($employees_data);
    }

    public function findAwardeeById(string $ownerID)
    {
        $year = 2024;
        $month = 2; // February
        $days_in_month = Carbon::create($year, $month)->daysInMonth;

        $awardee = $this->awardeeRepository->findById($ownerID);

        $awardee->full_name = "{$awardee->firstname} " .
            ($awardee->midinit ? "{$awardee->midinit}. " : '') .
            "{$awardee->lastname}";
        $awardee->spouse_full_name = "{$awardee->spouseFirstname} " .
            ($awardee->spouseMidint ? "{$awardee->spouseMidint}. " : '') .
            "{$awardee->spouseLastname}";
        $awardee->rate_per_month = $awardee->stallRentalDet->stallProfile->ratePerDay * $days_in_month;

        return new AwardeeDetailsResource($awardee);
    }

    // public function findUserByEmail(string $email)
    // {

    //     // $user = $this->awardeeRepository->findByEmail($email);

    //     // // return UserResource::collection($user); // for multiple data
    //     // return new UserResource($user); //for only 1 data
    // }

    // public function createUser(object $payload)
    // {
    //     // $user = $this->awardeeRepository->create($payload);

    //     // return new UserResource($user);
    // }

    // public function updateUser(object $payload, string $id)
    // {
    //     // $user = $this->awardeeRepository->update($payload, $id);

    //     // return new UserResource($user);
    // }

    // public function deleteUser(string $id)
    // {
    //     // return $this->awardeeRepository->delete($id);
    // }

    public function current_billing(object $payload)
    {
        $is_op_exists = $this->opRepository->checkOP($payload);
        // items already generated
        if ($is_op_exists) {
            return response()->json([
                'message' => 'This Item/s are already generated',
            ], Response::HTTP_BAD_REQUEST);
        }

        $connection = $this->popsApi->connect();
        $serverStatus = $this->popsApi->checkPopsStatus();

        if ($connection === 'Success.' && $serverStatus === 'Up') {

            $has_extension = ($payload->extension == '0.00') ? false : true;

            $stallprofile = json_decode($payload->stallprofile);

            $officeCode = substr($stallprofile->officecode->officeCode, 0, 2) . '-' . substr($stallprofile->officecode->officeCode, 2);

            $letter = chr(rand(65, 90)); // Random uppercase letter A–Z
            $digits = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT); // 2-digit number (00–99)
            $randomCode = $letter . $digits;

            //generate OPRefID | Format officecode - year - sequence number 00000
            $prefix = str_replace('-', '', $officeCode) . '-' . date('y') . $randomCode . '-'; // e.g. "2072-25-"
            $last = StallOP::orderByDesc('stallOPId')->first();
            if ($last && isset($last->OPRefId)) {
                $lastNumber = (int) substr($last->OPRefId, -5);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            $OPRefId = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

            $items = json_decode($payload->items, true); // `true` makes it an array
            $totalAmount = 0;

            //account codes to be paid
            $itemsPaid = [];
            $payload->duedate = $this->opRepository->OPDueDate();
            foreach ($items as $item) {
                // $accountCodes = $this->awardeeRepository->accountCodes($officeCode, $has_extension, $item['value'], $stallprofile->sectionCode);
                $accountCodes = $this->opRepository->accountCodes($officeCode, $has_extension, $item['value'], $stallprofile->sectionCode);
                foreach ($accountCodes as &$account) {
                    $extension = Str::contains(Str::lower($account->accountcode), $officeCode . '-39');
                    if ($extension) {
                        $account->amount = $item['extensionRate'] ?? 0;
                        $accountCodeDesc = $account->description;
                    } else {
                        $account->amount = $item['amountBasic'] ?? 0;
                        $accountCodeDesc = $account->description . " ({$item['label']})";
                    }
                    $totalAmount += $account->amount;

                    //save op
                    $payload->accoutcodes = $account->accountcode;
                    $payload->amount = $account->amount;
                    $payload->OPRefId = $OPRefId;
                    $payload->purpose = $item['label'];
                    // $this->awardeeRepository->saveOP($payload);
                    $this->opRepository->saveOP($payload);

                    $itemsPaid[] = [
                        'accountcode' => $account->accountcode,
                        'description' => $accountCodeDesc,
                        'amount' => $account->amount,
                    ];
                }
            }

            //save to pops
            $payload->items = $itemsPaid;
            if (!app()->environment('local')) {
                $this->popsApi->createPayment($payload);
            }

            // $stall_profile->account_codes = $accountCodes;
            $pdf = Pdf::loadView('pdf.top', [
                'owner_name' => $payload->name,
                'owner_address' => $stallprofile->stallDescription,
                'account_code_details' => $itemsPaid,
                'total_amount' => $totalAmount,
                'op_number' => $OPRefId,
                'owner_id' => $payload->ownerId,
                'op_date' => Carbon::now()->format('F j, Y'),
                'op_sys' => 'ESMS',
                'post_by' => $payload->postBy,
                'remarks' => '',
                'valid_until_date' => $this->opRepository->OPDueDate(true),
            ]);

            // Define filename and path
            $filename = "public/ops/{$payload->ownerId}/{$OPRefId}.pdf";

            // Save PDF content to storage/app/pdfs/OP_12345.pdf
            Storage::put($filename, $pdf->output());

            return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
        }

        return response()->json([
            'message' => 'POPS server down.',
        ], Response::HTTP_BAD_REQUEST);
    }
}
