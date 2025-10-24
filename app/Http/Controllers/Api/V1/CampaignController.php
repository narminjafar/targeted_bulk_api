<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CampaignFilterRequest;
use App\Http\Requests\StoreCampaignRequest;
use App\Jobs\SendCampaignEmailsJob;
use App\Services\CampaignService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CampaignController extends Controller
{
    public function __construct(private CampaignService $campaignService) {}

    public function index(CampaignFilterRequest $request)
    {
        $filters = $request->filters();
        $perPage = $request->perPage();

        $campaigns = $this->campaignService->allPaginated($filters, $perPage);

        return response()->json($campaigns);
    }

    public function show(int $id)
    {
        $campaign = $this->campaignService->get($id);
        return response()->json($campaign);
    }

    public function showWithStats(int $id)
    {
        $campaign = $this->campaignService->getByStats($id);
        return response()->json($campaign);
    }


    public function store(StoreCampaignRequest $request)
    {
        $idempotencyKey = $request->header('Idempotency-Key');

        $campaign = $this->campaignService->create($request->validated(), $idempotencyKey);
        return response()->json($campaign,  201);
    }

    public function send(int $id)
    {
        $this->campaignService->queueSendById($id);

        return response()->json(['message' => 'Campaign queued successfully'], Response::HTTP_ACCEPTED);
    }

    public function stats(int $id)
    {
        $campaign = $this->campaignService->get($id);
        return response()->json([
            'total_recipients' => $campaign->total_recipients,
            'sent_count' => $campaign->sent_count,
            'error_count' => $campaign->error_count
        ]);
    }
}
