<?php

namespace App\Services;

use App\Exceptions\CustomApiException;
use App\Jobs\SendCampaignEmailsJob;
use App\Repositories\Campaigns\CampaignRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;

class CampaignService
{
    protected $campaignRepo;

    public function __construct(CampaignRepositoryInterface $campaignRepo)
    {
        $this->campaignRepo = $campaignRepo;
    }

    public function allPaginated(array $filters, int $perPage)
    {
        return $this->campaignRepo->paginate($perPage, $filters);
    }

    public function get(int $id)
    {
        $campaign = $this->campaignRepo->find($id);

        if (!$campaign) {
            throw new CustomApiException(
                'Tələb olunan kampaniya tapılmadı.',
                'NOT_FOUND',
                Response::HTTP_NOT_FOUND
            );
        }

        return $campaign;
    }

    public function getByStats(int $id)
    {
        $campaign = $this->campaignRepo->find($id);

        if (!$campaign) {
            throw new CustomApiException(
                'Tələb olunan kampaniya tapılmadı.',
                'NOT_FOUND',
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->filterColumns(
            $campaign,
            ['status', 'total_recipients', 'sent_count', 'error_count']
        );
    }

    // public function getByUuid(string $uuid)
    // {
    //     return $this->campaignRepo->findByUuid($uuid);
    // }

    public function create(array $data, ?string $idempotencyKey = null)
    {
        if ($idempotencyKey && Cache::has('campaign_idempotency:' . $idempotencyKey)) {
            return Cache::get('campaign_idempotency:' . $idempotencyKey);
        }

        $campaign = $this->campaignRepo->create($data)->fresh();

        $result = $this->filterColumns($campaign, ['id', 'status', 'total_recipients']);

        if ($idempotencyKey) {
            Cache::put('campaign_idempotency:' . $idempotencyKey, $result, now()->addMinutes(30));
        }

        return $result;
    }

    public function queueSendById(int $id): bool
    {
        $campaign = $this->campaignRepo->find($id);

        // if (!$campaign) return false;

        if (!$campaign) {
            throw new CustomApiException(
                'Göndəriləcək kampaniya tapılmadı.',
                'NOT_FOUND',
                Response::HTTP_NOT_FOUND
            );
        }
         Cache::forget("campaign_send_lock:" . $campaign->id);

        $lockKey = 'campaign_send_lock:' . $campaign->id;
        if (!Cache::add($lockKey, true, now()->addMinutes(10))) {
            throw new CustomApiException(
                'Kampaniya hazırda göndərilmə prosesindədir.',
                'RATE_LIMIT',
                Response::HTTP_CONFLICT
            );
        }

        $this->campaignRepo->update($campaign, ['status' => 'queued']);

        dispatch(new SendCampaignEmailsJob($campaign->id, $lockKey));

        return true;
    }


    protected function filterColumns($model, array $columns)
    {
        return collect($model->toArray())->only($columns);
    }
}
