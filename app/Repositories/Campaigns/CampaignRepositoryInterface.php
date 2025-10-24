<?php
namespace App\Repositories\Campaigns;

use Illuminate\Database\Eloquent\Model;

interface CampaignRepositoryInterface {
    public function create(array $data);
    public function all(array $filters = []);
    public function find(int $id);
    public function update($model, array $data);
    public function paginate(int $perPage, array $filters = []);
    public function increment($model, string $field);
}
