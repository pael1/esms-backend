<?php

namespace App\Service;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Resources\AwardeeListResource;
use App\Interface\Repository\ParameterRepositoryInterface;
use App\Interface\Service\ReportServiceInterface;
use App\Interface\Repository\ReportRepositoryInterface;
use App\Repository\ParameterRepository;

class ReportService implements ReportServiceInterface
{
    private $reportRepository;
    private $parameterRepository;

    public function __construct(ReportRepositoryInterface $reportRepository, ParameterRepositoryInterface $parameterRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->parameterRepository = $parameterRepository;
    }

    public function masterlist(object $payload)
    {
        $data = $this->reportRepository->masterlist($payload);

        return AwardeeListResource::collection($data);
    }

    public function masterlist_print(object $payload)
    {
        $data = $this->reportRepository->masterlist_print($payload);
        $results = AwardeeListResource::collection($data)->toArray(request());
        $marketName = $this->parameterRepository->findByFieldIdFieldValue("MARKETCODE", $payload->marketcode);
        $pdf = Pdf::loadView('pdf.masterlist', [
            'marketName' => $marketName,
            'awardees' => $results
        ]);

        $pdf->setPaper('legal', 'landscape'); // Options: A4, letter, legal, etc.

        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }
}
