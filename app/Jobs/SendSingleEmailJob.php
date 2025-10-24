<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use App\Repositories\Campaigns\CampaignRepository;
use App\Models\User;
use App\Repositories\Campaigns\CampaignRepositoryInterface;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class SendSingleEmailJob implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    public int $campaignId;
    public int $userId;
    public $tries = 5;
    public $backoff = 60;
    private const RATE_PER_MIN = 10;  
    private const RATE_LIMIT_DURATION = 60; 

    public function __construct(int $campaignId, int $userId)
    {
        $this->campaignId = $campaignId;
        $this->userId = $userId;
    }

    public function handle(CampaignRepositoryInterface $campaignRepo, UserRepositoryInterface $userRepo): void
    {
        $campaign = $campaignRepo->find($this->campaignId);

        $user = $userRepo->find($this->userId);

        if (!$campaign || !$user) {
            return;
        }

        $key = 'email_send_rate';
        if (RateLimiter::tooManyAttempts($key, self::RATE_PER_MIN)) {
            $seconds = RateLimiter::availableIn($key);
             Log::info("Rate limit exceeded, releasing job for {$seconds} seconds.", [
                'campaign_id' => $campaign->id,
                'user_id' => $user->id
            ]);

            $this->release($seconds + 1);
            // $this->release($seconds);
            return;
        }

        RateLimiter::hit($key,  self::RATE_LIMIT_DURATION);

        try {
            Mail::to($user->email)->send(new CampaignMail(
                $campaign->subject,
                $campaign->content,
                $user,
                $campaign->id
            ));

            DB::transaction(function () use ($campaign, $user, $campaignRepo) {
                DB::table('campaign_user_sent')->insert([
                    'campaign_id' => $campaign->id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $campaignRepo->increment($campaign, 'sent_count');
            });
        } catch (\Throwable $e) {
            Log::error('Campaign email failed to send', [
                'campaign_id' => $this->campaignId,
                'user_id' => $this->userId,
                'email' => $user->email, 
                'exception' => $e->getMessage(),
            ]);

            $campaignRepo->increment($campaign, 'error_count');
            throw $e;
        }
    }
}
