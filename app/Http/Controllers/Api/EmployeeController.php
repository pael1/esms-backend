<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interface\Service\EmployeeServiceInterface;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    private $employeeService;

    public function __construct(EmployeeServiceInterface $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // logger($request->all());
        return $this->employeeService->findMany($request);
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
        return $this->employeeService->delete($id);
    }
}
