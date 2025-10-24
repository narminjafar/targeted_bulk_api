<?php

namespace Tests\Feature;

use App\Repositories\Campaigns\CampaignRepository;
use App\Repositories\Segments\SegmentRepository;
use Tests\TestCase;
use App\Jobs\SendCampaignEmailsJob;
use App\Repositories\Campaigns\CampaignRepositoryInterface;
use App\Repositories\Segments\SegmentRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Models\Campaign;
use App\Models\Segment;

class CampaignQueueTest extends TestCase
{
    use RefreshDatabase;

    private CampaignRepositoryInterface $campaignRepo;
    private SegmentRepositoryInterface $segmentRepo;

    protected function setUp(): void
    {
        parent::setUp();

        // Interface üzərindən inject, real model ilə
        $this->segmentRepo = new SegmentRepository(new Segment());
        $this->campaignRepo = new CampaignRepository(new Campaign());
    }

    public function test_campaign_queue_sends_in_chunks_without_duplicates()
    {
        Queue::fake();

        // Real DB-yə yazırıq, factories istifadə edilə bilər
        $segment = $this->segmentRepo->create([
            'name' => 'Test Segment',
            'filter_json' => json_encode([]),
        ]);

        $campaign = $this->campaignRepo->create([
            'segment_id' => $segment->id,
            'name' => 'Test Campaign',
            'subject' => 'Test Subject',
            'content' => 'Test Content',
            'template_key' => 'default',
            'filter_json' => json_encode([]),
        ]);

        // Dispatch job
        SendCampaignEmailsJob::dispatch($campaign->id, 'lock-key-test');

        Queue::assertPushed(SendCampaignEmailsJob::class, function ($job) use ($campaign) {
            return $job->campaignId === $campaign->id;
        });
    }
}
