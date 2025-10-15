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
        $exists = Stallprofile::where('stall_no_id', $request->stall_id)
            ->where('stall_id_ext', $request->extension)
            ->where('stallType', $request->type)
            ->where('marketCode', $request->market)
            ->whereRaw('SUBSTRING(sectionCode, 3, 2) = ?', [$request->section])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'The stall number already exists.',
                'errors' => [
                    'Stall Number' => ['The stall number already exists.']
                ]
            ], 422);
        }

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
        $exists = Stallprofile::where('stall_no_id', $request->stall_id)
            ->where('stall_id_ext', $request->extension)
            ->where('stallType', $request->type)
            ->where('marketCode', $request->market)
            ->whereRaw('SUBSTRING(sectionCode, 3, 2) = ?', [$request->section])
            ->where('stallProfileId', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'The stall number already exists.',
                'errors' => [
                    'Stall Number' => ['The stall number already exists.']
                ]
            ], 422);
        }

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
