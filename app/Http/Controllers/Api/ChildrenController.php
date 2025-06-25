<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interface\Service\ChildrenServiceInterface;
use Illuminate\Http\Request;

class ChildrenController extends Controller
{
    private $childrenService;

    public function __construct(ChildrenServiceInterface $childrenService)
    {
        $this->childrenService = $childrenService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->childrenService->findMany($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
