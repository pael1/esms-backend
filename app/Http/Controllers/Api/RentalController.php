<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interface\Service\RentalServiceInterface;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    private $rentalService;

    public function __construct(RentalServiceInterface $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->rentalService->findMany($request);
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
        return $this->rentalService->findById($id);
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
