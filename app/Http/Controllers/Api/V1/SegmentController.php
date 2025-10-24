<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SegmentFilterRequest;
use App\Http\Requests\StoreSegmentRequest;
use App\Services\SegmentService;

class SegmentController extends Controller
{
    public function __construct(
        private SegmentService $segmentService
    ) {}

    public function index(SegmentFilterRequest $request)
    {
        $perPage = $request->perPage();

        return $this->segmentService->allPaginated($perPage);
    }

    public function store(StoreSegmentRequest $request)
    {
        $segment = $this->segmentService->create($request->validated());
        return response()->json($segment, 201);
    }

    public function show(int $id)
    {
        $segment = $this->segmentService->get($id);
        return response()->json($segment);
    }


    public function preview(int $id)
    {
        return response()->json(
            $this->segmentService->preview($id, 20)
        );
    }
}
