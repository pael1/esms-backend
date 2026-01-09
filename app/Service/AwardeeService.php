<?php

namespace App\Service;

use App\Pops\Api;
use Carbon\Carbon;
use App\Models\StallOP;
use App\Models\Parameter;
use Illuminate\Support\Str;
use PhpParser\Builder\Param;
use App\Jobs\ProcessUnpaidOP;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\UserResource;
use App\Http\Resources\StallOPResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\AwardeeListResource;
use App\Http\Resources\StallOwnerEmpResource;
use App\Http\Resources\AwardeeDetailsResource;
use App\Http\Resources\StallOwnerChildResource;
use App\Http\Resources\StallOwnerFilesResource;
use App\Http\Resources\StallOwnerAccountResource;
use App\Http\Resources\StallOwnerResource;
use App\Interface\Service\AwardeeServiceInterface;
use App\Interface\Repository\OpRepositoryInterface;
use App\Interface\Repository\LedgerRepositoryInterface;
use App\Interface\Repository\SyncOpRepositoryInterface;
use App\Interface\Repository\AwardeeRepositoryInterface;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Repository\SignatoryRepositoryInterface;

class AwardeeService implements AwardeeServiceInterface
{
    private $awardeeRepository;
    private $popsApi;
    private $opRepository;
    private $signatoryRepository;
    private $syncOpRepository;
    private $LedgerRepository;
    private $parameterRepository;

    public function __construct(AwardeeRepositoryInterface $awardeeRepository, Api $popsApi, OpRepositoryInterface $opRepository, SignatoryRepositoryInterface $signatoryRepository, SyncOpRepositoryInterface $syncOpRepository, LedgerRepositoryInterface $LedgerRepository, ParameterRepositoryInterface $parameterRepository)
    {
        $this->awardeeRepository = $awardeeRepository;
        $this->popsApi = $popsApi;
        $this->opRepository = $opRepository;
        $this->signatoryRepository = $signatoryRepository;
        $this->syncOpRepository = $syncOpRepository;
        $this->LedgerRepository = $LedgerRepository;
        $this->parameterRepository = $parameterRepository;
    }

    public function findManyAwardee(object $payload)
    {
        $awardees = $this->awardeeRepository->findMany($payload);

        return AwardeeListResource::collection($awardees);
    }

    public function awardeeList(object $payload)
    {
        $awardees = $this->awardeeRepository->awardeeList($payload);

        return StallOwnerResource::collection($awardees);
    }

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

        $awardee->full_name = trim(
            ($awardee->firstname ?? '') . ' ' .
            ($awardee->midinit ? "{$awardee->midinit}. " : '') .
            ($awardee->lastname ?? '')
        );

        $awardee->spouse_full_name = trim(
            ($awardee->spouseFirstname ?? '') . ' ' .
            ($awardee->spouseMidint ? "{$awardee->spouseMidint}. " : '') .
            ($awardee->spouseLastname ?? '')
        );
        
        // $awardee->rate_per_month = $awardee->stallRentalDet->stallProfile->ratePerDay * $days_in_month;
        $ratePerDay = $awardee->stallRentalDet?->stallProfile?->ratePerDay;

        $awardee->rate_per_month = $ratePerDay ? $ratePerDay * $days_in_month : 0;

        //testing for roles and permissions using spatie
        // $user = UserAccount::where('SystemUser_EmpId', 12345)->first();
        // $permissions = $user->getAllPermissions()->pluck('name');
        // logger($permissions);

