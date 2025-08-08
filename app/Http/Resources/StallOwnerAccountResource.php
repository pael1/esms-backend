<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StallOwnerAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Calculate the surcharge based on 25% of the basic amount
        $surcharge = $this->amountBasic * 0.25;

        // Initialize months delayed and interest to 0
        $monthsDelayed = 0;
        $interest = 0;

        // Create a Carbon date from the month and year properties for the due date
        $dueDate = Carbon::createFromFormat('F Y', $this->month . ' ' . $this->year);

        // Check if the due date is in the past
        if (Carbon::now()->greaterThan($dueDate->endOfMonth())) {
            // Calculate the number of months from the due date month to the current month, inclusive.
            // For example, if due in June and paid in August, this will correctly calculate 3 months.
            $monthsDelayed = (Carbon::now()->year - $dueDate->year) * 12 + (Carbon::now()->month - $dueDate->month) + 1;
        }

        // Calculate the interest based on 2% of the basic amount plus surcharge per month delayed
        $interest = (($this->amountBasic + $surcharge) * 0.02) * $monthsDelayed;

        // Return the full resource array with the new calculations
        return [
            'stallOwnerAccountId' => $this->stallOwnerAccountId,
            'ownerId' => $this->ownerId,
            'month' => $this->month,
            'year' => $this->year,
            'date' => $this->month.' '.$this->year,
            'amountBasic' => $this->amountBasic,
            'amountSurc' => $this->amountSurc,
            'amountInt' => $this->amountInt,
            'surcharge' => $surcharge, // New calculated surcharge
            'interest' => $interest, // New calculated interest
            'monthsDelayed' => $monthsDelayed, // New calculated months delayed
            'ORNum' => $this->ORNum,
            'OPRefId' => $this->OPRefId,
            'ORDate' => $this->ORDate,
            'generatedBy' => $this->generatedBy,
            'isadded' => $this->isadded,
            'expdate' => $this->expdate,
        ];
    }
}
