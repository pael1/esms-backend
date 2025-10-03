<?php

namespace App\Http\Controllers\Api;

use App\Models\Stallprofile;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateStallRequest;
use App\Http\Requests\UpdateStallRequest;
use App\Interface\Service\StallServiceInterface;

class StallController extends Controller
{
    private $stallService;

    public function __construct(StallServiceInterface $stallService)
    {
        $this->stallService = $stallService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->stallService->findManyStalls($request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateStallRequest $request)
    {
        return $this->stallService->createStall($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->stallService->findStallById($id);
        // $stall = Stallprofile::findOrFail($id);
        // return response()->json($stall);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStallRequest $request, string $id)
    {
        return $this->stallService->updateStall($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
