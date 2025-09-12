<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interface\Service\StallOwnerServiceInterface;
use Illuminate\Http\Request;

class StallOwnerController extends Controller
{
    private $stallOwnerService;

    public function __construct(StallOwnerServiceInterface $stallOwnerService)
    {
        $this->stallOwnerService = $stallOwnerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->stallOwnerService->findMany($request);
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
