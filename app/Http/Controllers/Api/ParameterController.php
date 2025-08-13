<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interface\Service\ParameterServiceInterface;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    private $parameterService;

    public function __construct(ParameterServiceInterface $parameterService)
    {
        $this->parameterService = $parameterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->parameterService->findMany($request);
    }

    public function subSection(Request $request)
    {
        return $this->parameterService->findSubSection($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
