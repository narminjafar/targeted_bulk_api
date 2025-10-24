<?php

namespace App\Jobs;

use App\Services\SegmentService;
use App\Models\UserUnsubscribed;
use App\Repositories\Campaigns\CampaignRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendCampaignEmailsJob implements ShouldQueue
{
    use Dispatchable,Queueable, SerializesModels;

    public int $campaignId;
    public string $lockKey;
    public $tries = 2;
    public array $backoff = [10, 30, 60, 120, 300];
    public bool $failOnTimeout = true;
    private const CHUNK_SIZE = 2000;

    public function __construct(
        int $campaignId,
        string $lockKey
    ) {
        $this->campaignId = $campaignId;
        $this->lockKey = $lockKey;
    }

    public function handle(CampaignRepositoryInterface $campaignRepo, SegmentService $segmentService): void
    {
        $campaign = $campaignRepo->find($this->campaignId);

        if (!$campaign) {
            Cache::forget($this->lockKey);
            return;
        }

        try {

            $campaignRepo->update($campaign, ['status' => 'sending']);

            $query = $segmentService->filterUsers($campaign->segment->filter_json);

            if ($query->count() === 0) {
                Log::warning("Campaign {$campaign->id} has no users to send emails to.", [
                    'campaign_id' => $campaign->id,
                    'lockKey' => $this->lockKey,
                ]);
            }
            $query->chunkById(self::CHUNK_SIZE, function (Collection $users) use ($campaign) {

                $userIds = $users->pluck('id')->toArray();
                $unsubscribedIds = UserUnsubscribed::where('campaign_id', $campaign->id)->whereIn('user_id', $userIds)
                    ->pluck('user_id');

                $alreadySentIds = DB::table('campaign_user_sent')
                    ->where('campaign_id', $campaign->id)
                    ->whereIn('user_id', $userIds)
                    ->pluck('user_id');

                foreach ($users as $user) {
                    if ($unsubscribedIds->contains($user->id) || $alreadySentIds->contains($user->id)) {
                        continue;
                    }

                    dispatch(new SendSingleEmailJob(
                        $campaign->id,
                        $user->id
                    ));
                }
            });

            $sentCount = DB::table('campaign_user_sent')
                ->where('campaign_id', $campaign->id)
                ->count();


            $campaignRepo->update($campaign, [
                'status' => 'done',
                'total_recipients' => $sentCount,
            ]);
        } catch (Throwable $e) {
            dd( $e->getMessage());
            $campaignRepo->update($campaign, ['status' => 'failed']);

            throw $e;
        } finally {
            Cache::forget($this->lockKey);
        }
    }
}