        return new AwardeeDetailsResource($awardee);
    }

    public function create(array $payload)
    {
        $stallOwner = $this->awardeeRepository->create($payload);

        return $stallOwner;
    }

    // public function update(string $id, array $payload)
    // {
    //     return $this->awardeeRepository->update($id, $payload);
    // }

    public function current_billing(object $payload)
    {
        $connection = $this->popsApi->connect();
        $serverStatus = $this->popsApi->checkPopsStatus();
    
        //enhance error handling
        if ($connection === 'Failed.') {
            return response()->json([
                'message' => 'This IP is not whitelisted.',
            ], Response::HTTP_BAD_REQUEST);
        } else if ($serverStatus === 'Down') {
            return response()->json([
                'message' => 'POPS server down.',
            ], Response::HTTP_BAD_REQUEST);
        } else {
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
            $payload->signatoryid = $stallprofile->signatory->signatoryId;

            //sectioncode description
            $sectionCodeDes = $this->parameterRepository->getSection($stallprofile->sectionCode);
            
            $newItems = [];
            foreach ($items as $item) {
                //check if this month is already paid
                $date = $this->LedgerRepository->checkLedgerExists($payload->ownerId, $item['label']);
                if ($date && $date->ORNum) {
                    return response()->json([
                        'message' => $item['label']. ' is already paid',
                    ], Response::HTTP_BAD_REQUEST);
                }

                if($item['value'] === 'current') {
                    $description = $sectionCodeDes;
                    $description1 = 'Current';
                } else {
                    $description = $sectionCodeDes;
                    $description1 = 'Previous';
                }
                $newItems[] = [
                    'value' => $item['value'],
                    'label' => $item['label'],
                    'amount_basic' => $item['amountBasic'],
                    'office_code' => $officeCode,
                    'description' => $description,
                    'description1' => $description1,
                ];

                // Create the item for the interest
                if($item['interest'] > 0) {
                    $newItems[] = [
                        'value' => $item['value'],
                        'label' => $item['label'],
                        'amount_basic' => $item['interest'],
                        'office_code' => $officeCode,
                        'description' => 'Fines',
                        'description1' => 'Penalties',
                    ];
                }
                

                // Create the item for the surcharge
                if($item['surcharge'] > 0) {
                    $newItems[] = [
                        'value' => $item['value'],
                        'label' => $item['label'],
                        'amount_basic' => $item['surcharge'],
                        'office_code' => $officeCode,
                        'description' => 'Fines',
                        'description1' => 'Penalties',
                    ];
                }

                $has_extension = ($item['extensionRate'] == '0.00') ? false : true;
                // Create the item for the extension
                if ($has_extension) {
                    $newItems[] = [
                        'value' => $item['value'],
                        'label' => $item['label'],
                        'amount_basic' => $item['extensionRate'],
                        'office_code' => $officeCode,
                        'description' => 'Extension',
                        'description1' => 'Rental',
                    ];
                }
                
            }

            $is_op_exists = $this->opRepository->checkOP($payload, $newItems);
            // items already generated
            if ($is_op_exists) {
                return response()->json([
                    'message' => 'This Item/s are already generated',
                ], Response::HTTP_BAD_REQUEST);
            }

            $ledgerIds = [];
            foreach ($newItems as $newItem) {
                //get account code
                $accountCode = $this->opRepository->getAccountCode($officeCode, $newItem['description'], $newItem['description1']);

                // account code details
                $popsAccountCode = $accountCode['accountcode'];
                $popsDescription = $accountCode['description'];

                $payload->accoutcodes = $popsAccountCode;
                $payload->amount = $newItem['amount_basic'];
                $payload->OPRefId = $OPRefId;
                $payload->purpose = $newItem['label'];

                //for current month only
                if($newItem['value'] === 'current'){
                    //check if the current month is already added in ledger
                    //if added, get the ledgerid
                    $ledger = $this->LedgerRepository->checkLedgerExists($payload->ownerId, $newItem['label']);
                    if ($ledger) {
                        $ledgerIds[] = $ledger->stallOwnerAccountId;
                    } else { //create ledger
                        $newLedger = $this->LedgerRepository->createLedger($payload, $newItem);
                        $ledgerIds[] = $newLedger->stallOwnerAccountId;
                    }
                } else {
                    $ledgerIds[] = $newItem['value'];
                }

                //NOTE: I just need the last saved ids since the all ids are there and it will be use when updating the ledger from webhook
                $ids = implode(',', $ledgerIds);

                $this->opRepository->saveOP($payload, $ids);
                $totalAmount += $newItem['amount_basic'];

                $itemsPaid[] = [
                    'accountcode' => $popsAccountCode,
                    'description' => $popsDescription,
                    'amount' => $newItem['amount_basic'],
                ];
            }

            //save to pops
            $payload->items = $itemsPaid;
            $this->popsApi->createPayment($payload);
            $signatory = $this->signatoryRepository->findById($stallprofile->signatory->signatoryId);
            $pdf = Pdf::loadView('pdf.top', [
                'owner_name' => $payload->name,
                'owner_address' => "Stall #" . $stallprofile->stallNoId . ' - ' . $stallprofile->stallDescription,
                'account_code_details' => $itemsPaid,
                'total_amount' => $totalAmount,
                'op_number' => $OPRefId,
                'owner_id' => $payload->ownerId,
                'op_date' => Carbon::now()->format('F j, Y'),
                'op_sys' => 'ESMS',
                'post_by' => $payload->postBy,
                'remarks' => '',
                'signatory' => $signatory->signatoryFullName,
                'valid_until_date' => $this->opRepository->OPDueDate(true),
            ]);

            $pdf->setPaper('A4', 'portrait'); // Options: A4, letter, legal, etc.

            // Define filename and path
            $filename = "public/ops/{$payload->ownerId}/{$OPRefId}.pdf";

            // Save PDF content to storage/app/pdfs/OP_12345.pdf
            Storage::put($filename, $pdf->output());

            return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
        }

    }
}
