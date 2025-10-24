<?php

namespace App\Repositories\Campaigns;

use App\Models\Campaign;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class CampaignRepository extends AbstractRepository implements CampaignRepositoryInterface
{
    protected Model $model;

    public function __construct(Campaign $model)
    {
        $this->model = $model;
    }
    public function findByUuid(string $uuid)
    {
        return $this->model->where('uuid', $uuid)->first();
    }

    public function increment($model, $field)
    {
        $model->increment($field);

    }

}
